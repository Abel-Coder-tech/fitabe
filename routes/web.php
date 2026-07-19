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

use App\Http\Controllers\SitemapController;

require __DIR__.'/auth.php';

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', [AcceuilController::class, 'index'])->name('home');
Route::get('/vote', [PublicVoteController::class, 'index'])->name('public.vote');
Route::post('/vote', [PublicVoteController::class, 'store'])->name('public.vote.store')->middleware('throttle:10,1');
Route::get('/vote/merci', [PublicVoteController::class, 'merci'])->name('public.vote.merci');
Route::post('/vote/settings', [PublicVoteController::class, 'updateSettings'])->name('public.vote.settings')->middleware(['auth', 'role:super_admin', 'throttle:30,1']);
Route::post('/webhook/fedapay', [PublicVoteController::class, 'webhookFedapay'])->name('public.vote.webhook.fedapay');
Route::get('/medias', [PublicMediaController::class, 'index'])->name('public.medias');
Route::get('/resultats', [ResultatController::class, 'publicIndex'])->name('public.resultats');
Route::get('/contact', [PublicContactController::class, 'index'])->name('public.contact');
Route::post('/contact', [PublicContactController::class, 'store'])->name('public.contact.store')->middleware('throttle:5,1');
Route::view('/mentions-legales', 'public.mentions-legales')->name('public.mentions-legales');
Route::view('/confidentialite', 'public.confidentialite')->name('public.confidentialite');
Route::view('/cgu', 'public.cgu')->name('public.cgu');
Route::view('/reglement', 'public.reglement')->name('public.reglement');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard — accessible à tous les rôles authentifiés
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Contenu éditorial — accessible à tous les rôles
    Route::resource('candidats', CandidatController::class);
    Route::post('candidats/note-jury', [CandidatController::class, 'updateNoteJury'])->name('candidats.note-jury');
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('partenaires', PartenaireController::class);
    Route::resource('medias', MediaController::class);
    Route::resource('contacts', ContactController::class)->except(['create', 'store', 'edit', 'update']);

    // Votes — réservé super_admin
    Route::get('votes', [VoteController::class, 'index'])->name('votes.index')->middleware('role:super_admin');
    Route::get('votes/{vote}', [VoteController::class, 'show'])->name('votes.show')->middleware('role:super_admin');
    Route::delete('votes/{vote}', [VoteController::class, 'destroy'])->name('votes.destroy')->middleware('role:super_admin');
    Route::post('votes/clear-all', [VoteController::class, 'clearAll'])->name('votes.clearAll')->middleware('role:super_admin');
    Route::post('votes/toggle', [VoteController::class, 'toggle'])->name('votes.toggle')->middleware('role:super_admin');

    // Résultats — consultation pour tous, édition réservée super_admin
    Route::prefix('resultats')->name('resultats.')->controller(ResultatController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{annee}', 'show')->name('show');
        Route::get('/{resultat}/edit', 'edit')->name('edit')->middleware('role:super_admin');
        Route::put('/{resultat}', 'update')->name('update')->middleware('role:super_admin');
        Route::post('/regenerer/{annee}', 'regenerer')->name('regenerer')->middleware('role:super_admin');
        Route::post('/{annee}/publier-tout', 'togglePublishEdition')->name('publier')->middleware('role:super_admin');
    });

    // Administration sensible — réservé super_admin
    Route::resource('parametres', ParametreController::class)->middleware('role:super_admin');
    Route::resource('users', UserController::class)->middleware('role:super_admin');
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