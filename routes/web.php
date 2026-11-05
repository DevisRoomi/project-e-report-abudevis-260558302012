<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Admin;
use App\Livewire\Auth;
use App\Livewire\Warga;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware('guest')->group(function (): void {
    Route::livewire('/login', Auth\Login::class)->name('login');
    Route::livewire('/register', Auth\Register::class)->name('register');
});
Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function (): void {
    Route::livewire('/dashboard', Warga\Dashboard::class)->name('dashboard');
    Route::livewire('/laporan', Warga\Laporan\Index::class)->name('laporan.index');
    Route::livewire('/laporan/buat', Warga\Laporan\Create::class)->name('laporan.create');
    Route::livewire('/laporan/{laporan}', Warga\Laporan\Show::class)->name('laporan.show');
    Route::livewire('/laporan/{laporan}/edit', Warga\Laporan\Edit::class)->name('laporan.edit');
    Route::livewire('/profile', Warga\Profile::class)->name('profile');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::livewire('/dashboard', Admin\Dashboard::class)->name('dashboard');
    Route::livewire('/laporan', Admin\Laporan\Index::class)->name('laporan.index');
    Route::livewire('/laporan/{laporan}', Admin\Laporan\Show::class)->name('laporan.show');
});

// Windows PHP built-in server junction fallback route for storage assets
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    abort_unless(file_exists($filePath), 404);

    return response()->file($filePath);
})->where('path', '.*')->name('storage.local');