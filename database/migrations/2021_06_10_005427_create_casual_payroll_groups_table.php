<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasualPayrollGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casual_payroll_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('department')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('status')->nullable();
            $table->integer('payroll_type')->nullable();
            $table->integer('with_hazard')->nullable();
            $table->softdeletes();
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
        Schema::dropIfExists('casual_payroll_groups');
    }
}
