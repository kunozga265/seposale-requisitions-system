<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->boolean('type'); // 0 -> system, 1 -> client
            $table->string('status'); //processing, sent, delivered, failed etc
            $table->string('phone_number'); //sent to, or received from
            $table->string('message_type')->nullable(); //proof_of_payment, delivery_order etc
            $table->string('message')->nullable(); //Dear Association etc, failed because ... etc
            $table->string('whatsapp_message_id');
            $table->string('whatsapp_message_type')->nullable(); //contacts, document, text etc
            $table->json('payload')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('sale_id')->nullable();
            $table->integer('quotation_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('receipt_id')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->integer('collection_id')->nullable();
            $table->integer('credit_voucher_id')->nullable();
            $table->integer('supplier_voucher_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('whatsapp_messages');
    }
}
