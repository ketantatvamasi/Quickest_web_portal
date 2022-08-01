<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogIdAndlogTypeToLogActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_activity', function (Blueprint $table) {
            $table->bigInteger('log_id')->unsignedBigInteger()->default(0);
            $table->string('log_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_activity', function (Blueprint $table) {
            $table->dropColumn('log_id');
            $table->dropColumn('log_type');
        });
    }
}
