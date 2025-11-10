<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestFormItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_form_items', function (Blueprint $table) {
            $table->id();
            $table->string("details");
            $table->string("units")->nullable();
            $table->double("quantity");
            $table->double("unit_cost");
            $table->double("total_cost");
            $table->double("balance");
            $table->string("status");
            $table->integer("request_id");
            $table->string("accounting_account_id")->nullable();
            $table->string("transporter_id")->nullable();
            $table->string("supplier_id")->nullable();
            $table->string("comments")->nullable();
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
        Schema::dropIfExists('request_form_items');
    }
}
