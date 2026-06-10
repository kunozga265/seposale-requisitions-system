<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_options', function (Blueprint $table) {
            $table->id();
            $table->double('cost');
            $table->double('max');
            $table->boolean('available');
            $table->integer('vehicle_type_id');
            $table->integer('product_id');
            $table->integer('zone_id');
            $table->json('meta');
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
        Schema::dropIfExists('transport_options');
    }
}
