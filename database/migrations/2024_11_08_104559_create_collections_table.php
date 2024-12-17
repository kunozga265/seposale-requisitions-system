<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('collections')) {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->integer("code")->unique();
            $table->string("serial")->unique();
            $table->integer("client_id");
            $table->string("photo")->nullable();
            $table->string("collected_by")->nullable();
            $table->string("collected_by_phone_number")->nullable();
            $table->integer("inventory_id");
            $table->integer("inventory_summary_id");
            $table->integer("site_sale_summary_id");
            $table->integer("site_id");
            $table->double("quantity");
            $table->double("balance");
            $table->integer("user_id");
            $table->double("date");
            $table->softDeletes();
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
        Schema::dropIfExists('collections');
    }
}
