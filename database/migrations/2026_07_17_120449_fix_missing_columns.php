<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Votes : ajouter les colonnes manquantes utilisées par l'app
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'votant_nom')) {
                $table->string('votant_nom', 255)->nullable()->after('candidate_id');
            }
            if (!Schema::hasColumn('votes', 'votant_email')) {
                $table->string('votant_email', 255)->nullable()->after('votant_nom');
            }
            if (!Schema::hasColumn('votes', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('statut');
            }
            if (!Schema::hasColumn('votes', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('montant');
            }
            // Renommer telephone -> votant_telephone si besoin (ou on garde les deux)
        });

        // Partenaires : ajouter description
        Schema::table('partenaires', function (Blueprint $table) {
            if (!Schema::hasColumn('partenaires', 'description')) {
                $table->text('description')->nullable()->after('site_web');
            }
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['votant_nom', 'votant_email', 'ip_address', 'payment_method']);
        });

        Schema::table('partenaires', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }
};
