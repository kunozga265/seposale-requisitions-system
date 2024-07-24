<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            "title"             =>  "Managing Director",
            "approvalStages"    =>  json_encode([]),
        ]);

        Position::create([
            "title"             =>  "Chief Executive Officer",
            "approvalStages"    =>  json_encode([]),
        ]);

        Position::create([
            "title"             =>  "Administrative Officer",
            "approvalStages"    =>  json_encode([]),
        ]);

        Position::create([
            "title"             =>  "Sales Officer",
            "approvalStages"    =>  json_encode([  [
                "stage"     =>  1,
                "position"  =>  3,
                "name"      =>  null,
                "date"      =>  null,
                "status"    =>  false
            ]]),
        ]);

        Position::create([
            "title"             =>  "Deliveries Officer",
            "approvalStages"    =>  json_encode([  [
                "stage"     =>  1,
                "position"  =>  3,
                "name"      =>  null,
                "date"      =>  null,
                "status"    =>  false
            ]]),
        ]);

        Position::create([
            "title"             =>  "Marketing Officer",
            "approvalStages"    =>  json_encode([  [
                "stage"     =>  1,
                "position"  =>  3,
                "name"      =>  null,
                "date"      =>  null,
                "status"    =>  false
            ]]),
        ]);

        Position::create([
            "title"             =>  "Operations Officer",
            "approvalStages"    =>  json_encode([  [
                "stage"     =>  1,
                "position"  =>  3,
                "name"      =>  null,
                "date"      =>  null,
                "status"    =>  false
            ]]),
        ]);

    }
}
