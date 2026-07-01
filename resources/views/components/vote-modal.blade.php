<div class="modal fade" id="voteModal{{ $candidat->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Voter pour {{ $candidat->display_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="votant_nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="votant_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" name="votant_telephone" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Confirmer le vote</button>
            </div>
        </form>
    </div>
</div>
