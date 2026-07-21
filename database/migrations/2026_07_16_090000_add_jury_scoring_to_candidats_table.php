<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('candidats')) {
            return;
        }

        Schema::table('candidats', function (Blueprint $table) {
            if (!Schema::hasColumn('candidats', 'note_technique')) {
                $table->decimal('note_technique', 4, 2)->nullable();
            }
            if (!Schema::hasColumn('candidats', 'note_originalite')) {
                $table->decimal('note_originalite', 4, 2)->nullable();
            }
            if (!Schema::hasColumn('candidats', 'note_scene')) {
                $table->decimal('note_scene', 4, 2)->nullable();
            }
            if (!Schema::hasColumn('candidats', 'score_final')) {
                $table->decimal('score_final', 5, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            $table->dropColumn(['note_technique', 'note_originalite', 'note_scene', 'score_final']);
        });
    }
};
