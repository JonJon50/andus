<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\PendingMail;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class ContactInquiryFlowTest extends TestCase
{
    use RefreshDatabase;

    private const SUCCESS_MESSAGE = 'Thank you for contacting AndUs. Your inquiry has been received.';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'mail.default' => 'array',
            'mail.contact_to' => 'inquiries@example.test',
            'mail.from.address' => 'sender@example.test',
            'mail.from.name' => 'Example Business',
        ]);
    }

    #[DataProvider('invalidInputs')]
    public function test_invalid_input_shows_inline_errors_without_side_effects(string $field, mixed $value, string $rule): void
    {
        Mail::fake();

        $form = $this->validForm()
            ->set($field, $value)
            ->call('save')
            ->assertHasErrors([$field => $rule])
            ->assertSet($field, $value)
            ->assertSet('submitted', false)
            ->assertDontSee(self::SUCCESS_MESSAGE);

        $error = $form->instance()->getErrorBag()->first($field);
        $this->assertNotSame('', $error);
        $form->assertSee($error);

        $this->assertDatabaseCount('contact_inquiries', 0);
        Mail::assertNothingSent();
    }

    public static function invalidInputs(): array
    {
        return [
            'missing name' => ['name', '', 'required'],
            'missing email' => ['email', '', 'required'],
            'missing message' => ['message', '', 'required'],
            'invalid email' => ['email', 'not-an-email', 'email'],
            'short message' => ['message', 'Too short', 'min'],
            'long name' => ['name', str_repeat('N', 256), 'max'],
            'long email' => ['email', str_repeat('a', 64).'@'.str_repeat('b', 63).'.'.str_repeat('c', 63).'.'.str_repeat('d', 60).'.test', 'max'],
            'long phone' => ['phone', str_repeat('1', 51), 'max'],
            'long company' => ['company', str_repeat('C', 256), 'max'],
        ];
    }

    public function test_valid_boundaries_and_empty_optional_fields_are_accepted(): void
    {
        Mail::fake();

        $this->validForm()
            ->set('name', str_repeat('N', 255))
            ->set('email', str_repeat('a', 64).'@'.str_repeat('b', 62).'.'.str_repeat('c', 62).'.'.str_repeat('d', 59).'.test')
            ->set('phone', str_repeat('1', 50))
            ->set('company', str_repeat('C', 255))
            ->set('message', '1234567890')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee(self::SUCCESS_MESSAGE);

        $this->validForm()
            ->set('phone', '')
            ->set('company', '')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee(self::SUCCESS_MESSAGE);

        $this->assertDatabaseCount('contact_inquiries', 2);
        $this->assertDatabaseHas('contact_inquiries', ['service_id' => null, 'phone' => '', 'company' => '']);
        Mail::assertSent(ContactInquirySubmitted::class, 2);
    }

    #[DataProvider('serviceSelections')]
    public function test_successful_submission_persists_details_and_notifies_the_recipient(?string $title, ?string $slug): void
    {
        Mail::fake();
        $service = $title === null ? null : $this->createService($title, $slug);

        $this->validForm()
            ->set('service_id', $service ? (string) $service->id : '')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSee(self::SUCCESS_MESSAGE)
            ->assertSet('service_id', '')
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('phone', '')
            ->assertSet('company', '')
            ->assertSet('message', '');

        $this->assertDatabaseCount('contact_inquiries', 1);
        $this->assertDatabaseHas('contact_inquiries', [
            'service_id' => $service?->id,
            'name' => 'Test Visitor',
            'email' => 'visitor@example.test',
            'phone' => '555-0100',
            'company' => 'Example Company',
            'message' => 'Please help with our website project.',
            'status' => 'new',
        ]);

        Mail::assertSent(ContactInquirySubmitted::class, 1);
        Mail::assertNothingQueued();
        Mail::assertSent(ContactInquirySubmitted::class, function (ContactInquirySubmitted $mail) use ($service): bool {
            $this->assertTrue($mail->hasTo('inquiries@example.test'));
            $this->assertTrue($mail->inquiry->exists);
            $this->assertSame(ContactInquiry::sole()->id, $mail->inquiry->id);
            $this->assertSame($service?->id, $mail->inquiry->service?->id);
            $mail->assertHasSubject('New AndUs Website Inquiry');
            $mail->assertHasReplyTo('visitor@example.test', 'Test Visitor');

            return true;
        });
    }

    public static function serviceSelections(): array
    {
        return [
            'no service' => [null, null],
            'website' => ['Business Website Development', 'website'],
            'application' => ['Custom Web Applications', 'application'],
            'automation' => ['Workflow Automation', 'automation'],
            'database' => ['Database & Reporting Solutions', 'database'],
        ];
    }

    public function test_nonexistent_service_is_rejected_outside_production(): void
    {
        Mail::fake();

        $this->validForm()
            ->set('service_id', '99999')
            ->call('save')
            ->assertHasErrors(['service_id' => 'exists'])
            ->assertSet('submitted', false)
            ->assertDontSee(self::SUCCESS_MESSAGE);

        $this->assertDatabaseCount('contact_inquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_persistence_failure_currently_sends_fallback_mail_and_shows_success(): void
    {
        Mail::fake();
        Log::spy();
        $service = $this->createService('Workflow Automation', 'automation');
        $this->simulatePersistenceFailure();

        $this->validForm()
            ->set('service_id', (string) $service->id)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSet('name', '')
            ->assertSet('message', '')
            ->assertSee(self::SUCCESS_MESSAGE);

        $this->assertDatabaseCount('contact_inquiries', 0);
        $this->assertPersistenceFailureLogged();
        Mail::assertSent(ContactInquirySubmitted::class, 1);
        Mail::assertSent(ContactInquirySubmitted::class, function (ContactInquirySubmitted $mail) use ($service): bool {
            $this->assertNotInstanceOf(ContactInquiry::class, $mail->inquiry);
            $this->assertSame('Test Visitor', $mail->inquiry->name);
            $this->assertSame('Please help with our website project.', $mail->inquiry->message);
            $this->assertSame($service->id, $mail->inquiry->service->id);
            $this->assertTrue($mail->hasTo('inquiries@example.test'));
            $mail->assertHasReplyTo('visitor@example.test', 'Test Visitor');
            $mail->assertSeeInHtml('Workflow Automation');

            return true;
        });
    }

    public function test_mail_failure_after_persistence_propagates_and_retains_input(): void
    {
        $this->simulateMailFailure();
        $form = $this->validForm();

        $this->assertMailFailure($form);

        $this->assertDatabaseCount('contact_inquiries', 1);
        $this->assertDatabaseHas('contact_inquiries', ['email' => 'visitor@example.test', 'status' => 'new']);
        $this->assertFalse($form->instance()->submitted);
        $this->assertSame('Test Visitor', $form->instance()->name);
        $this->assertSame('Please help with our website project.', $form->instance()->message);
    }

    public function test_combined_failure_propagates_without_storing_an_inquiry(): void
    {
        Log::spy();
        $this->simulatePersistenceFailure();
        $this->simulateMailFailure();
        $form = $this->validForm();

        $this->assertMailFailure($form);

        $this->assertDatabaseCount('contact_inquiries', 0);
        $this->assertPersistenceFailureLogged();
        $this->assertFalse($form->instance()->submitted);
        $this->assertSame('Test Visitor', $form->instance()->name);
    }

    public function test_previous_success_banner_currently_remains_after_a_later_validation_failure(): void
    {
        Mail::fake();

        $this->validForm()
            ->call('save')
            ->assertSee(self::SUCCESS_MESSAGE)
            ->set('name', 'Another Visitor')
            ->set('email', 'invalid-email')
            ->set('message', 'Another project inquiry.')
            ->call('save')
            ->assertHasErrors(['email' => 'email'])
            ->assertSet('submitted', true)
            ->assertSee(self::SUCCESS_MESSAGE)
            ->assertSet('name', 'Another Visitor');

        $this->assertDatabaseCount('contact_inquiries', 1);
        Mail::assertSent(ContactInquirySubmitted::class, 1);
    }

    #[DataProvider('serviceSelections')]
    public function test_array_mailer_builds_and_renders_the_actual_notification(?string $title, ?string $slug): void
    {
        $service = $title === null ? null : $this->createService($title, $slug);
        $transport = Mail::mailer('array')->getSymfonyTransport();
        $this->assertInstanceOf(ArrayTransport::class, $transport);

        $this->validForm()
            ->set('service_id', $service ? (string) $service->id : '')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee(self::SUCCESS_MESSAGE);

        $this->assertCount(1, $transport->messages());
        $message = $transport->messages()->sole()->getOriginalMessage();
        $this->assertSame('New AndUs Website Inquiry', $message->getSubject());
        $this->assertSame('inquiries@example.test', $message->getTo()[0]->getAddress());
        $this->assertSame('visitor@example.test', $message->getReplyTo()[0]->getAddress());
        $this->assertSame('Test Visitor', $message->getReplyTo()[0]->getName());
        $this->assertSame('sender@example.test', $message->getFrom()[0]->getAddress());

        foreach (['Test Visitor', 'visitor@example.test', '555-0100', 'Example Company', 'Please help with our website project.', $title ?? 'No service selected'] as $detail) {
            $this->assertStringContainsString(e($detail), $message->getHtmlBody());
        }

        $this->assertDatabaseCount('contact_inquiries', 1);
    }

    private function validForm(): Testable
    {
        return Livewire::test(ContactForm::class)
            ->assertDontSee(self::SUCCESS_MESSAGE)
            ->set('name', 'Test Visitor')
            ->set('email', 'visitor@example.test')
            ->set('phone', '555-0100')
            ->set('company', 'Example Company')
            ->set('message', 'Please help with our website project.');
    }

    private function createService(string $title, string $slug): Service
    {
        return Service::create(['title' => $title, 'slug' => $slug, 'description' => 'Test service']);
    }

    private function simulatePersistenceFailure(): void
    {
        ContactInquiry::creating(function (): void {
            throw new RuntimeException('Simulated persistence failure.');
        });
    }

    private function simulateMailFailure(): void
    {
        $pending = Mockery::mock(PendingMail::class);
        $pending->shouldReceive('send')
            ->once()
            ->with(Mockery::type(ContactInquirySubmitted::class))
            ->andThrow(new RuntimeException('Simulated mail failure.'));

        Mail::shouldReceive('to')->once()->with('inquiries@example.test')->andReturn($pending);
    }

    private function assertMailFailure(Testable $form): void
    {
        try {
            $form->call('save');
            $this->fail('The current mail failure must propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated mail failure.', $exception->getMessage());
        }
    }

    private function assertPersistenceFailureLogged(): void
    {
        Log::shouldHaveReceived('error')->once()->with('Contact inquiry database save failed.', [
            'error' => 'Simulated persistence failure.',
            'email' => 'visitor@example.test',
        ]);
    }
}
