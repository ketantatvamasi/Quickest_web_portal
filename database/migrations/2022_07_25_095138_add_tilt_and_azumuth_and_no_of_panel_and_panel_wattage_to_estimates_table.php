<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTiltAndAzumuthAndNoOfPanelAndPanelWattageToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->string('azumuth',255)->nullable();
            $table->unsignedInteger('tilt')->default(0);
            $table->unsignedInteger('no_of_panel')->default(0);
            $table->unsignedInteger('panel_wattage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('azumuth');
            $table->dropColumn('tilt');
            $table->dropColumn('no_of_panel');
            $table->dropColumn('panel_wattage');
        });
    }
}
