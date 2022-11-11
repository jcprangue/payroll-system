<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasualWithoutpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casual_withoutpays', function (Blueprint $table) {
            $table->id();
            $table->integer('casual_employee_id');
            $table->date('month')->nullable();
            $table->string('credit')->nullable();
            $table->string('under')->nullable();
            $table->string('ulwop')->nullable();
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
        Schema::dropIfExists('casual_withoutpays');
    }
}
