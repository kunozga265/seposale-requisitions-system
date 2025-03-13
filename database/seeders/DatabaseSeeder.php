<?php

namespace Database\Seeders;

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PayableController;
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
         $this->call(BatchTableSeeder::class);

//        $expenses = Payable::all();
//        foreach ($expenses as $expense) {
//            $expense->update([
//                "code" => (new PayableController())->getCodeNumber(),
//            ]);
//        }
    }
}
