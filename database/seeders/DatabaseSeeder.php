<?php

namespace Database\Seeders;

use App\Http\Controllers\AccountingAccountController;
use App\Http\Controllers\AccountingRecordController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PayableController;
use App\Models\AccountingAccount;
use App\Models\ClientType;
use App\Models\Client;
use App\Models\Delivery;
use App\Models\DeliveryNote;
use App\Models\Expense;
use App\Models\MaterialsType;
use App\Models\Payable;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\Transporter;
use App\Models\RequestForm;
use App\Models\AccountingRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //        \App\Models\Client::factory(5)->create();

        $this->call(RoleTableSeeder::class);
        $this->call(SyncCollections::class);
        // $this->call(ProductAppendTableSeeder::class);
        // $this->call(ProductVariantAppendTableSeeder::class);

        // $this->call(PositionTableSeeder::class);
        // $this->call(ProductTableSeeder::class);

        // $this->call(UserTableSeeder::class);
        // $this->call(PaymentMethodTableSeeder::class);

        // $this->call(SummaryTableSeeder::class);
        // $this->call(RequestFormTableSeeder::class);
        // $this->call(DeliveryTableSeeder::class);
        // $this->call(SitesTableSeeder::class);
        // $this->call(InventoryTableSeeder::class);
        // $this->call(SerialSeeder::class);
        // $this->call(ClientTableSeeder::class);
        // $this->call(ExpenseTypeTableSeeder::class);
        // $this->call(SupplierTableSeeder::class);
        // $this->call(TransporterTableSeeder::class);
        // $this->call(AccountTableSeeder::class);
        // $this->call(BatchTableSeeder::class);
        // $this->call(MaterialsTypeSeederTable::class);
        // $this->call(ReceiptSummariesSeeder::class);

        // $this->call(AccountTypeTableSeeder::class);
        // $this->call(AccountsGroupTableSeeder::class);
        // $this->call(AccountingAccountsTableSeeder::class);
        // $this->call(RequestFormItemsTableSeeder::class);
        // $this->call(ProductAccountsTableSeeder::class);
        // $this->call(InventoryAccountsTableSeeder::class);
        // $this->call(MaterialsTableSeeder::class);

        // $this->call(ClientTypeTableSeeder::class);

        //capitalise each client's name
        // Client::all()->each(function ($client) {
        //     $client->name = ucwords($client->name);
        //     $client->save();
        // });

        //transfer all notes to objects
        // Delivery::all()->each(function ($delivery) {

        //     $notes = json_decode($delivery->notes, true) ?? [];
        //     foreach ($notes as $note) {
        //        DeliveryNote::create([
        //             "serial" => (new AppController())->generateUniqueCode("DELIVERY_NOTE"),
        //             "code" =>(new DeliveryController())->getNoteCodeNumber($delivery),
        //             "date"  => $note["date"],
        //             "quantity" => $note["quantity"],
        //             "cost" => isset($note["cost"]) ? $note["cost"] : null,
        //             "total" => isset($note["total"]) ? $note["total"] : null,
        //             "balance" => isset($note["balance"]) ? $note["balance"] : null,
        //             "photo" => isset($note["photo"]) ? $note["photo"] : null,
        //             "recipient_name" => isset($note["recipientName"]) ? $note["recipientName"] : null,
        //             "recipient_phone_number" => isset($note["recipientPhoneNumber"]) ? $note["recipientPhoneNumber"] : null,
        //             "delivery_id" => $delivery->id,
        //         ]);
        //     }
        // });

        // AccountingAccount::create([
        //     'name' => 'WHT Recoverable / Tax Credits',
        //     'code' => 1200,
        //     'type' => "DEBIT",
        //     'special_type' => "WHT",
        //     'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        // ]);

        //   PaymentMethod::create([
        //     "name" => "Withholding"
        // ]);

        // Transporter::all()->each(function (Transporter $transporter) {
        //     $transporter->update([
        //         "serial" => (new AppController())->generateUniqueCode("TRANSPORTER"),
        //     ]);
        // });
        // Supplier::all()->each(function (Supplier $supplier) {
        //     $supplier->update([
        //         "serial" => (new AppController())->generateUniqueCode("SUPPLIER"),
        //     ]);
        // });

        //clean transactions
        // $all_records = AccountingRecord::all();
        // foreach ($all_records as $subject) {
        //     $record = AccountingRecord::find($subject->id);

        //     if (is_object($record)) {
        //         if ($record->request_form_item_id != null) {
        //             $records = AccountingRecord::where("amount", $record->amount)
        //                 ->where('id', '!=', $record->id)
        //                 ->where("type", $record->type)
        //                 ->where("name", $record->name)
        //                 // ->where("reference", $record->reference)
        //                 ->where("description", $record->description)
        //                 ->where("request_form_item_id", $record->request_form_item_id)
        //                 ->get();

        //             foreach ($records as $r) {
        //                 $r->delete();
        //                 Log::info("Deleted Id: {$r->id}");
        //                 // Log::info("Subject Id: {$subject->id} RQ Item Id: {$subject->request_form_item_id}-- " . "Record Id: {$r->id}, // Name: {$r->name}, Description: {$r->description}");
        //             }
        //         }


        //         // (new AccountingRecordController())->reverseTransactions($records);


        //     }
        // }

        // $deliveries = Delivery::where('status',0)->where('due_date',null)->get();
        // foreach($deliveries as $d){
        //     $d->delete();
        // }

        // $request_forms = RequestForm::where('type', 'OPERATIONS')->get();
        // foreach ($request_forms as $form) {
        //     foreach ($form->items as $item) {
        //         $item->update([
        //             'unit_cost' => $item->total_cost / $item->quantity
        //         ]);
        //     }
        // }
    }
}
