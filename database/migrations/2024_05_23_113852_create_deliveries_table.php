<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->integer("status");
            $table->string("photo")->nullable();
            $table->double("date_initiated")->nullable();
            $table->double("date_delivered")->nullable();
            $table->integer("initiated_by")->nullable();
            $table->integer("delivered_by")->nullable();
            $table->integer("sale_id");
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
        Schema::dropIfExists('deliveries');
    }
}
