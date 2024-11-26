<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer("code")->unique();
            $table->json("opening_stock");
            $table->json("closing_stock");
            $table->json("comments");
            $table->integer("user_id");
            $table->integer("site_id");
            $table->double("date");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_summaries');
    }
}
