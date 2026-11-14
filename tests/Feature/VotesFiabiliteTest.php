<?php

namespace Tests\Feature;

use App\Models\Candidats;
use App\Models\Votes;
use App\Models\VoteLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VotesFiabiliteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.fedapay.secret_key' => 'sk_test_123',
            'services.fedapay.mode' => 'live',
        ]);
    }

    private function candidat(int $nombreVotes = 0): Candidats
    {
        $id = DB::table('candidates')->insertGetId([
            'nom' => 'Candidat test',
            'nom_scene' => null,
            'categorie' => 'danse',
            'numero_scene' => null,
            'photo' => 'candidats/test.jpg',
            'biographie' => null,
            'nombre_votes' => $nombreVotes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Candidats::findOrFail($id);
    }

    private function vote(Candidats $candidat, int $quantite = 5, int $montant = 500): Votes
    {
        return Votes::create([
            'candidate_id' => $candidat->id,
            'quantite' => $quantite,
            'montant' => $montant,
            'statut' => 'en_attente',
            'payment_method' => 'fedapay',
            'transaction_id' => 'pending_'.uniqid(),
        ]);
    }

    private function fausseTransaction(array $surcharges = []): array
    {
        return array_merge([
            'id' => 114126333,
            'status' => 'transferred',
            'amount' => 500,
            'customer' => ['phone_number' => '+22990000000'],
            'custom_metadata' => ['vote_id' => 0],
        ], $surcharges);
    }

    public function test_marquer_confirme_n_incremente_qu_une_seule_fois(): void
    {
        $candidat = $this->candidat();
        $vote = $this->vote($candidat);

        $this->assertTrue($vote->marquerConfirme('114126333', 'fedapay'));
        $this->assertFalse($vote->marquerConfirme('114126333', 'fedapay'));

        $this->assertSame('confirme', $vote->fresh()->statut);
        $this->assertSame(5, $candidat->fresh()->nombre_votes);
        $this->assertSame(0, VoteLog::count());
    }

    public function test_un_vote_rejete_ne_peut_pas_etre_confirme(): void
    {
        $candidat = $this->candidat();
        $vote = $this->vote($candidat);

        $this->assertTrue($vote->marquerRejete('114000000'));
        $this->assertFalse($vote->marquerConfirme('114126333', 'fedapay'));

        $this->assertSame('rejete', $vote->fresh()->statut);
        $this->assertSame(0, $candidat->fresh()->nombre_votes);
    }

    public function test_merci_confirme_apres_verification_api_transferred(): void
    {
        $candidat = $this->candidat();
        $vote = $this->vote($candidat);

        Http::fake([
            'https://api.fedapay.com/v1/transactions/114126333' => Http::response(
                $this->fausseTransaction([
                    'custom_metadata' => ['vote_id' => $vote->id],
                ])
            ),
        ]);

        // L'URL annonce « approved » : seule la réponse API fait foi.
        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame('confirme', $vote->fresh()->statut);
        $this->assertSame(5, $candidat->fresh()->nombre_votes);

        // Rejouer le retour ne doit pas doubler le compteur.
        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame(5, $candidat->fresh()->nombre_votes);
        $this->assertSame(1, VoteLog::where('categorie', 'confirme_callback')->count());
    }

    public function test_merci_ne_confirme_pas_si_l_api_dit_pending(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/114126333' => Http::response(
                $this->fausseTransaction([
                    'status' => 'pending',
                    'custom_metadata' => ['vote_id' => $vote->id],
                ])
            ),
        ]);

        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame('en_attente', $vote->fresh()->statut);
        $this->assertSame(0, $vote->candidat->fresh()->nombre_votes);
    }

    public function test_merci_ne_confirme_pas_si_le_montant_differe(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/114126333' => Http::response(
                $this->fausseTransaction([
                    'amount' => 100,
                    'custom_metadata' => ['vote_id' => $vote->id],
                ])
            ),
        ]);

        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame('en_attente', $vote->fresh()->statut);
        $this->assertSame(1, VoteLog::where('categorie', 'montant_incoherent')->count());
    }

    public function test_merci_refuse_une_transaction_rattachee_a_un_autre_vote(): void
    {
        $candidat = $this->candidat();
        $vote = $this->vote($candidat);
        $autreVote = $this->vote($candidat);

        Http::fake([
            'https://api.fedapay.com/v1/transactions/114126333' => Http::response(
                $this->fausseTransaction([
                    'custom_metadata' => ['vote_id' => $autreVote->id],
                ])
            ),
        ]);

        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame('en_attente', $vote->fresh()->statut);
        $this->assertSame(0, $candidat->fresh()->nombre_votes);
        $this->assertSame(1, VoteLog::where('categorie', 'vote_incoherent')->count());
    }

    public function test_merci_ne_confirme_pas_si_l_api_est_indisponible(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/*' => Http::response(['message' => 'down'], 503),
        ]);

        $this->get('/vote/merci?vote_id='.$vote->id.'&id=114126333&status=approved')
            ->assertOk();

        $this->assertSame('en_attente', $vote->fresh()->statut);
        $this->assertSame(1, VoteLog::where('categorie', 'verification_api_impossible')->count());
    }

    public function test_le_cron_confirme_un_paiement_transferred_du_bon_montant(): void
    {
        $candidat = $this->candidat();
        $vote = $this->vote($candidat);

        Http::fake([
            'https://api.fedapay.com/v1/transactions/search*' => Http::response([
                'v1/transactions' => [
                    $this->fausseTransaction([
                        'id' => 114126333,
                        'custom_metadata' => ['vote_id' => $vote->id],
                    ]),
                ],
                'meta' => ['next_page' => null, 'total_pages' => 1],
            ]),
        ]);

        $this->artisan('votes:verifier-fedapay --pages=1')->assertExitCode(0);

        $this->assertSame('confirme', $vote->fresh()->statut);
        $this->assertSame(5, $candidat->fresh()->nombre_votes);
    }

    public function test_le_cron_ne_confirme_pas_un_montant_incoherent(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/search*' => Http::response([
                'v1/transactions' => [
                    $this->fausseTransaction([
                        'amount' => 100,
                        'custom_metadata' => ['vote_id' => $vote->id],
                    ]),
                ],
                'meta' => ['next_page' => null, 'total_pages' => 1],
            ]),
        ]);

        $this->artisan('votes:verifier-fedapay --pages=1')->assertExitCode(0);

        $this->assertSame('en_attente', $vote->fresh()->statut);
        $this->assertSame(1, VoteLog::where('categorie', 'montant_incoherent')->count());
    }

    public function test_le_cron_sans_rejet_ne_rejette_jamais_un_echec(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/search*' => Http::response([
                'v1/transactions' => [
                    $this->fausseTransaction([
                        'status' => 'canceled',
                        'custom_metadata' => ['vote_id' => $vote->id],
                    ]),
                ],
                'meta' => ['next_page' => null, 'total_pages' => 1],
            ]),
        ]);

        $this->artisan('votes:verifier-fedapay --pages=1 --sans-rejet')->assertExitCode(0);

        $this->assertSame('en_attente', $vote->fresh()->statut);
    }

    public function test_le_cron_sans_option_rejette_un_echec_avec_la_decision_manuelle(): void
    {
        $vote = $this->vote($this->candidat());

        Http::fake([
            'https://api.fedapay.com/v1/transactions/search*' => Http::response([
                'v1/transactions' => [
                    $this->fausseTransaction([
                        'status' => 'canceled',
                        'custom_metadata' => ['vote_id' => $vote->id],
                    ]),
                ],
                'meta' => ['next_page' => null, 'total_pages' => 1],
            ]),
        ]);

        $this->artisan('votes:verifier-fedapay --pages=1')->assertExitCode(0);

        $this->assertSame('rejete', $vote->fresh()->statut);
    }

    public function test_l_empreinte_detecte_toute_modification_des_votes_confirmes(): void
    {
        $chemin = sys_get_temp_dir().'/fitabe-etat-'.uniqid().'.json';

        $candidat = $this->candidat();
        $vote = $this->vote($candidat);
        $vote->marquerConfirme('114126333', 'fedapay');

        $this->artisan("votes:etat --enregistrer={$chemin}")->assertExitCode(0);
        $this->artisan("votes:etat --comparer={$chemin}")->assertExitCode(0);

        DB::table('votes')->where('id', $vote->id)->update(['quantite' => 6]);

        $this->artisan("votes:etat --comparer={$chemin}")->assertExitCode(1);

        @unlink($chemin);
    }

    public function test_l_audit_detecte_et_corrige_les_compteurs_derives(): void
    {
        $candidat = $this->candidat(100);
        $this->vote($candidat)->marquerConfirme('114126333', 'fedapay');

        $this->artisan('votes:audit --heures=24')->assertExitCode(1);

        $this->assertSame(105, $candidat->fresh()->nombre_votes);

        $this->artisan('votes:audit --heures=24 --corriger --force')->assertExitCode(0);

        $this->assertSame(5, $candidat->fresh()->nombre_votes);
    }
}
