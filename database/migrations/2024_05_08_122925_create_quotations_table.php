<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();

            //Customer
            $table->integer("code");
            $table->integer("client_id");
            $table->string("location")->nullable();
            $table->string("recipient_name")->nullable();
            $table->string("recipient_profession")->nullable();
            $table->string("recipient_phone_number")->nullable();
            $table->json("information");
            $table->double("total");
            $table->json("quotes")->nullable();
            $table->integer("user_id");
            $table->integer("sale_id")->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
