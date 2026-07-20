@extends('layouts.public')

@section('title', 'Contact - ' . config('app.name', 'FITAB'))

@section('content')
{{-- ==================== HERO ==================== --}}
<section class="d-flex align-items-center justify-content-center"
         style="min-height: 220px; background: linear-gradient(135deg, rgba(62,30,5,0.88) 0%, rgba(62,30,5,0.65) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center; background-size: cover;">
    <div class="container text-center">
        <h1 class="fw-bold display-6 mb-2" style="color: #E3D5AD;">Contactez-nous</h1>
        <p class="mb-0" style="color: rgba(227,213,173,0.8); max-width: 500px; margin: 0 auto;">Une question, une suggestion ? Nous sommes à votre écoute.</p>
    </div>
</section>

{{-- ==================== CORPS ==================== --}}
<section class="py-5 bg-white">
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
                            <div class="mb-3" style="position: absolute; left: -9999px;" aria-hidden="true">
                                <label for="_hp_name">Ne pas remplir</label>
                                <input type="text" name="_hp_name" id="_hp_name" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">En soumettant ce formulaire, vous acceptez notre <a href="{{ route('public.confidentialite') }}" style="color: #9B4D07;">politique de confidentialité</a>.</small>
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
                        <h5 class="fw-bold mb-1" style="color: #9B4D07;">Coordonnées officielles</h5>
                        <hr class="my-3" style="width: 40px; height: 3px; border: none; background-color: #CA7B05; opacity: 1;">

                        <div class="mb-3">
                            <small class="text-muted">Organisation</small>
                            <p class="mb-0 fw-medium" style="color: #2c2c2c; font-size: 0.9rem;">STRATÈGE MEDIA EVENTS - FITAB</p>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-whatsapp" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">Téléphone / WhatsApp</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c;"><a href="https://wa.me/2290166167588" target="_blank" rel="noopener" style="color: inherit; text-decoration: none;">+229 01 66 16 75 88</a></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-envelope-fill" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">E-mail</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c;">strategemediaevents@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(202,123,5,0.12);">
                                <i class="bi bi-geo-alt-fill" style="color: #CA7B05;"></i>
                            </div>
                            <div>
                                <small class="text-muted">Adresse</small>
                                <p class="mb-0 fw-medium" style="color: #2c2c2c; font-size: 0.9rem;">Agbokou Centre Social M/EYISSE<br>Porto-Novo, Bénin</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-semibold mb-3" style="color: #9B4D07;">Suivez-nous</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="https://www.facebook.com/share/1WhHoPqx9H/" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-facebook fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">Facebook</span>
                            </a>
                            <a href="https://www.instagram.com/fitab_talents_artistiques_pn/" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-instagram fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">Instagram</span>
                            </a>
                            <a href="https://www.youtube.com/@TalentsArtistiques" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-youtube fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">YouTube</span>
                            </a>
                            <a href="https://www.tiktok.com/@fitab_talent_artistique" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-decoration-none" style="color: #9B4D07;">
                                <i class="bi bi-tiktok fs-5" style="color: #CA7B05;"></i>
                                <span class="small fw-medium">TikTok</span>
                            </a>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-semibold mb-3" style="color: #9B4D07;">Retrouvez-nous</h6>
                        <div class="rounded-3 overflow-hidden mb-3" style="height: 200px;">
                            <iframe src="https://www.google.com/maps?q=Agbokou+Centre+Social+Porto-Novo+B%C3%A9nin&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                        <a href="https://maps.google.com/?q=Agbokou+Centre+Social+M/EYISSE+Porto-Novo+B%C3%A9nin" target="_blank" rel="noopener" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                            <i class="bi bi-box-arrow-up-right" style="color: #CA7B05;"></i>
                            <span>Ouvrir dans Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection