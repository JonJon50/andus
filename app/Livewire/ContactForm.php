<?php

namespace App\Livewire;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

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

    $this->services = Service::orderBy('sort_order')->get();
}

    protected function rules(): array
    {
        return [
            'service_id' => ['nullable', 'exists:services,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    public function save(): void
{
    $validated = $this->validate();

    $validated['service_id'] = $validated['service_id'] ?: null;
    $validated['status'] = 'new';

    try {
        // Try saving to the database
        $inquiry = ContactInquiry::create($validated);
    } catch (\Exception $e) {
        // If the database fails (e.g., no DB in production), log it or handle as needed, but continue with email.
        // Log error if you want: Log::error($e->getMessage());
        $inquiry = (object) $validated; // Simulate an inquiry object for the email
    }

    // Always send the email, whether the DB save worked or not
    Mail::to(config('mail.contact_to'))
        ->send(new ContactInquirySubmitted($inquiry));

    // Reset the form inputs
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
}