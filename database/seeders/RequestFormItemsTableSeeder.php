<?php

namespace Database\Seeders;

use App\Models\RequestForm;
use App\Models\RequestFormItem;
use Illuminate\Database\Seeder;

class RequestFormItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request_forms = RequestForm::all();
        foreach ($request_forms as $request_form) {

            $items = json_decode($request_form->information, true);
            if(!is_array($items)) {
                continue; // Skip if information is not an array
            }
            foreach ($items as $item) {
                RequestFormItem::create([
                    "details" => $item['details'],
                    "units" => $item['units'],
                    "quantity" => $item['quantity'],
                    "unit_cost" => $item['unitCost'],
                    "total_cost" => $item['totalCost'],
                    "balance" => $item['totalCost'],
                    "status" => $request_form->approvalStatus,
                    "request_id" => $request_form->id,
                    "accounting_account_id" => $item['accountingAccountId'] ?? null,
                    "transporter_id" => $item['transporterId'] ?? null,
                    "supplier_id" => $item['supplierId'] ?? null,
                    "comments" => $item['comments'] ?? null,
                ]);
            }
        }
    }
}
