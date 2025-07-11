<?php

namespace Database\Seeders;

use App\Models\AccountingAccount;
use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $materials = Material::all();

        foreach ($materials as $material) {
            $material->update([
                'inventory_account_id' => AccountingAccount::where('code', 1110)->first()->id,
                // 'cogs_account_id' => AccountingAccount::where('code', 5190)->first()->id,
            ]);
        }
    }
}
