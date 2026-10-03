<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HttpsLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_forwarded_https_generates_secure_contact_urls_without_forcing_the_scheme(): void
    {
        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.10'])
            ->withHeaders([
                'X-Forwarded-Proto' => 'https',
            ])
            ->get('http://www.withandus.com/contact');

        $response->assertOk()->assertSeeLivewire(ContactForm::class);
        $this->assertTrue($this->app['request']->isSecure());
        $this->assertSecurePageUrls($response->getContent());

        preg_match('/<script[^>]+src="([^"]+)"[^>]+data-update-uri=/', $response->getContent(), $matches);
        $this->assertNotEmpty($matches[1] ?? null);
        $this->get(html_entity_decode($matches[1]))->assertOk();
    }

    public function test_forwarded_host_is_not_trusted(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'untrusted.example',
        ])->get('http://www.withandus.com/contact');

        $response->assertOk();
        $this->assertSame('www.withandus.com', $this->app['request']->getHost());
        $this->assertSecurePageUrls($response->getContent());
    }

    public function test_ordinary_local_http_remains_http(): void
    {
        $response = $this->get('http://localhost/contact');

        $response->assertOk()->assertSee('href="http://localhost/contact"', false);
        $response->assertSee('data-update-uri="http://localhost/', false);
        $this->assertFalse($this->app['request']->isSecure());
    }

    #[DataProvider('serviceGuidance')]
    public function test_service_selection_updates_the_guidance_panel(string $title, string $slug, string $guidance): void
    {
        $service = Service::create([
            'title' => $title,
            'slug' => $slug,
            'description' => 'Test service',
        ]);

        Livewire::test(ContactForm::class)
            ->assertSee('Not sure where to start?')
            ->set('service_id', (string) $service->id)
            ->assertSee($guidance)
            ->assertDontSee('Not sure where to start?');
    }

    public static function serviceGuidance(): array
    {
        return [
            'website' => ['Business Website Development', 'website', 'Website Project Fit'],
            'application' => ['Custom Web Applications', 'application', 'Custom Application Fit'],
            'automation' => ['Workflow Automation', 'automation', 'Automation Opportunity'],
            'database' => ['Database & Reporting Solutions', 'database', 'Database & Reporting Fit'],
        ];
    }

    private function assertSecurePageUrls(string $html): void
    {
        foreach (['', '/about', '/services', '/projects', '/contact'] as $path) {
            $this->assertStringContainsString('href="https://www.withandus.com'.$path.'"', $html);
        }

        $this->assertMatchesRegularExpression('/<script[^>]+src="https:\/\/www\.withandus\.com\/[^" ]+\.js\?[^" ]+"[^>]+data-update-uri=/', $html);
        $this->assertStringContainsString('data-update-uri="https://www.withandus.com/', $html);
        $this->assertStringContainsString('data-module-url="https://www.withandus.com/', $html);
        $this->assertStringNotContainsString('http://www.withandus.com', $html);
        $this->assertStringNotContainsString('untrusted.example', $html);
    }
}
