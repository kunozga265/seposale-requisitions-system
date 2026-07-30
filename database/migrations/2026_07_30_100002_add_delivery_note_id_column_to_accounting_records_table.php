<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryNoteIdColumnToAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->foreignId('delivery_note_id')
                ->nullable()
                ->constrained('delivery_notes')
                ->onDelete('set null');
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
            $table->dropForeign(['delivery_note_id']);
            $table->dropColumn('delivery_note_id');
        });
    }
}
