<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsForEmploymentUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->uuid('occupation_id')->nullable();
            $table->text('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->text('company_subdistrict')->nullable();
            $table->uuid('line_of_business_id')->nullable();
            $table->uuid('job_title_id')->nullable();
            $table->uuid('gross_income_id')->nullable();
            $table->uuid('income_free_text')->nullable();
            $table->uuid('source_of_fund_id')->nullable();
            $table->text('source_of_fund_free_text')->nullable();
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
            $table->dropColumn(['occupation_id', 'company_name', 'company_address', 'company_subdistrict', 'line_of_business_id', 'job_title_id', 'gross_income_id', 'income_free_text', 'source_of_fund_id', 'source_of_fund_free_text']);
        });
    }
}
