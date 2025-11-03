<?php

namespace Database\Seeders;

use App\Http\Controllers\AccountingAccountController;
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
use Illuminate\Database\Seeder;

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

        // $this->call(RoleTableSeeder::class);
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
        Delivery::all()->each(function ($delivery) {

            $notes = json_decode($delivery->notes, true) ?? [];
            foreach ($notes as $note) {
               DeliveryNote::create([
                    "serial" => (new AppController())->generateUniqueCode("DELIVERY_NOTE"),
                    "code" =>(new DeliveryController())->getNoteCodeNumber($delivery),
                    "date"  => $note["date"],
                    "quantity" => $note["quantity"],
                    "cost" => $note["cost"],
                    "total" => $note["total"],
                    "balance" => $note["balance"],
                    "photo" => $note["photo"],
                    "recipient_name" => $note["recipientName"],
                    "recipient_phone_number" => $note["recipientPhoneNumber"],
                    "delivery_id" => $delivery->id,
                ]);
            }
        });
    }
}
