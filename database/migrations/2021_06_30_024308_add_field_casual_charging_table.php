<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCasualChargingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('casual_chargings', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('accounts')->nullable();
            $table->integer('accounts_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('casual_chargings', function ($table) {
            $table->dropColumn('code');
            $table->dropColumn('accounts');
            $table->dropColumn('accounts_id');
        });
    }
}
