<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('chart_of_accounts_id')->nullable();
            $table->string('deduction_name')->nullable();
            $table->string('deduction_nick')->nullable();
            $table->integer('user_access_by')->nullable();
            $table->integer('is_loanable')->nullable();
            $table->integer('is_auto_deduct')->nullable();
            $table->integer('is_taxable')->nullable();
            $table->integer('deduction_group')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_codes');
    }
}
