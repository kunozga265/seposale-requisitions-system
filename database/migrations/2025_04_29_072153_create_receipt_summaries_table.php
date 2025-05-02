<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_summaries', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->double("balance")->nullable();
            $table->double("amount");
            $table->double("cost")->nullable();
            $table->string("units")->nullable();
            $table->integer("summary_id")->nullable();
            $table->integer("site_sale_summary_id")->nullable();
            $table->integer("receipt_id");
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
        Schema::dropIfExists('receipt_summaries');
    }
}
