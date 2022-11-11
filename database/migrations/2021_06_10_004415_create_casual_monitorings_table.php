<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasualMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casual_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->nullable();
            $table->integer('casual_payroll_group')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('status')->nullable();
            $table->date('month')->nullable();
            $table->integer('quincena')->nullable();
            $table->decimal('amount',20,2)->nullable();
            $table->integer('user_id')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('casual_monitorings');
    }
}
