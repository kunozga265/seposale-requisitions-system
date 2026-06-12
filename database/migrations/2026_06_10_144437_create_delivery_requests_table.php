<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->integer('status');
            $table->boolean('active')->default(false);
            $table->double('amount');
            $table->double('quantity');
            $table->integer('trips');
            $table->integer('sale_id');
            $table->integer('summary_id');
            $table->integer('transport_option_id');
            $table->integer('receipt_id')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('payment_receipt_id')->nullable();
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
        Schema::dropIfExists('delivery_requests');
    }
}
