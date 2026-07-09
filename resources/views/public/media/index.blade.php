@extends('layouts.public')

@section('title', 'Médiathèque - ' . config('app.name', 'FITAB'))

@section('content')
<style>
.media-card { transition: transform .3s ease, box-shadow .3s ease; }
.media-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important; }
.media-thumb { width: 100%; height: 100%; object-fit: cover; }
.media-card { transition: transform .3s ease, box-shadow .3s ease; }
.media-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important; }
.media-thumb { width: 100%; height: 100%; object-fit: cover; }
.media-overlay { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; background: rgba(62,30,5,0.6); opacity: 0; transition: opacity .3s ease; }
.media-card:hover .media-overlay { opacity: 1; }
.media-overlay-title { color: #fff; font-size: 0.8rem; font-weight: 500; text-align: center; padding: 0 8px; max-width: 100%; }
.btn-fitab-outline.active { background: var(--fitab-orange-light); color: #fff; }
.lire-plus-btn { font-size: 0.75rem; border-radius: 50px; padding: 0.15rem 0.8rem; border: 1px solid #9B4D07; color: #9B4D07; background: transparent; cursor: pointer; transition: all .2s; }
.lire-plus-btn:hover { background: #9B4D07; color: #fff; }
</style>

{{-- ==================== HERO ==================== --}}
<section class="d-flex align-items-center justify-content-center" style="min-height: 220px; background: linear-gradient(135deg, #3E1E05 0%, #9B4D07 50%, #3E1E05 100%);">
    <div class="container text-center">
        <h1 class="fw-bold display-6 mb-2" style="color: #E3D5AD;">Médiathèque</h1>
        <p class="mb-0" style="color: rgba(227,213,173,0.8); max-width: 500px; margin: 0 auto;">Explorez les photos et vidéos du Festival International des Talents Artistiques du Bénin.</p>
    </div>
</section>

{{-- ==================== FILTRES ==================== --}}
<section class="py-3 section-light">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
            <div class="btn-group btn-group-sm" role="group" id="filterType">
                <button type="button" class="btn btn-fitab-outline btn-sm active" data-filter="all">Tous</button>
                <button type="button" class="btn btn-fitab-outline btn-sm" data-filter="photo">Photos</button>
                <button type="button" class="btn btn-fitab-outline btn-sm" data-filter="video">Vidéos</button>
            </div>
        </div>
    </div>
</section>

{{-- ==================== PHOTOS ==================== --}}
@if ($photos->count())
<section class="py-5 bg-white" id="section-photos">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0" style="color: #9B4D07;">Galerie Photos</h3>
            <div class="d-flex align-items-center gap-2">
                @if ($annees->count() > 1)
                <select class="form-select form-select-sm filter-annee-photos" style="width: auto;" onchange="filtrerPhotos()">
                    <option value="all">Toutes éditions</option>
                    @foreach ($annees as $a)
                    <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
                @endif
                <span class="small text-muted">{{ $photos->count() }} photo{{ $photos->count() > 1 ? 's' : '' }}</span>
            </div>
        </div>
        <div class="row g-3" id="photosGrid">
            @foreach ($photos as $media)
            <div class="col-6 col-md-4 col-lg-3 media-item media-photo" data-type="photo" data-annee="{{ $media->annee_edition ?? '' }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden media-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#photoModal" data-index="{{ $loop->index }}">
                    <div class="ratio ratio-1x1">
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre ?? 'Photo' }}" class="media-thumb" loading="lazy">
                        <div class="media-overlay">
                            <i class="bi bi-search text-white fs-4"></i>
                            @if ($media->titre)
                            <span class="media-overlay-title">{{ $media->titre }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($media->description)
                    <button class="lire-plus-btn position-absolute bottom-0 end-0 m-2" onclick="event.stopPropagation(); ouvrirLirePlus({{ $loop->index }}, 'photos')">Lire plus</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ==================== SÉPARATEUR ==================== --}}
@if ($photos->count() && $videos->count())
<section class="bg-white">
    <div class="container">
        <hr class="m-0">
        <div class="text-center position-relative" style="margin-top: -12px;">
            <span class="bg-white px-4 fw-semibold small" style="color: #CA7B05;">Espace Vidéo</span>
        </div>
    </div>
</section>
@endif

{{-- ==================== VIDÉOS ==================== --}}
@if ($videos->count())
<section class="py-5 bg-white" id="section-videos">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0" style="color: #9B4D07;">Espace Vidéo</h3>
            <div class="d-flex align-items-center gap-2">
                @if ($annees->count() > 1)
                <select class="form-select form-select-sm filter-annee-videos" style="width: auto;" onchange="filtrerVideos()">
                    <option value="all">Toutes éditions</option>
                    @foreach ($annees as $a)
                    <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
                @endif
                <span class="small text-muted">{{ $videos->count() }} vidéo{{ $videos->count() > 1 ? 's' : '' }}</span>
            </div>
        </div>
        <div class="row g-3" id="videosGrid">
            @foreach ($videos as $media)
            <div class="col-md-6 col-lg-4 media-item media-video" data-type="video" data-annee="{{ $media->annee_edition }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden media-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#videoModal" data-index="{{ $loop->index }}">
                    <div class="ratio ratio-16x9" style="background-color: #000;">
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre ?? 'Vidéo' }}" class="media-thumb" loading="lazy">
                        <div class="media-overlay">
                            <i class="bi bi-play-circle-fill text-white" style="font-size: 3rem; text-shadow: 0 2px 8px rgba(0,0,0,0.5);"></i>
                            @if ($media->titre)
                            <span class="media-overlay-title">{{ $media->titre }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($media->description)
                    <button class="lire-plus-btn position-absolute bottom-0 end-0 m-2" onclick="event.stopPropagation(); ouvrirLirePlus({{ $loop->index }}, 'videos')">Lire plus</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ==================== VIDE ==================== --}}
