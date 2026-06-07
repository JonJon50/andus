<?php

namespace App\Livewire;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $services;

    public $service_id = '';

    public $name = '';

    public $email = '';

    public $phone = '';

    public $company = '';

    public $message = '';

    public $submitted = false;

    public function mount(): void
    {
        if (App::environment('production')) {
            $this->services = collect([
                (object) [
                    'id' => 1,
                    'title' => 'Business Website Development',
                ],
                (object) [
                    'id' => 2,
                    'title' => 'Custom Web Applications',
                ],
                (object) [
                    'id' => 3,
                    'title' => 'Workflow Automation',
                ],
                (object) [
                    'id' => 4,
                    'title' => 'Database & Reporting Solutions',
                ],
            ]);

            return;
        }

        $this->services = Service::query()
            ->orderBy('sort_order')
            ->get();
    }

    protected function rules(): array
    {
        return [
            'service_id' => App::environment('production')
                ? ['nullable']
                : ['nullable', 'exists:services,id'],

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    protected function selectedService(): mixed
    {
        if (! $this->service_id) {
            return null;
        }

        return collect($this->services)
            ->firstWhere('id', (int) $this->service_id);
    }

    protected function servicePrompt(): array
    {
        $selectedService = $this->selectedService();

        if (! $selectedService) {
            return [
                'title' => 'Not sure where to start?',
                'description' => 'Select a service and we will help guide the next step.',
                'questions' => [
                    'What problem are you trying to solve?',
                    'What would a successful project look like?',
                ],
            ];
        }

        return match ($selectedService->title) {
            'Business Website Development' => [
                'title' => 'Website Project Fit',
                'description' => 'Great choice if you need a professional online presence that explains your business and helps customers reach you.',
                'questions' => [
                    'Do you need a new website or a redesign?',
                    'What pages do you need? Home, About, Services, Projects, Contact?',
                    'Do you already have a domain or hosting?',
                ],
            ],

            'Custom Web Applications' => [
                'title' => 'Custom Application Fit',
                'description' => 'Great choice if your business needs a portal, dashboard, internal tool, or database-driven workflow.',
                'questions' => [
                    'Who will use the application?',
                    'What information needs to be stored or managed?',
                    'What process are you trying to make easier?',
                ],
            ],

            'Workflow Automation' => [
                'title' => 'Automation Opportunity',
                'description' => 'Great choice if your team repeats the same task often and wants a smoother, faster process.',
                'questions' => [
                    'What repetitive task do you want to reduce?',
                    'Should the automation send emails, organize data, or trigger follow-ups?',
                    'What currently takes the most time in your workflow?',
                ],
            ],

            'Database & Reporting Solutions' => [
                'title' => 'Database & Reporting Fit',
                'description' => 'Great choice if your data is spread across spreadsheets, forms, or systems and you need better reporting.',
                'questions' => [
                    'Where is your data currently stored?',
                    'What reports or numbers do you need to see?',
                    'Do you need search, filters, dashboards, or exports?',
                ],
            ],

            default => [
                'title' => 'Project Fit',
                'description' => 'Tell us more about what you are trying to build.',
                'questions' => [
                    'What problem are you trying to solve?',
                    'What would make this project successful?',
                ],
            ],
        };
    }

    public function save(): void
    {
        $validated = $this->validate();

        $validated['service_id'] = $validated['service_id'] ?: null;
        $validated['status'] = 'new';

        $selectedService = $this->selectedService();

        try {
            $inquiry = ContactInquiry::create($validated);
        } catch (\Throwable $e) {
            Log::error('Contact inquiry database save failed.', [
                'error' => $e->getMessage(),
                'email' => $validated['email'],
            ]);

            $inquiry = (object) [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'message' => $validated['message'],
                'status' => $validated['status'],
                'service' => $selectedService,
                'created_at' => now(),
            ];
        }

        Mail::to(config('mail.contact_to'))
            ->send(new ContactInquirySubmitted($inquiry));

        $this->reset([
            'service_id',
            'name',
            'email',
            'phone',
            'company',
            'message',
        ]);

        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form', [
            'selectedService' => $this->selectedService(),
            'servicePrompt' => $this->servicePrompt(),
        ]);
    }
}