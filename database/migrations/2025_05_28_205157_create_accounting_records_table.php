<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_records', function (Blueprint $table) {
            $table->id();
            $table->string("serial")->unique();
            $table->string("reference")->nullable();
            $table->double("date");
            $table->string("name"); // Name of the record
            $table->text("description")->nullable(); // Description of the record
            $table->double("amount"); // Amount of the record
            $table->double("opening_balance"); // Balance before the record
            $table->double("closing_balance"); // Balance after the record
            $table->enum("type", ['DEBIT', 'CREDIT']); // Type of the record
            $table->foreignId("accounting_account_id")
                ->constrained('accounting_accounts')
                ->onDelete('cascade'); // Foreign key to the accounting account
            $table->foreignId("receipt_id")
                ->nullable()
                ->constrained('receipts')
                ->onDelete('set null'); // Foreign key to the receipt, nullable if not applicable
            $table->foreignId("accounting_record_id")
                ->nullable()
                ->constrained('accounting_records')
                ->onDelete('set null'); // Foreign key to the parent accounting record, nullable if not applicable
            $table->foreignId("request_form_item_id")
                ->nullable()
                ->constrained('request_form_items')
                ->onDelete('set null'); // Foreign key to the request form item, nullable if not applicable
            $table->foreignId("summary_id")
                ->nullable()
                ->constrained('summaries')
                ->onDelete('set null'); // Foreign key to the summary, nullable if not applicable
            $table->foreignId("site_sale_summary_id")
                ->nullable()
                ->constrained('site_sale_summaries')
                ->onDelete('set null'); // Foreign key to the site sale summary, nullable if not applicable
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
        Schema::dropIfExists('accounting_records');
    }
}
