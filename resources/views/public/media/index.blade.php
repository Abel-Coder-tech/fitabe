@extends('layouts.public')

@section('title', 'Médiathèque - ' . config('app.name', 'FITAB'))

@section('content')
<style>
.media-card { transition: transform .3s ease, box-shadow .3s ease; }
.media-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important; }
.media-thumb { width: 100%; height: 100%; object-fit: cover; }
.media-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(24,61,106,0.5); opacity: 0; transition: opacity .3s ease; }
.media-card:hover .media-overlay { opacity: 1; }
.ratio-1x1 .media-overlay { background: rgba(24,61,106,0.4); }
</style>
{{-- ==================== HERO ==================== --}}
<section class="d-flex align-items-center bg-white" style="min-height: 220px;">
    <div class="container text-center py-4">
        <h1 class="fw-bold display-6 mb-2" style="color: #183D6A;">Médiathèque</h1>
        <p class="mb-0" style="color: #183D6A; max-width: 500px; margin: 0 auto;">Explorez les photos et vidéos du Festival International des Talents Artistiques du Bénin.</p>
    </div>
</section>

{{-- ==================== FILTRES ==================== --}}
<section class="py-3" style="background-color: #f8f8f8;">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
            <div class="btn-group btn-group-sm" role="group" id="filterType">
                <button type="button" class="btn btn-outline-secondary active" data-filter="all">Tous</button>
                <button type="button" class="btn btn-outline-secondary" data-filter="photo">Photos</button>
                <button type="button" class="btn btn-outline-secondary" data-filter="video">Vidéos</button>
            </div>
            @if ($annees->count() > 1)
            <select id="filterAnnee" class="form-select form-select-sm" style="width: auto;">
                <option value="all">Toutes éditions</option>
                @foreach ($annees as $a)
                <option value="{{ $a }}">{{ $a }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>
</section>

{{-- ==================== PHOTOS ==================== --}}
@if ($photos->count())
<section class="py-5 bg-white" id="section-photos">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0" style="color: #183D6A;">Galerie Photos</h3>
            <span class="small text-muted">{{ $photos->count() }} photo{{ $photos->count() > 1 ? 's' : '' }}</span>
        </div>
        <div class="row g-3" id="photosGrid">
            @foreach ($photos as $media)
            <div class="col-6 col-md-4 col-lg-3 media-item" data-type="photo" data-annee="{{ $media->annee }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden media-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#photoModal" data-index="{{ $loop->index }}">
                    <div class="ratio ratio-1x1">
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre ?? 'Photo' }}" class="media-thumb" loading="lazy">
                        <div class="media-overlay">
                            <i class="bi bi-search text-white fs-4"></i>
                        </div>
                    </div>
                    @if ($media->titre || $media->annee)
                    <div class="card-body py-2 px-3">
                        @if ($media->titre)
                        <small class="fw-medium d-block text-truncate" style="color: #183D6A;">{{ $media->titre }}</small>
                        @endif
                        <span class="badge fw-normal" style="background-color: #F3EACE; color: #183D6A; font-size: 0.65rem;">{{ $media->annee }}</span>
                    </div>
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
            <span class="bg-white px-4 fw-semibold small" style="color: #98732B;">Espace Vidéo</span>
        </div>
    </div>
</section>
@endif

{{-- ==================== VIDÉOS ==================== --}}
@if ($videos->count())
<section class="py-5 bg-white" id="section-videos">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold mb-0" style="color: #183D6A;">Espace Vidéo</h3>
            <span class="small text-muted">{{ $videos->count() }} vidéo{{ $videos->count() > 1 ? 's' : '' }}</span>
        </div>
        <div class="row g-3" id="videosGrid">
            @foreach ($videos as $media)
            <div class="col-md-6 col-lg-4 media-item" data-type="video" data-annee="{{ $media->annee }}">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden media-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#videoModal" data-index="{{ $loop->index }}">
                    <div class="ratio ratio-16x9" style="background-color: #000;">
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre ?? 'Vidéo' }}" class="media-thumb" loading="lazy">
                        <div class="media-overlay">
                            <i class="bi bi-play-circle-fill text-white" style="font-size: 3rem; text-shadow: 0 2px 8px rgba(0,0,0,0.5);"></i>
                        </div>
                    </div>
                    @if ($media->titre || $media->annee)
                    <div class="card-body py-2 px-3">
                        @if ($media->titre)
                        <small class="fw-medium d-block text-truncate" style="color: #183D6A;">{{ $media->titre }}</small>
                        @endif
                        <span class="badge fw-normal" style="background-color: #F3EACE; color: #183D6A; font-size: 0.65rem;">{{ $media->annee }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ==================== VIDE ==================== --}}
@if (!$photos->count() && !$videos->count())
<section class="py-5 bg-white">
    <div class="container text-center py-5">
        <i class="bi bi-images fs-1" style="color: #98732B;"></i>
        <p class="text-muted mt-3 mb-0">Aucun média publié pour le moment.</p>
    </div>
</section>
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
                <p id="lightboxTitre" class="text-white text-center small py-2 mb-0" style="background: rgba(0,0,0,0.6);"></p>
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
                <p id="videoTitre" class="text-white text-center small py-2 mb-0" style="background: rgba(0,0,0,0.6);"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    // === FILTRES ===
    const typeBtns = document.querySelectorAll('#filterType .btn');
    const anneeSelect = document.getElementById('filterAnnee');
    const items = document.querySelectorAll('.media-item');

    function filterItems() {
        const type = document.querySelector('#filterType .btn.active')?.dataset?.filter || 'all';
        const annee = anneeSelect?.value || 'all';
        items.forEach(el => {
            const matchType = type === 'all' || el.dataset.type === type;
            const matchAnnee = annee === 'all' || el.dataset.annee === annee;
            el.style.display = matchType && matchAnnee ? '' : 'none';
        });
    }

    typeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            typeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterItems();
        });
    });

    if (anneeSelect) {
        anneeSelect.addEventListener('change', filterItems);
    }

    // === LIGHTBOX PHOTOS ===
    const photos = @json($photos->map(fn($m) => [
        'url' => $m->thumbnail,
        'titre' => $m->titre,
    ]));
    const photoModal = document.getElementById('photoModal');
    const lightboxImg = document.getElementById('lightboxImage');
    const lightboxTitre = document.getElementById('lightboxTitre');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    let currentPhoto = 0;

    photoModal?.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        currentPhoto = parseInt(btn.dataset.index);
        showPhoto(currentPhoto);
    });

    function showPhoto(index) {
        if (!photos[index]) return;
        lightboxImg.src = photos[index].url;
        lightboxImg.alt = photos[index].titre || 'Photo';
        lightboxTitre.textContent = photos[index].titre || '';
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
    const videos = @json($videos->map(fn($m) => [
        'id' => $m->youtube_id,
        'titre' => $m->titre,
    ]));
    const videoModal = document.getElementById('videoModal');
    const videoIframe = document.getElementById('videoIframe');
    const videoTitre = document.getElementById('videoTitre');
    let currentVideo = 0;

    videoModal?.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        currentVideo = parseInt(btn.dataset.index);
        showVideo(currentVideo);
    });

    videoModal?.addEventListener('hidden.bs.modal', function() {
        videoIframe.src = '';
    });

    function showVideo(index) {
        if (!videos[index]) return;
        videoIframe.src = 'https://www.youtube.com/embed/' + videos[index].id + '?autoplay=1';
        videoTitre.textContent = videos[index].titre || '';
    }
})();
</script>
@endpush