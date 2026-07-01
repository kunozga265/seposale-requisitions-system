<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixDateOfBirthInApplicationsTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE applications MODIFY date_of_birth DATE NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE applications MODIFY date_of_birth DOUBLE NULL');
    }
}
