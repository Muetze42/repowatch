<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View as ViewInterface;
use Livewire\Component;

class Home extends Component
{
    /**
     * Called when a component created.
     */
    public function mount(): void
    {
        //
    }

    /**
     * Called before updating a component property.
     */
    public function updating(string $property, mixed $value): void
    {
        //
    }

    /**
     * Called after updating a property.
     */
    public function updated(string $property, mixed $value): void
    {
        //
    }

    /**
     * Get the view / view contents that represent the component.
     */
    public function render(): ViewInterface
    {
        return view('livewire.home');
    }
}