@if (!$photos->count() && !$videos->count() && !$editions->count())
<section class="py-5 bg-white">
    <div class="container text-center py-5">
        <i class="bi bi-images fs-1" style="color: #CA7B05;"></i>
        <p class="text-muted mt-3 mb-0">Aucun média publié pour le moment.</p>
    </div>
</section>
@endif

{{-- ==================== SÉPARATEUR RÉSULTATS ==================== --}}
@if ($editions->count())
<section class="bg-white">
    <div class="container">
        <hr class="m-0">
        <div class="text-center position-relative" style="margin-top: -12px;">
            <span class="bg-white px-4 fw-semibold small" style="color: #CA7B05;">Résultats</span>
        </div>
    </div>
</section>
@endif

{{-- ==================== RÉSULTATS ==================== --}}
@if ($editions->count())
<section class="py-5 bg-white" id="section-resultats">
    <div class="container">
        <h3 class="fw-bold mb-4" style="color: #9B4D07;">Résultats par édition</h3>
        <div class="row g-3">
            @foreach ($editions as $annee)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden edition-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#resultatsModal" data-annee="{{ $annee }}">
                    <div class="text-center py-4" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                        <i class="bi bi-trophy-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="card-body text-center py-3">
                        <h5 class="fw-bold mb-0" style="color: #3E1E05;">Édition {{ $annee }}</h5>
                        <small class="text-muted">Voir les résultats</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== MODAL RÉSULTATS ==================== --}}
<div class="modal fade" id="resultatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="px-4 py-3 position-relative" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                <h5 class="fw-bold text-white mb-0" id="resultatsModalTitle">Résultats</h5>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="resultatsModalBody">
            </div>
        </div>
    </div>
</div>
@endif

{{-- ==================== PAGINATION ==================== --}}
@if ($medias->hasPages())
<section class="py-4 bg-white">
    <div class="container">
        <div class="d-flex justify-content-center">
            {{ $medias->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>
@endif

{{-- ==================== MODAL PHOTO (LIGHTBOX) ==================== --}}
<div class="modal fade" id="photoModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-black border-0 rounded-3 overflow-hidden">
            <div class="modal-header border-0 p-2">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0 position-relative">
                <img id="lightboxImage" src="" alt="" class="img-fluid" style="max-height: 85vh; margin: 0 auto;">
                <div id="lightboxDesc" class="text-white text-start small py-2 px-3" style="background: rgba(0,0,0,0.6); display: none;"></div>
                <button id="lightboxPrev" class="btn position-absolute top-50 start-0 translate-middle-y ms-2 rounded-circle" style="background: rgba(255,255,255,0.15); color: #fff; width: 44px; height: 44px;"><i class="bi bi-chevron-left fs-5"></i></button>
                <button id="lightboxNext" class="btn position-absolute top-50 end-0 translate-middle-y me-2 rounded-circle" style="background: rgba(255,255,255,0.15); color: #fff; width: 44px; height: 44px;"><i class="bi bi-chevron-right fs-5"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL VIDÉO ==================== --}}
<div class="modal fade" id="videoModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-black border-0 rounded-3 overflow-hidden">
            <div class="modal-header border-0 p-2">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="videoIframe" src="" allowfullscreen style="border:0;"></iframe>
                </div>
                <div id="videoDesc" class="text-white text-start small py-2 px-3" style="background: rgba(0,0,0,0.6); display: none;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL LIRE PLUS (DESCRIPTION) ==================== --}}
