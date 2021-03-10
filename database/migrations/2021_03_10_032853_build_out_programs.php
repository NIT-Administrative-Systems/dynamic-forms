<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuildOutPrograms extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->index();
            $table->string('slug');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'slug']);
        });

        Schema::create('program_cycles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('program_id');
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('form_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unqiue();
            $table->string('name');

            $table->timestamps();
        });

        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('program_id')->index();
            $table->bigInteger('form_type_id')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('form_versions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('form_id')->index();
            $table->json('definition');
            $table->datetime('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('program_cycles');
        Schema::dropIfExists('form_types');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('form_versions');
    }
}
