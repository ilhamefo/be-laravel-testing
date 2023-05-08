<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlaggingCollumnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dateTimeTz('update_phone_at')->nullable();
            $table->dateTimeTz('update_email_at')->nullable();
            $table->dateTimeTz('update_bank_at')->nullable();
            $table->dateTimeTz('update_personal_data_at')->nullable();
            $table->dateTimeTz('update_home_address_at')->nullable();
            $table->dateTimeTz('update_employment_at')->nullable();
            $table->dateTimeTz('update_additional_information_at')->nullable();
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
            $table->dropColumn(['update_phone_at', 'update_email_at', 'update_bank_at', 'update_personal_data_at', 'update_home_address_at', 'update_employment_at', 'update_additional_information_at']);
        });
    }
}
