<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithdrawalFieldsToPaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('for_withdrawal')->default(false)->after('photo');
            $table->json('withdrawal_fields')->nullable()->after('for_withdrawal');
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['for_withdrawal', 'withdrawal_fields']);
        });
    }
}
