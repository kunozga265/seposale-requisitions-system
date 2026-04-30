<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionIdColumnToAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->foreignId('collection_id')
                ->nullable()
                ->constrained('collections')
                ->onDelete('set null')
                ->after('production_id'); // Foreign key to the accounting account, nullable if not applicable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropColumn('collection_id');
        });
    }
}
