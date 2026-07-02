@extends('layouts.public')

@section('title', 'Contact - ' . config('app.name', 'FITAB'))

@section('content')
{{-- ==================== HERO ==================== --}}
<section class="d-flex align-items-center bg-white" style="min-height: 220px;">
    <div class="container text-center">
        <h1 class="fw-bold display-6 mb-2" style="color: #9B4D07;">Contactez-nous</h1>
        <p class="mb-0" style="color: #5F2B0C; max-width: 500px; margin: 0 auto;">Une question, une suggestion ? Nous sommes à votre écoute.</p>
    </div>
</section>

{{-- ==================== CORPS ==================== --}}
<section class=" bg-white">
    <div class="container">
        <div class="row g-5" id="formulaire">
            {{-- COLONNE GAUCHE — FORMULAIRE --}}
            <div class="col-lg-7">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

<div class="card rounded-3 h-100" style="border: 1px solid #E3D5AD;">
    <div class="card-body p-4 p-md-5">
        <h4 class="fw-bold mb-1" style="color: #9B4D07;">Envoyez-nous un message</h4>
                        <hr class="my-3" style="width: 60px; height: 3px; border: none; background-color: #CA7B05; opacity: 1;">
                        <form action="{{ route('public.contact.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nom" class="form-label fw-medium small">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="nom" id="nom" class="form-control form-control-lg @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium small">Adresse e-mail <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="sujet" class="form-label fw-medium small">Objet <span class="text-danger">*</span></label>
                                <input type="text" name="sujet" id="sujet" class="form-control form-control-lg @error('sujet') is-invalid @enderror" value="{{ old('sujet') }}" required>
                                @error('sujet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label fw-medium small">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="5" class="form-control form-control-lg @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-lg w-100 fw-semibold py-3 border-0 btn-fitab">
                                <i class="bi bi-send me-2"></i>Envoyer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- COLONNE DROITE — INFOS --}}
            <div class="col-lg-5">
                <div class="card rounded-3 h-100" style="border: 1px solid #E3D5AD;">
                    <div class="card-body p-4 p-md-5 d-flex flex-column">
                        <h5 class="fw-bold mb-1" style="color: #9B4D07;">Informations</h5>
                        <hr class="my-3" style="width: 40px; height: 3px; border: none; background-color: #CA7B05; opacity: 1;">

                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-telephone-fill" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">Téléphone</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c;">+229 01 66 16 75 88</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-envelope-fill" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">E-mail</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c;">eyissobur@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-geo-alt-fill" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">Adresse</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c;">Porto-Novo, Bénin</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-semibold mb-3" style="color: #9B4D07;">Suivez-nous</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-facebook fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">Facebook</span>
                            </a>
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-instagram fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">Instagram</span>
                            </a>
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-youtube fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">YouTube</span>
                            </a>
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-tiktok fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">TikTok</span>
                            </a>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-semibold mb-3" style="color: #9B4D07;">Retrouvez-nous</h6>
                        <div class="rounded-3 overflow-hidden mb-3" style="height: 200px;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127006.36257960094!2d2.5858495490516753!3d6.496527703275542!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1024a6c6baf6f49f%3A0xe3c15c3a61dc0162!2sPorto-Novo%2C%20B%C3%A9nin!5e0!3m2!1sfr!2s!4v1689605683940!5m2!1sfr!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                        <a href="https://maps.google.com/?q=Porto-Novo+B%C3%A9nin" target="_blank" rel="noopener" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                            <i class="bi bi-box-arrow-up-right" style="color: #CA7B05;"></i>
                            <span>Ouvrir dans Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== APPEL SPONSORS ==================== --}}
<section class="py-5 text-center bg-white">
    <div class="container">
        <h3 class="fw-bold mb-2" style="color: #9B4D07;">Vous souhaitez soutenir le FITAB ?</h3>
        <p class="text-muted mb-4">Devenez partenaire ou sponsor du Festival International des Talents Artistiques du Bénin.</p>
        <a href="#formulaire" class="btn btn-lg fw-semibold px-5 py-3 border-0 btn-fitab">
            <i class="bi bi-envelope me-2"></i>Contactez-nous
        </a>
    </div>
</section>
@endsection