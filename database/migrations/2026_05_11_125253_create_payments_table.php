<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("state");
            $table->string("status");
            $table->string("reference")->unique();
            $table->json("content");

            $table->foreignId("client_id")
                ->constrained('clients')
                ->onDelete('cascade');

            $table->foreignId("sale_id")
                ->constrained('sales')
                ->onDelete('cascade');

            $table->foreignId("receipt_id")
                ->nullable()
                ->constrained('receipts')
                ->onDelete('set null');

            $table->timestamp('deleted_at')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
