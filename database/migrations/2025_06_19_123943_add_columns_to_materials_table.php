<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('inventory_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null')
                ->after('product_id'); // Foreign key to the accounting account, nullable if not applicable
            $table->foreignId('cogs_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null')
                ->after('accounting_account_id'); // Foreign key to the COGS account, nullable if not applicable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['inventory_account_id']);
            $table->dropColumn('inventory_account_id');
            $table->dropForeign(['cogs_account_id']);
            $table->dropColumn('cogs_account_id');
        });
    }
}
