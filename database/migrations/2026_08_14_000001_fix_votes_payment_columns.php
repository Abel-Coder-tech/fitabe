<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            if (! Schema::hasColumn('votes', 'telephone')) {
                $table->string('telephone', 50)->nullable()->after('email');
            }
            if (! Schema::hasColumn('votes', 'moyen_paiement')) {
                $table->string('moyen_paiement', 50)->nullable();
            }
            if (! Schema::hasColumn('votes', 'webhook_recu_le')) {
                $table->timestamp('webhook_recu_le')->nullable();
            }
        });

        // La colonne pouvait être un ENUM limité (mtn, moov, carte) : on l'élargit
        // pour accepter tous les modes de paiement renvoyés par FedaPay (om, cm, carte, …).
        if (Schema::hasColumn('votes', 'moyen_paiement')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE votes MODIFY moyen_paiement VARCHAR(50) NULL');
            } else {
                Schema::table('votes', fn (Blueprint $table) => $table->string('moyen_paiement', 50)->nullable()->change());
            }
        }

        // Recopie héritée : ancienne colonne votant_telephone -> telephone
        if (Schema::hasColumn('votes', 'votant_telephone')) {
            DB::table('votes')
                ->whereNull('telephone')
                ->whereNotNull('votant_telephone')
                ->update(['telephone' => DB::raw('votant_telephone')]);
        }
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            foreach (['telephone', 'moyen_paiement', 'webhook_recu_le'] as $colonne) {
                if (Schema::hasColumn('votes', $colonne)) {
                    $table->dropColumn($colonne);
                }
            }
        });
    }
};
