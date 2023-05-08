<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonalDataUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dateTimeTz('birth_date')->nullable();
            $table->text('birth_place')->nullable();
            $table->text('nik')->nullable();
            $table->text('npwp')->nullable();
            $table->text('mother_name')->nullable();
            $table->uuid('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'birth_place', 'nik', 'npwp', 'mother_name', 'update_employment_at', 'gender']);
        });
    }
}
