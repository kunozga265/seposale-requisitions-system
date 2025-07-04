<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('code')->unique();
            $table->string('type');
            $table->string('special_type')->nullable(); // e.g., 'cash', 'bank', 'receivable', 'payable'
            $table->string('description')->nullable(); // Description of the account
            $table->boolean('is_active')->default(true); // Whether the account is active
            $table->integer('accounts_group_id');
            $table->double('balance')->default(0); // Balance of the account
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_accounts');
    }
}
