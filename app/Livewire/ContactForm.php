<?php

namespace App\Livewire;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\Service;
use Illuminate\Contracts\View\View;
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

        $inquiry = ContactInquiry::create($validated);

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
        return view('livewire.contact-form');
    }
}