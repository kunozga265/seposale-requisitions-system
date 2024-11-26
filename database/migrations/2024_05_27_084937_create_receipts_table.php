<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('receipts')) {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->integer("client_id");
            $table->integer("sale_id")->nullable();
            $table->integer("site_sale_id")->nullable();
            $table->integer("payment_method_id");
            $table->integer("user_id");
            $table->integer("code");
            $table->double("amount");
            $table->double("date");
            $table->string("reference")->nullable();
            $table->json("information")->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
