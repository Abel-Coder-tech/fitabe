<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Votes;
use App\Models\Contact;
use App\Models\Resultat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function candidats()
    {
        return $this->csv('candidats', ['ID', 'Nom', 'Nom de scène', 'Catégorie', 'N° scène', 'Votes', 'Note jury'],
            Candidats::all()->map(fn($c) => [$c->id, $c->nom, $c->nom_scene, $c->categorie, $c->numero_scene, $c->nombre_votes, $c->note_jury]));
    }

    public function votes()
    {
        return $this->csv('votes', ['ID', 'Candidat', 'Email votant', 'Montant', 'Statut', 'Date'],
            Votes::with('candidat')->where('statut', 'confirme')->get()->map(fn($v) => [
                $v->id, $v->candidat?->display_name, $v->email, $v->montant, $v->statut, $v->created_at->format('d/m/Y H:i'),
            ]));
    }

    public function contacts()
    {
        return $this->csv('contacts', ['ID', 'Nom', 'Email', 'Sujet', 'Date', 'Lu'],
            Contact::all()->map(fn($c) => [$c->id, $c->nom, $c->email, $c->sujet, $c->created_at->format('d/m/Y H:i'), $c->lu ? 'Oui' : 'Non']));
    }

    public function resultats()
    {
        return $this->csv('resultats', ['ID', 'Candidat', 'Catégorie', 'Prix', 'Score', 'Année', 'Publié'],
            Resultat::all()->map(fn($r) => [$r->id, $r->candidat_nom, $r->categorie, $r->prix_label, $r->score_final, $r->annee_edition, $r->publie ? 'Oui' : 'Non']));
    }

    private function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $stream = fopen('php://output', 'w');
            fprintf($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($stream, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($stream, $row, ';');
            }
            fclose($stream);
        }, "$filename.csv", ['Content-Type' => 'text/csv']);
    }
}
