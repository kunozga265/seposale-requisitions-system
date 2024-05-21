<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            //Customer
            $table->integer("code");
            $table->string("name");
            $table->string("phone_number")->nullable();
            $table->string("email")->nullable();
            $table->string("address")->nullable();
            $table->string("location")->nullable();

            $table->json("information");
            $table->double("total");
            $table->json("receipts")->nullable();
            $table->integer("status");
            $table->integer("user_id");
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
        Schema::dropIfExists('invoices');
    }
}
