<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('site_sales')) {
            Schema::create('site_sales', function (Blueprint $table) {
                $table->id();
                $table->integer("code")->unique();
                $table->string("serial")->unique();
                $table->integer("status");
                $table->integer("client_id");
                $table->double("total");
                $table->double("balance");
                $table->double("date");
                $table->boolean("editable");
                $table->integer("user_id");
                $table->integer("site_id");
                $table->integer("inventory_summary_id");
                $table->boolean("whatsapp")->nullable();
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
        Schema::dropIfExists('site_sales');
    }
}
