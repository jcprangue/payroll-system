<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSignatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_signatories', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id')->nullable();
            $table->string('department_head')->nullable();
            $table->string('department_head_position')->nullable();
            $table->integer('signatory_role')->nullable();
            $table->string('company')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('payroll_signatories');
    }
}
