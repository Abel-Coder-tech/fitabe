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

Route::get('/', [AcceuilController::class, 'index'])->name('home');
Route::get('/vote', [PublicVoteController::class, 'index'])->name('public.vote');
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