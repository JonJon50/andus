<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactInquirySubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class ContactRecipientConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_example_environment_declares_the_canonical_recipient_key(): void
    {
        $example = file_get_contents(base_path('.env.example'));

        $this->assertMatchesRegularExpression('/^CONTACT_TO_EMAIL=$/m', $example);
        $this->assertDoesNotMatchRegularExpression('/^CONTACT_TO_ADDRESS=/m', $example);
    }

    public function test_mail_configuration_reads_the_canonical_environment_variable(): void
    {
        $keys = ['CONTACT_TO_EMAIL', 'CONTACT_TO_ADDRESS'];
        $originalEnv = [];
        $originalServer = [];

        foreach ($keys as $key) {
            $originalEnv[$key] = $_ENV[$key] ?? null;
            $originalServer[$key] = $_SERVER[$key] ?? null;
        }

        try {
            $_ENV['CONTACT_TO_EMAIL'] = $_SERVER['CONTACT_TO_EMAIL'] = 'inquiries@example.test';
            $_ENV['CONTACT_TO_ADDRESS'] = $_SERVER['CONTACT_TO_ADDRESS'] = 'legacy@example.test';

            $mail = require base_path('config/mail.php');

            $this->assertSame('inquiries@example.test', $mail['contact_to']);
        } finally {
            foreach ($keys as $key) {
                if ($originalEnv[$key] === null) {
                    unset($_ENV[$key]);
                } else {
                    $_ENV[$key] = $originalEnv[$key];
                }

                if ($originalServer[$key] === null) {
                    unset($_SERVER[$key]);
                } else {
                    $_SERVER[$key] = $originalServer[$key];
                }
            }
        }
    }

    #[DataProvider('missingRecipients')]
    public function test_missing_recipient_fails_before_saving_or_sending(mixed $recipient): void
    {
        config(['mail.contact_to' => $recipient]);
        Mail::fake();

        $form = $this->validForm();

        try {
            $form->call('save');
            $this->fail('A missing inquiry recipient must fail clearly.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Inquiry recipient is not configured. Set CONTACT_TO_EMAIL.', $exception->getMessage());
        }

        $this->assertDatabaseCount('contact_inquiries', 0);
        Mail::assertNothingSent();
        $this->assertFalse($form->instance()->submitted);
        $this->assertSame('Test Visitor', $form->instance()->name);
    }

    public static function missingRecipients(): array
    {
        return [
            'missing' => [null],
            'empty' => [''],
            'whitespace' => [" \t\n"],
            'non-string' => [false],
        ];
    }

    public function test_configured_recipient_preserves_successful_submission(): void
    {
        config(['mail.contact_to' => 'inquiries@example.test']);
        Mail::fake();

        $this->validForm()
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSet('service_id', '')
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('phone', '')
            ->assertSet('company', '')
            ->assertSet('message', '');

        $this->assertDatabaseCount('contact_inquiries', 1);
        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Test Visitor',
            'email' => 'visitor@example.test',
            'service_id' => null,
            'status' => 'new',
        ]);
        Mail::assertSent(ContactInquirySubmitted::class, 1);
        Mail::assertSent(ContactInquirySubmitted::class, fn ($mail) => $mail->hasTo('inquiries@example.test'));
    }

    private function validForm(): Testable
    {
        return Livewire::test(ContactForm::class)
            ->set('name', 'Test Visitor')
            ->set('email', 'visitor@example.test')
            ->set('phone', '555-0100')
            ->set('company', 'Example Company')
            ->set('message', 'Please help with our website project.');
    }
}
