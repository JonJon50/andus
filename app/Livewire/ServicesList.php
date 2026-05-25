<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\View\View;
use Livewire\Component;

class ServicesList extends Component
{
    public function render(): View
    {
        return view('livewire.services-list', [
            'services' => Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}