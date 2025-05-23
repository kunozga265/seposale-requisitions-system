<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('summaries')){
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->string("product_id");
            $table->string("product_variant_id")->nullable();
            $table->string("sale_id");
            $table->double("date");
            $table->double("amount");
            $table->double("balance")->nullable();
            $table->double("quantity");
            $table->string("description")->nullable();
            $table->string("units")->nullable();
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
        Schema::dropIfExists('summaries');
    }
}
