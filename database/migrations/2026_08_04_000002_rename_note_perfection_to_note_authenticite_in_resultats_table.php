<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            if (Schema::hasColumn('resultats', 'note_perfection') && ! Schema::hasColumn('resultats', 'note_authenticite')) {
                $table->renameColumn('note_perfection', 'note_authenticite');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            if (Schema::hasColumn('resultats', 'note_authenticite') && ! Schema::hasColumn('resultats', 'note_perfection')) {
                $table->renameColumn('note_authenticite', 'note_perfection');
            }
        });
    }
};
