<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // `quantite` est déjà créé par create_votes_table : garde hasColumn pour
        // permettre les installations neuves (migrate:fresh) sans doublon de colonne.
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'quantite')) {
                $table->integer('quantite')->default(1)->after('candidat_id');
            }
            if (!Schema::hasColumn('votes', 'montant')) {
                $table->integer('montant')->nullable()->after('quantite');
            }
            if (!Schema::hasColumn('votes', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('montant');
            }
            if (!Schema::hasColumn('votes', 'transaction_id')) {
                $table->string('transaction_id', 255)->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['quantite', 'montant', 'payment_method', 'transaction_id'] as $colonne) {
            if (Schema::hasColumn('votes', $colonne)) {
                Schema::table('votes', fn (Blueprint $table) => $table->dropColumn($colonne));
            }
        }
    }
};
