<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FITA — Administration</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎤</text></svg>">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body style="background: #3E1E05;">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="card border-0 rounded-4 shadow-lg" style="max-width: 420px; width: 100%;">
            <div class="card-body px-5 py-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="FITA" height="50"
                         style="background: rgba(62,30,5,0.95); border-radius: 8px; padding: 12px;"
                         onerror="this.style.display='none'">
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
<style>
.password-toggle { position: relative; }
.password-toggle .form-control { padding-right: 40px; }
.password-toggle .bi {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #aaa; cursor: pointer; font-size: 15px; z-index: 5;
}
.password-toggle .bi:hover { color: #9B4D07; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="password"]').forEach(function(input) {
        if (input.closest('.password-toggle')) return;
        var wrapper = document.createElement('div');
        wrapper.className = 'password-toggle';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        var icon = document.createElement('i');
        icon.className = 'bi bi-eye-slash';
        icon.style.cssText = 'position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#aaa;cursor:pointer;font-size:15px;z-index:5;';
        icon.onmouseenter = function() { this.style.color = '#9B4D07'; };
        icon.onmouseleave = function() { this.style.color = '#aaa'; };
        icon.onclick = function() {
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.classList.toggle('bi-eye-slash', isPassword);
            this.classList.toggle('bi-eye', !isPassword);
        };
        wrapper.appendChild(icon);
    });
});
</script>
<script>
(function() {
    var messages = {
        valueMissing: function() { return 'Ce champ est obligatoire.'; },
        typeMismatch: function(el) {
            if (el.type === 'email') return 'Veuillez entrer une adresse email valide.';
            if (el.type === 'url') return 'Veuillez entrer une URL valide.';
            return 'Veuillez saisir une valeur valide.';
        },
        tooShort: function(el) { return 'Ce champ doit contenir au moins ' + el.minLength + ' caractères.'; },
        tooLong: function(el) { return 'Ce champ ne doit pas dépasser ' + el.maxLength + ' caractères.'; },
        rangeUnderflow: function(el) { return 'La valeur doit être supérieure ou égale à ' + el.min + '.'; },
        rangeOverflow: function(el) { return 'La valeur ne doit pas dépasser ' + el.max + '.'; },
        stepMismatch: function() { return 'Veuillez saisir une valeur valide pour ce champ.'; },
        patternMismatch: function() { return 'Le format saisi est invalide.'; },
        badInput: function() { return 'Veuillez saisir une valeur valide.'; },
        customError: function(el) { return el.validationMessage; }
    };
    document.addEventListener('invalid', function(e) {
        var el = e.target;
        if (!el || !el.willValidate) return;
        for (var key in messages) {
            if (el.validity[key]) {
                el.setCustomValidity(messages[key](el));
                break;
            }
        }
    }, true);
    document.addEventListener('input', function(e) {
        if (e.target.setCustomValidity) e.target.setCustomValidity('');
    }, true);
    document.addEventListener('change', function(e) {
        if (e.target.setCustomValidity) e.target.setCustomValidity('');
    }, true);
})();
</script>
</body>
</html>
