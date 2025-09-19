<?php

namespace App\Livewire;

use App\Models\Repository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Home extends Component
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection<array-key, \App\Models\Repository>
     */
    public Collection $repositories;

    /**
     * Called when a component created.
     */
    public function mount(): void
    {
        $this->repositories = Repository::orderBy('package_name')
            ->withCount('releases')
            ->withMax('releases', 'released_at')
            ->get()
            ->append(['vendor', 'name', 'latest_release_date']);
    }
}
