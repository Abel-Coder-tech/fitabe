<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('resultats')) {
            return;
        }

        Schema::table('resultats', function (Blueprint $table) {
            if (!Schema::hasColumn('resultats', 'note_perfection')) {
                $table->decimal('note_perfection', 4, 2)->nullable()->after('note_presence');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            if (Schema::hasColumn('resultats', 'note_perfection')) {
                $table->dropColumn('note_perfection');
            }
        });
    }
};
