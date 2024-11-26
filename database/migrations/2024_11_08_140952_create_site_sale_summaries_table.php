<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSaleSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('site_sale_summaries')) {
            Schema::create('site_sale_summaries', function (Blueprint $table) {
                $table->id();
                $table->integer("inventory_id");
                $table->integer("site_sale_id");
                $table->double("quantity");
                $table->double("amount");
                $table->double("balance");
                $table->double("collected");
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
        Schema::dropIfExists('site_sale_summaries');
    }
}
