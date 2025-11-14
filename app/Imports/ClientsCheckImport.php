<?php

namespace App\Imports;

use App\Models\Client;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ClientController;
use App\Models\CustomJob;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ClientsCheckImport implements ToCollection, WithHeadingRow
{
    public function collection($rows)
    {
        $check = true;
        foreach ($rows as $row) {
            $phone_number = $row['phone_number'];
            $name = $row['name'];

            if ($phone_number == '' || $phone_number == null) {
                $check = false;
                break;
            } else if ($name == '' || $name == null) {
                $check = false;
                break;
            }
        }

        return $check;
    }
}
