<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('auth_type', ['sso', 'local']);
            $table->string('username');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->datetime('last_directory_sync_at')->nullable();
            $table->string('employee_id', 7)->nullable();
            $table->string('phone')->nullable();

            /**
             * Preferred vs. legal, for folks with professional names/deadnames/etc.
             * We will need legal name for the award contract, but no reason to plaster
             * it all over the app if it isn't what people know them by.
             */
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('legal_first_name')->nullable();
            $table->string('legal_last_name')->nullable();

            $table->enum('primary_affiliation', ['outside-sponsor', 'staff', 'faculty', 'student', 'emeritus']);
            $table->boolean('is_outside_sponsor')->default(false);
            $table->boolean('is_staff')->default(false);
            $table->boolean('is_faculty')->default(false);
            $table->boolean('is_student')->default(false);
            $table->boolean('is_emeritus')->default(false);

            $table->rememberToken();
            $table->timestamps();

            $table->unique(['auth_type', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
