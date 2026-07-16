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
        Schema::table('candidats', function (Blueprint $table) {
            if (Schema::hasColumn('candidats', 'note_jury')) {
                $table->dropColumn('note_jury');
            }
            $table->decimal('note_maitrise', 5, 2)->nullable()->after('nombre_votes');
            $table->decimal('note_originalite', 5, 2)->nullable()->after('note_maitrise');
            $table->decimal('note_presence', 5, 2)->nullable()->after('note_originalite');
        });
    }

    public function down(): void
    {
        Schema::table('candidats', function (Blueprint $table) {
            $table->dropColumn(['note_maitrise', 'note_originalite', 'note_presence']);
            $table->decimal('note_jury', 5, 2)->nullable()->after('nombre_votes');
        });
    }
};
