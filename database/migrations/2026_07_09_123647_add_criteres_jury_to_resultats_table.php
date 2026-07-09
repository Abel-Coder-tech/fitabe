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
        Schema::table('resultats', function (Blueprint $table) {
            $table->decimal('note_technique', 4, 2)->nullable()->after('note_jury');
            $table->decimal('note_originalite', 4, 2)->nullable()->after('note_technique');
            $table->decimal('note_presence', 4, 2)->nullable()->after('note_originalite');
        });
    }

    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropColumn(['note_technique', 'note_originalite', 'note_presence']);
        });
    }
};
