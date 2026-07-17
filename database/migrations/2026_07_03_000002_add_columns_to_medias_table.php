<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('medias', 'description')) {
            Schema::table('medias', function (Blueprint $table) {
                $table->text('description')->nullable()->after('titre');
            });
        }

        if (Schema::hasColumn('medias', 'chemin_fichier')) {
            Schema::table('medias', function (Blueprint $table) {
                $table->renameColumn('chemin_fichier', 'url');
            });
        }

        if (!Schema::hasColumn('medias', 'lien_youtube')) {
            Schema::table('medias', function (Blueprint $table) {
                $table->string('lien_youtube', 255)->nullable()->after('url');
            });
        }

        if (!Schema::hasColumn('medias', 'annee_edition')) {
            Schema::table('medias', function (Blueprint $table) {
                $table->string('annee_edition', 10)->nullable()->after('lien_youtube');
            });
        }

        if (!Schema::hasColumn('medias', 'ordre_affichage')) {
            Schema::table('medias', function (Blueprint $table) {
                $table->integer('ordre_affichage')->nullable()->after('annee_edition');
            });
        }
    }

    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }
};
