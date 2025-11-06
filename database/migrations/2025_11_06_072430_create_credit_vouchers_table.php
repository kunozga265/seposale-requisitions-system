<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("serial");
            $table->integer("code");
            $table->double("date");
            $table->double("amount");
            $table->double("balance");
            $table->integer("payable_id");
            $table->integer("transporter_id")->nullable();
            $table->integer("supplier_id")->nullable();
            $table->integer("delivery_id")->nullable();
            $table->integer("sale_id")->nullable();
            $table->boolean("paid")->default(false);

            //source
            $table->integer("request_id");
            $table->integer("request_form_item");

            //payout
            $table->integer("payout_request_id")->nullable();
            $table->integer("payout_request_form_item")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_vouchers');
    }
}
