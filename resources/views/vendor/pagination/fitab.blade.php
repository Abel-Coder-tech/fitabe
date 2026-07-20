@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination mb-0">
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link border-0" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-left"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-left"></i></a>
                    </li>
                @endif
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0" href="{{ $paginator->nextPageUrl() }}" rel="next" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-right"></i></a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link border-0" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small mb-0" style="color: #5F2B0C;">
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    à
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    sur
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    résultats
                </p>
            </div>

            <div>
                <ul class="pagination mb-0">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link border-0" style="background: transparent; color: #CA7B05;" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link border-0" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-left"></i></a>
                        </li>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0" style="background: transparent; color: #9B4D07;">{{ $element }}</span></li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link fw-bold border-0 rounded-3" style="background: #9B4D07; color: #fff; min-width: 36px; text-align: center;">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-3" href="{{ $url }}" style="background: transparent; color: #5F2B0C; min-width: 36px; text-align: center;">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link border-0" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="background: transparent; color: #9B4D07;"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link border-0" style="background: transparent; color: #CA7B05;" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
