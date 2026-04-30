<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoneIdConfirmedColumnsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('zone_id')->nullable();
            $table->boolean('client_generated')->default(false);
            $table->boolean('confirmed')->default(true);
            $table->double('confirmed_date')->nullable();
            $table->integer('location_id')->nullable();
            $table->json('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('zone_id');
            $table->dropColumn('client_generated');
            $table->dropColumn('confirmed');
            $table->dropColumn('confirmed_date');
            $table->dropColumn('location_id');
            $table->dropColumn('meta');
        });
    }
}
