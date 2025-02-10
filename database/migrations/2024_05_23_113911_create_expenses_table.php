<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('expenses')){
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->double("total");
            $table->double("date");
            $table->json("contents");
            $table->integer("expense_type_id");
            $table->integer("sale_id")->nullable();
            $table->integer("delivery_id")->nullable();
            $table->integer("request_id")->nullable();
            $table->integer("transporter_id")->nullable();
            $table->integer("supplier_id")->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
