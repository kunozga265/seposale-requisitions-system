<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer("client_id");
            $table->integer("zone_id")->nullable();
            $table->string("name");
            $table->string("recipient_name");
            $table->string("recipient_profession");
            $table->string("recipient_phone_number");
            $table->json("meta");
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
        Schema::dropIfExists('saved_addresses');
    }
}
