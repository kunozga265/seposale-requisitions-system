<?php

namespace Database\Seeders;

use App\Http\Controllers\AccountingAccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PayableController;
use App\Models\AccountingAccount;
use App\Models\Expense;
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

        //         $this->call(RoleTableSeeder::class);
        //         $this->call(PositionTableSeeder::class);
        //         $this->call(ProductTableSeeder::class);
        //
        //         $this->call(UserTableSeeder::class);
        //         $this->call(PaymentMethodTableSeeder::class);

        //         $this->call(SummaryTableSeeder::class);
        //         $this->call(RequestFormTableSeeder::class);
        //         $this->call(DeliveryTableSeeder::class);
        //         $this->call(SitesTableSeeder::class);
        //         $this->call(InventoryTableSeeder::class);
        //         $this->call(SerialSeeder::class);
        //         $this->call(ClientTableSeeder::class);
        //         $this->call(ExpenseTypeTableSeeder::class);
        //         $this->call(SupplierTableSeeder::class);
        //         $this->call(TransporterTableSeeder::class);
        //         $this->call(AccountTableSeeder::class);
        //  $this->call(BatchTableSeeder::class);
        //  $this->call(MaterialsTypeSeederTable::class);
        //  $this->call(ReceiptSummariesSeeder::class);

        // $this->call(AccountTypeTableSeeder::class);
        // $this->call(AccountsGroupTableSeeder::class);
        // $this->call(AccountingAccountsTableSeeder::class);
        // $this->call(RequestFormItemsTableSeeder::class);
        // $this->call(ProductAccountsTableSeeder::class);
        // $this->call(InventoryAccountsTableSeeder::class);
        // $this->call(MaterialsTableSeeder::class);

         $receipts = \App\Models\Receipt::where("date",">",1748728800)->get();
         $nb = (new AccountingAccountController())->getAccount(1020);
         $std = (new AccountingAccountController())->getAccount(1021);
         $njewa = (new AccountingAccountController())->getAccount(1022);
         $airwing = (new AccountingAccountController())->getAccount(1023);

        //  foreach($receipts as $receipt){
        //     switch($receipt->account_id){
        //         case 1:
        //             $nb->rec
        //             break;
        //     }
        //  }



    }
}
