<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->string('votant_nom', 255)->nullable()->after('candidate_id');
            $table->string('votant_email', 255)->nullable()->after('votant_nom');
            $table->string('votant_telephone', 50)->nullable()->after('votant_email');
            $table->string('ip_address', 50)->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['votant_nom', 'votant_email', 'votant_telephone', 'ip_address']);
        });
    }
};
