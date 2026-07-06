<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->text('description')->nullable()->after('titre');
            $table->string('lien_youtube', 255)->nullable()->after('url');
            $table->string('annee_edition', 10)->nullable()->after('lien_youtube');
            $table->integer('ordre_affichage')->nullable()->after('annee_edition');
        });
    }

    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropColumn(['description', 'lien_youtube', 'annee_edition', 'ordre_affichage']);
        });
    }
};
