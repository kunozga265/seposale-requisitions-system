<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inventories')) {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("units")->nullable();
            $table->double("cost");
            $table->integer("site_id");
            $table->double("available_stock");
            $table->double("uncollected_stock");
            $table->double("threshold");
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('inventories');
    }
}
