<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')
                  ->constrained('candidates')
                  ->cascadeOnDelete();
            $table->string('transaction_id', 100)->unique();
            $table->unsignedSmallInteger('quantite');
            $table->unsignedInteger('montant');
            $table->string('telephone', 20)->nullable();
            $table->enum('moyen_paiement', ['mtn', 'moov', 'carte'])->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'echoue'])->default('en_attente');
            $table->timestamp('webhook_recu_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};