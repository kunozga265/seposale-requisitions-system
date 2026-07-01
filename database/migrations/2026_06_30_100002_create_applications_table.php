<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
                $table->string('first_name');
                $table->string('last_name');
                $table->double('date_of_birth')->nullable();
                $table->string('email');
                $table->string('gender');
                $table->string('phone_number');
                $table->text('qualifications')->nullable();
                $table->json('fields')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
