<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_rewards', function (Blueprint $table) {
            $table->text('note')->nullable()->after('date');
            $table->unsignedBigInteger('user_id')->nullable()->after('note');
        });

        DB::statement("ALTER TABLE client_rewards MODIFY type ENUM('earned', 'applied', 'withdrawn', 'deducted') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE client_rewards MODIFY type ENUM('earned', 'applied', 'withdrawn') NOT NULL");

        Schema::table('client_rewards', function (Blueprint $table) {
            $table->dropColumn(['note', 'user_id']);
        });
    }
};
