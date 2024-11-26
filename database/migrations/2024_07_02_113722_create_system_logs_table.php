<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('system_logs')) {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("message");
            $table->integer("delivery_id")->nullable();
            $table->integer("sale_id")->nullable();
            $table->integer("site_sale_id")->nullable();
            $table->integer("request_form_id")->nullable();
            $table->integer("collection_id")->nullable();
            $table->integer("inventory_id")->nullable();
            $table->json("contents")->nullable();
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
        Schema::dropIfExists('system_logs');
    }
}
