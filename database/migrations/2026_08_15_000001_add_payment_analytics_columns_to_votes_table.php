<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            if (! Schema::hasColumn('votes', 'frais')) {
                $table->unsignedInteger('frais')->nullable()->after('montant');
            }
            if (! Schema::hasColumn('votes', 'operateur')) {
                $table->string('operateur', 50)->nullable()->after('moyen_paiement');
            }
            if (! Schema::hasColumn('votes', 'pays')) {
                $table->string('pays', 2)->nullable()->after('operateur');
            }
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            foreach (['frais', 'operateur', 'pays'] as $colonne) {
                if (Schema::hasColumn('votes', $colonne)) {
                    $table->dropColumn($colonne);
                }
            }
        });
    }
};
