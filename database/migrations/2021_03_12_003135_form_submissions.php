<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FormSubmissions extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('applicant_user_id')->index();
            $table->bigInteger('program_cycle_id')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('application_submissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('form_version_id')->index();
            $table->bigInteger('application_id')->index();

            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('application_submissions');
    }
}
