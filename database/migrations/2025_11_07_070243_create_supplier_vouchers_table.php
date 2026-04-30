<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_vouchers', function (Blueprint $table) {
              $table->id();
            $table->string("serial");
            $table->integer("code");
            $table->double("date");
            $table->double("amount");
            $table->integer("payable_id");
            $table->integer("transporter_id")->nullable();
            $table->integer("supplier_id")->nullable();
            $table->integer("site_id");
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
        Schema::dropIfExists('supplier_vouchers');
    }
}
