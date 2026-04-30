<?php

namespace Database\Seeders;

use App\Models\RequestForm;
use Illuminate\Database\Seeder;

class RequestFormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requestForms = RequestForm::orderBy("created_at","asc")->get();
        $index = 1;
        foreach ($requestForms as $requestForm){
            $requestForm->update([
                "code_alt" => $index
            ]);
            $index++;
        }
    }
}
