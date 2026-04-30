<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptSummaryIdToAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->foreignId('receipt_summary_id')
                ->nullable()
                ->constrained('receipt_summaries')
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
            $table->dropForeign(['receipt_summary_id']);
            $table->dropColumn('receipt_summary_id');
        });
    }
}
