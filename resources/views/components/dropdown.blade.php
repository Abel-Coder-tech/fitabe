@props(['align' => 'right', 'width' => '48'])

<div class="dropdown">
    {{ $trigger }}

    <div class="dropdown-menu {{ $align === 'right' ? 'dropdown-menu-end' : '' }} {{ $width === '48' ? '' : '' }}" style="{{ $width !== '48' ? 'min-width: ' . $width . 'px' : '' }}">
        {{ $content }}
    </div>
</div>
