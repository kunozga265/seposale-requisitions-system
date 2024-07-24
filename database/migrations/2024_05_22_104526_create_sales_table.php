<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->integer("code_alt")->unique();
            $table->integer("status");
            $table->integer("client_id");
            $table->double("total");
            $table->double("balance");
            $table->double("date");
            $table->boolean("editable");
            $table->json("comments")->nullable();
            $table->string("location")->nullable();
            $table->integer("user_id");
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
        Schema::dropIfExists('sales');
    }
}
