{{-- Affiche le message d'erreur de validation sous le champ concerné (rouge) --}}
@error($field)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror
