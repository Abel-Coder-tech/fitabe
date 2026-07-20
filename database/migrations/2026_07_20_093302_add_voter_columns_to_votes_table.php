<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->string('email', 255)->nullable()->after('candidate_id');
            $table->string('adresse_ip', 50)->nullable()->after('transaction_id');
            $table->string('transaction_id', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['email', 'adresse_ip']);
            $table->string('transaction_id', 100)->nullable(false)->change();
        });
    }
};
