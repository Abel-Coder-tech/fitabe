@props(['disabled' => false])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary']) }} @disabled($disabled)>
    {{ $slot }}
</button>
