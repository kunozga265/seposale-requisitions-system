<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteSaleSummaryController extends Controller
{
    public function getCollections($collections)
    {
        $array = [];
        foreach ($collections as $collection){
            $by = $collection->collected_by != null ? ucwords($collection->collected_by) : " self";
            $phone_number = $collection->collected_by_phone_number != null ? "({$collection->collected_by_phone_number})" : "";
            $array[] = [
                "date" => intval($collection->date),
                "code" => strval($collection->code),
                "message" => "{$collection->quantity} collected by $by $phone_number",
                "photo" => $collection->photo,
            ];
        }

        return $array;
    }
}
