<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedFullDescriptionColumnsToProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('slug');
            $table->string('name')->nullable();
            $table->double('cost_original');
            $table->text('description_full')->nullable();
            $table->boolean('featured')->default(0);
            $table->boolean('transport_inclusive')->default(0);
            $table->json('specifications')->nullable();
            $table->json('product_information')->nullable();
            $table->text('about')->nullable();
            $table->string('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('name');
            $table->dropColumn('cost_original');
            $table->dropColumn('description_full');
            $table->dropColumn('featured');
            $table->dropColumn('transport_inclusive');
            $table->dropColumn('specifications');
            $table->dropColumn('product_information');
            $table->dropColumn('about');
            $table->dropColumn('link');
        });
    }
}
