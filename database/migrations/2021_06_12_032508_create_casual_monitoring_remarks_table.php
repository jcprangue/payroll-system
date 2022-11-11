<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasualMonitoringRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casual_monitoring_remarks', function (Blueprint $table) {
            $table->id();
            $table->integer('casual_monitoring_id');
            $table->integer('status');
            $table->integer('user_id');
            $table->text('remarks');
            $table->integer('method');
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
        Schema::dropIfExists('casual_monitoring_remarks');
    }
}
