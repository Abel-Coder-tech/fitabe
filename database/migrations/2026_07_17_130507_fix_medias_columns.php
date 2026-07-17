<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            if (Schema::hasColumn('medias', 'chemin_fichier') && !Schema::hasColumn('medias', 'url')) {
                $table->renameColumn('chemin_fichier', 'url');
            }
            if (!Schema::hasColumn('medias', 'description')) {
                $table->text('description')->nullable()->after('titre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            if (Schema::hasColumn('medias', 'url') && !Schema::hasColumn('medias', 'chemin_fichier')) {
                $table->renameColumn('url', 'chemin_fichier');
            }
            if (Schema::hasColumn('medias', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
