<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappMessageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_message_statuses', function (Blueprint $table) {
            $table->id();
            // $table->double('date');
            $table->string('status'); //accepted, sent, delivered, failed etc
            $table->json('payload')->nullable();
            $table->string('wamid');
            $table->integer('whatsapp_message_id');
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
        Schema::dropIfExists('whatsapp_message_statuses');
    }
}
