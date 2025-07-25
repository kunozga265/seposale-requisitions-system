<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRequestFormItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_form_items', function (Blueprint $table) {
            $table->foreignId('inventory_id')
                ->nullable()
             
                ->onDelete('set null')
                ->after('supplier_id'); 
            $table->foreignId('material_id')
                ->nullable()
               
                ->onDelete('set null')
                ->after('inventory_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_form_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
            $table->dropColumn('inventory_id');
            $table->dropForeign(['material_id']);
            $table->dropColumn('material_id');
        });
    }
}
