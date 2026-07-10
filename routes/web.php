<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ContactsManager;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/contacts', ContactsManager::class)->name('contacts.index');
});