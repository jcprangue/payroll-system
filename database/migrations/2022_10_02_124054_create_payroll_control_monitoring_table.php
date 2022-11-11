<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollControlMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_control_monitoring', function (Blueprint $table) {
            $table->id();
            $table->integer('payroll_group_id');
            $table->integer('payroll_period');
            $table->decimal('amount');
            $table->date('month');
            $table->string('control_number');
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
        Schema::dropIfExists('payroll_control_monitoring');
    }
}
