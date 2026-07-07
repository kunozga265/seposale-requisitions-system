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
use App\Models\Batch;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Essentials
        // $this->call(RoleTableSeeder::class);
        // $this->call(PositionTableSeeder::class);
        // $this->call(ProductTableSeeder::class);
        // $this->call(ProductAppendTableSeeder::class);
        $this->call(ProductVariantAppendTableSeeder::class);
        // $this->call(UserTableSeeder::class);
        $this->call(PaymentMethodTableSeeder::class);
        // $this->call(FaqSeeder::class);
        // $this->call(BuildingTipSeeder::class);
        // $this->call(SitesTableSeeder::class);
        // $this->call(InventoryTableSeeder::class);
        // $this->call(AccountTypeTableSeeder::class);
        // $this->call(AccountsGroupTableSeeder::class);
        // $this->call(AccountingAccountsTableSeeder::class);
        // $this->call(ProductAccountsTableSeeder::class);
        // $this->call(ClientTypeTableSeeder::class);
        
        
        //Optionals
        // $this->call(SyncCollections::class);
        // $this->call(SummaryTableSeeder::class);
        // $this->call(RequestFormTableSeeder::class);
        // $this->call(DeliveryTableSeeder::class);
        // $this->call(SerialSeeder::class);
        // $this->call(ClientTableSeeder::class);
        // $this->call(ExpenseTypeTableSeeder::class);
        // $this->call(SupplierTableSeeder::class);
        // $this->call(TransporterTableSeeder::class);
        // $this->call(AccountTableSeeder::class);
        // $this->call(BatchTableSeeder::class);
        // $this->call(MaterialsTypeSeederTable::class);
        // $this->call(ReceiptSummariesSeeder::class);
        // $this->call(RequestFormItemsTableSeeder::class);
        // $this->call(InventoryAccountsTableSeeder::class);
        // $this->call(MaterialsTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        // $this->call(TransportOptionTableSeeder::class);
        
        // $batches = Batch::all();

        // foreach($batches as $batch){
        //     $batch->update([
        //         'active' => true
        //     ]);
        // }

    }
}
