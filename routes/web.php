<?php

use App\Livewire\Home;
use App\Livewire\RepositoryComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('repositories/{vendor}/{name}', RepositoryComponent::class);
