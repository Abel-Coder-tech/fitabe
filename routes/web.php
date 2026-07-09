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
use App\Http\Controllers\Admin\ResultatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;

require __DIR__.'/auth.php';

Route::get('/', [AcceuilController::class, 'index'])->name('home');
Route::get('/vote', [PublicVoteController::class, 'index'])->name('public.vote');
Route::post('/vote', [PublicVoteController::class, 'store'])->name('public.vote.store');
Route::get('/vote/merci', [PublicVoteController::class, 'merci'])->name('public.vote.merci');
Route::post('/vote/settings', [PublicVoteController::class, 'updateSettings'])->name('public.vote.settings');
Route::post('/webhook/kkiapay', [PublicVoteController::class, 'webhookKkiapay'])->name('public.vote.webhook.kkiapay');
Route::post('/webhook/fedapay', [PublicVoteController::class, 'webhookFedapay'])->name('public.vote.webhook.fedapay');
Route::get('/medias', [PublicMediaController::class, 'index'])->name('public.medias');
Route::get('/contact', [PublicContactController::class, 'index'])->name('public.contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('public.contact.store');
Route::view('/mentions-legales', 'public.mentions-legales')->name('public.mentions-legales');
Route::view('/confidentialite', 'public.confidentialite')->name('public.confidentialite');
Route::view('/cgu', 'public.cgu')->name('public.cgu');
Route::view('/reglement', 'public.reglement')->name('public.reglement');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('candidats', CandidatController::class);
    Route::resource('votes', VoteController::class)->except(['create', 'store', 'edit']);
    Route::post('votes/clear-all', [VoteController::class, 'clearAll'])->name('votes.clearAll');
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('partenaires', PartenaireController::class);
    Route::resource('parametres', ParametreController::class);
    Route::resource('medias', MediaController::class);
    Route::resource('contacts', ContactController::class)->except(['create', 'store', 'edit', 'update']);
    Route::resource('users', UserController::class);
    Route::prefix('resultats')->name('resultats.')->controller(ResultatController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{annee}', 'show')->name('show');
        Route::get('/{resultat}/edit', 'edit')->name('edit');
        Route::put('/{resultat}', 'update')->name('update');
        Route::post('/regenerer/{annee}', 'regenerer')->name('regenerer');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/export', [ProfileController::class, 'export'])->name('profile.export');
    Route::delete('/profile/session/{session}', [ProfileController::class, 'destroySession'])->name('profile.session.destroy');
    Route::post('/profile/sessions/revoke-others', [ProfileController::class, 'destroyOtherSessions'])->name('profile.sessions.revoke-others');
});