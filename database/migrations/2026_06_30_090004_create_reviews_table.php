<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('name');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->double('date');
            $table->boolean('verified')->default(false);
            $table->string('location')->nullable();
            $table->unsignedInteger('helpful_count')->default(0);
            $table->json('photos')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
