<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AcceuilController;
use App\Http\Controllers\Public\VoteController as PublicVoteController;
use App\Http\Controllers\Public\MediaController as PublicMediaController;
use App\Http\Controllers\Public\ContactController as PublicContactController;
use App\Http\Controllers\Admin\CandidatController;
use App\Http\Controllers\Admin\VoteController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [AcceuilController::class, 'index'])->name('home');
Route::get('/vote', [PublicVoteController::class, 'index'])->name('public.vote');
Route::post('/vote', [PublicVoteController::class, 'store'])->name('public.vote.store');
Route::get('/vote/merci', [PublicVoteController::class, 'merci'])->name('public.vote.merci');
Route::post('/vote/webhook/kkiapay', [PublicVoteController::class, 'webhookKkiapay'])->name('public.vote.webhook.kkiapay');
Route::post('/vote/webhook/fedapay', [PublicVoteController::class, 'webhookFedapay'])->name('public.vote.webhook.fedapay');
Route::get('/medias', [PublicMediaController::class, 'index'])->name('public.medias');
Route::get('/contact', [PublicContactController::class, 'index'])->name('public.contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('public.contact.store');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('candidats', CandidatController::class);
    Route::resource('votes', VoteController::class)->except(['create', 'store', 'edit']);
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('partenaires', PartenaireController::class);
    Route::resource('parametres', ParametreController::class);
    Route::resource('medias', MediaController::class);
    Route::resource('contacts', ContactController::class)->except(['create', 'store', 'edit', 'update']);
    Route::resource('users', UserController::class);
});

// Dashboard + Profile
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
