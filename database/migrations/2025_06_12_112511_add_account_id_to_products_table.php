<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('cogs_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null')
                ->after('name'); // Foreign key to the COGS account, nullable if not applicable
            $table->foreignId('inventory_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null')
                ->after('cogs_account_id'); // Foreign key to the inventory account, nullable if not applicable
            $table->foreignId('revenue_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null')
                ->after('inventory_account_id'); // Foreign key to the revenue account, nullable if not applicable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['cogs_account_id']);
            $table->dropColumn('cogs_account_id');
            $table->dropForeign(['inventory_account_id']);
            $table->dropColumn('inventory_account_id');
            $table->dropForeign(['revenue_account_id']);
            $table->dropColumn('revenue_account_id');
        });
    }
}
