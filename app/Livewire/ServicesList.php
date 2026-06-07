<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Livewire\Component;

class ServicesList extends Component
{
    public function render(): View
    {
        return view('livewire.services-list', [
            'services' => $this->getServices(),
        ]);
    }

    protected function getServices(): Collection
    {
        if (App::environment('production')) {
            return collect([
                (object) [
                    'title' => 'Business Website Development',
                    'description' => 'Professional websites built to clearly present your services, build trust with customers, and support future business growth.',
                ],
                (object) [
                    'title' => 'Custom Web Applications',
                    'description' => 'Internal tools, dashboards, portals, and database-driven systems designed around the way your business actually works.',
                ],
                (object) [
                    'title' => 'Workflow Automation',
                    'description' => 'Automation solutions that reduce repetitive work, improve response time, and help your team stay organized.',
                ],
                (object) [
                    'title' => 'Database & Reporting Solutions',
                    'description' => 'Better ways to store, manage, clean, and report on business data so you can make smarter decisions.',
                ],
            ]);
        }

        return Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();
    }
}