<div class="modal fade" id="lirePlusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="px-4 pt-4 pb-2 position-relative" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                <h5 class="fw-bold text-white mb-0" id="lirePlusTitre">Description</h5>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p id="lirePlusContenu" class="mb-0" style="color: #3E1E05; line-height: 1.7; font-size: 0.95rem;"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    // === DONNÉES ===
    const photos = @json($photosJson);
    const videos = @json($videosJson);
    const editions = @json($editionsJson);

    // === LIRE PLUS ===
    window.ouvrirLirePlus = function(index, type) {
        const data = type === 'photos' ? photos : videos;
        const item = data[index];
        if (!item || !item.description) return;
        document.getElementById('lirePlusTitre').textContent = item.titre || 'Description';
        document.getElementById('lirePlusContenu').textContent = item.description;
        new bootstrap.Modal('#lirePlusModal').show();
    };

    // === FILTRES PAR TYPE (global) ===
    const typeBtns = document.querySelectorAll('#filterType .btn');
    const sections = {
        photo: document.getElementById('section-photos'),
        video: document.getElementById('section-videos'),
    };

    typeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            typeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const type = this.dataset.filter;
            if (sections.photo) sections.photo.style.display = (type === 'all' || type === 'photo') ? '' : 'none';
            if (sections.video) sections.video.style.display = (type === 'all' || type === 'video') ? '' : 'none';
        });
    });

    // === FILTRES PAR ANNÉE (par section) ===
    window.filtrerPhotos = function() {
        const annee = document.querySelector('.filter-annee-photos')?.value || 'all';
        document.querySelectorAll('.media-photo').forEach(el => {
            el.style.display = annee === 'all' || el.dataset.annee === annee ? '' : 'none';
        });
    };

    window.filtrerVideos = function() {
        const annee = document.querySelector('.filter-annee-videos')?.value || 'all';
        document.querySelectorAll('.media-video').forEach(el => {
            el.style.display = annee === 'all' || el.dataset.annee === annee ? '' : 'none';
        });
    };

    // === LIGHTBOX PHOTOS ===
    const photoModal = document.getElementById('photoModal');
    const lightboxImg = document.getElementById('lightboxImage');
    const lightboxDesc = document.getElementById('lightboxDesc');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    let currentPhoto = 0;

    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            currentPhoto = parseInt(btn.dataset.index);
            showPhoto(currentPhoto);
        });
        photoModal.addEventListener('hidden.bs.modal', function() {
            lightboxDesc.style.display = 'none';
        });
    }

    function showPhoto(index) {
        if (!photos[index]) return;
        lightboxImg.src = photos[index].url;
        lightboxImg.alt = photos[index].titre || 'Photo';
        if (photos[index].description) {
            lightboxDesc.textContent = photos[index].description;
            lightboxDesc.style.display = '';
        } else {
            lightboxDesc.style.display = 'none';
        }
        prevBtn.style.display = index > 0 ? '' : 'none';
        nextBtn.style.display = index < photos.length - 1 ? '' : 'none';
    }

    prevBtn?.addEventListener('click', function() {
        if (currentPhoto > 0) showPhoto(--currentPhoto);
    });
    nextBtn?.addEventListener('click', function() {
        if (currentPhoto < photos.length - 1) showPhoto(++currentPhoto);
    });

    // === MODAL VIDÉOS ===
    const videoModal = document.getElementById('videoModal');
    const videoIframe = document.getElementById('videoIframe');
    const videoDesc = document.getElementById('videoDesc');
    let currentVideo = 0;

    if (videoModal) {
        videoModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            currentVideo = parseInt(btn.dataset.index);
            showVideo(currentVideo);
        });
        videoModal.addEventListener('hidden.bs.modal', function() {
            videoIframe.src = '';
            videoDesc.style.display = 'none';
        });
    }

    function showVideo(index) {
        if (!videos[index]) return;
        videoIframe.src = 'https://www.youtube.com/embed/' + videos[index].id + '?autoplay=1';
        if (videos[index].description) {
            videoDesc.textContent = videos[index].description;
            videoDesc.style.display = '';
        } else {
            videoDesc.style.display = 'none';
        }
    }

    // === RÉSULTATS ===
    const resultatsModal = document.getElementById('resultatsModal');
    const resultatsTitle = document.getElementById('resultatsModalTitle');
    const resultatsBody = document.getElementById('resultatsModalBody');

    if (resultatsModal) {
        resultatsModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const annee = btn.dataset.annee;
            const edition = editions.find(e => e.annee === annee);
            if (!edition) return;
            resultatsTitle.textContent = "Résultats - Édition " + annee;
            let html = '';
            edition.categories.forEach(cat => {
                html += '<div class="mb-4">';
                html += '<h6 class="fw-bold mb-2" style="color: #3E1E05;"><i class="bi bi-tag-fill me-1" style="color: #9B4D07;"></i>' + cat.categorie + '</h6>';
                html += '<div class="list-group list-group-flush">';
                cat.resultats.forEach(r => {
                    const medal = r.prix === 1 ? '#FFD700' : (r.prix === 2 ? '#C0C0C0' : '#CD7F32');
                    html += '<div class="list-group-item d-flex align-items-center gap-3 px-0">';
                    html += '<span class="badge fs-6 px-2 py-1" style="background: ' + medal + '; color: #3E1E05; min-width: 48px;">' + r.prix_label + '</span>';
                    if (r.candidat_photo) {
                        html += '<img src="' + r.candidat_photo + '" alt="" width="44" height="44" class="rounded-circle" style="object-fit: cover;">';
                    }
                    html += '<div class="flex-grow-1">';
                    html += '<strong style="color: #3E1E05;">' + r.candidat_nom + '</strong>';
                    html += '<small class="d-block text-muted">' + r.nombre_votes + ' ovations' + (r.score_final ? ' · Score : ' + r.score_final : '') + '</small>';
                    html += '</div></div>';
                });
                html += '</div></div>';
            });
            resultatsBody.innerHTML = html;
        });
    }
})();
</script>
@endpush
