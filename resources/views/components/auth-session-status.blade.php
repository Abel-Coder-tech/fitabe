@props(['status'])

@if ($status)
    <div class="alert alert-success mb-4">
        {{ $status }}
    </div>
@endif
