<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soutiens', function ($table) {
            $table->string('role_parrain', 100)->nullable()->after('titre');
            $table->dropColumn(['organisation', 'ordre_affichage']);
        });
    }

    public function down(): void
    {
        Schema::table('soutiens', function ($table) {
            $table->string('organisation', 200)->nullable()->after('titre');
            $table->unsignedTinyInteger('ordre_affichage')->default(0);
            $table->dropColumn('role_parrain');
        });
    }
};
