<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('accounts')) {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("number")->nullable();
            $table->string("branch")->nullable();
            $table->string("type")->nullable();
            $table->double("balance");
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
        Schema::dropIfExists('accounts');
    }
}
