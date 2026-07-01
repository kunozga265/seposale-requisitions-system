<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('vacancies')) {
            Schema::create('vacancies', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->double('date');
                $table->double('due_date')->nullable();
                $table->string('department');
                $table->text('body');
                $table->json('fields')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('vacancies', function (Blueprint $table) {
                if (!Schema::hasColumn('vacancies', 'due_date')) {
                    $table->double('due_date')->nullable()->after('date');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
