<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payables')) {
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->integer("code");
            $table->string("description");
            $table->double("total");
            $table->double("date");
            $table->json("contents");
            $table->integer("expense_type_id");
            $table->integer("request_id")->nullable();
            $table->integer("sale_id")->nullable();
            $table->integer("delivery_id")->nullable();
            $table->integer("transporter_id")->nullable();
            $table->integer("supplier_id")->nullable();
            $table->boolean("paid");
            $table->timestamps();
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
        Schema::dropIfExists('payables');
    }
}
