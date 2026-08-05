<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Votes : les requêtes les plus fréquentes filtrent par statut + candidat et trient par création
        Schema::table('votes', function (Blueprint $table) {
            $table->index(['candidate_id', 'statut'], 'votes_candidat_statut_idx');
            $table->index(['statut', 'created_at'], 'votes_statut_created_idx');
        });

        // Candidats : filtre et regroupement par catégorie
        Schema::table('candidates', function (Blueprint $table) {
            $table->index('categorie', 'candidates_categorie_idx');
        });

        // Contacts : compteur de messages non lus
        Schema::table('contacts', function (Blueprint $table) {
            $table->index('lu', 'contacts_lu_idx');
        });

        // Vote logs : tri par date d'écriture
        Schema::table('vote_logs', function (Blueprint $table) {
            $table->index('created_at', 'vote_logs_created_at_idx');
        });

        // Médias : filtrage par type puis date
        Schema::table('medias', function (Blueprint $table) {
            $table->index(['type', 'created_at'], 'medias_type_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('votes_candidat_statut_idx');
            $table->dropIndex('votes_statut_created_idx');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex('candidates_categorie_idx');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex('contacts_lu_idx');
        });

        Schema::table('vote_logs', function (Blueprint $table) {
            $table->dropIndex('vote_logs_created_at_idx');
        });

        Schema::table('medias', function (Blueprint $table) {
            $table->dropIndex('medias_type_created_idx');
        });
    }
};
