<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->integer("code");
                $table->string("serial")->unique();
                $table->integer("status");
                $table->string("photo")->nullable();
                $table->string("tracking_number")->nullable();
                $table->double("quantity_delivered");
                $table->double("due_date")->nullable();
                $table->integer("summary_id");
                $table->json("notes")->nullable();
                $table->boolean("whatsapp")->nullable();
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
        Schema::dropIfExists('deliveries');
    }
}
