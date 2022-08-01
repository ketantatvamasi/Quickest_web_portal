<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvCompletedTaskToSalesPersonPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_person_performances', function (Blueprint $table) {
            $table->integer('adv_completed_task')->default(0)->after('completed_task');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_person_performances', function (Blueprint $table) {
            $table->dropColumn('adv_completed_task');
        });
    }
}
