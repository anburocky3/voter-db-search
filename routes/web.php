<?php

use App\Http\Controllers\VoterOverviewController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/voter-overview', [VoterOverviewController::class, 'index'])
        ->name('dashboard.voter-overview');

    Route::get('voter-search', App\Livewire\VoterSearch::class)->name('voter-search');


    // Route::get('json-import', [JsonImportController::class, 'index'])->name('json-import.index');
    // Route::post('json-import', [JsonImportController::class, 'import'])->name('json-import.process');
    // Route::get('json-import/status', [JsonImportController::class, 'status'])->name('json-import.status');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
