<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->nullable();
            $table->string('statut', 30)->nullable();
            $table->string('categorie', 60)->nullable();
            $table->string('message')->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->unsignedBigInteger('vote_id')->nullable();
            $table->integer('montant')->nullable();
            $table->text('contexte')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_logs');
    }
};
