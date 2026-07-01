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
        Schema::table('votes', function (Blueprint $table) {
            $table->integer('quantite')->default(1)->after('candidat_id');
            $table->integer('montant')->nullable()->after('quantite');
            $table->string('payment_method', 50)->nullable()->after('montant');
            $table->string('transaction_id', 255)->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['quantite', 'montant', 'payment_method', 'transaction_id']);
        });
    }
};
