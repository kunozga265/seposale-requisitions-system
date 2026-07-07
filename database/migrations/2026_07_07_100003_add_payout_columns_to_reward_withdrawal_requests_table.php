<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutColumnsToRewardWithdrawalRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('reward_withdrawal_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_id')->nullable()->after('notes');
            $table->json('payout_details')->nullable()->after('payment_method_id');
        });
    }

    public function down()
    {
        Schema::table('reward_withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_method_id', 'payout_details']);
        });
    }
}
