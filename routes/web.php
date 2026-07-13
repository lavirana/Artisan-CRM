<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ContactsManager;
use App\Livewire\CompaniesManager;
use App\Livewire\DealKanban;
use App\Livewire\TaskManager;
use App\Livewire\NoteActivityCenter;

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
    Route::get('/companies', CompaniesManager::class)->name('companies.index');
    Route::get('/pipeline', DealKanban::class)->name('deals.kanban');
    Route::get('/tasks', TaskManager::class)->name('tasks.index');
    Route::get('/activity-center', NoteActivityCenter::class)->name('notes.index');
});