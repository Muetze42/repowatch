<?php

use App\Livewire\Home;
use App\Livewire\RepositoryComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class);
Route::get('repositories/{vendor}/{name}', RepositoryComponent::class);
