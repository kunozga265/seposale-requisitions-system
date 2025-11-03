<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();  
            $table->string("serial");
            $table->integer("code");
            $table->double("date");
            $table->double("quantity");
            $table->double("cost")->nullable();
            $table->double("total");
            $table->double("balance");
            $table->string("photo")->nullable();
            $table->string("recipient_name");
            $table->string("recipient_phone_number");
            $table->integer("delivery_id");
            // $table->integer("client_id");
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
        Schema::dropIfExists('delivery_notes');
    }
}
