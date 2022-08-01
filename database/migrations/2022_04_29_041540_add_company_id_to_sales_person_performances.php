<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToSalesPersonPerformances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_person_performances', function (Blueprint $table) {
            $table->bigInteger('company_id')->unsignedBigInteger()->default(0)->after('daily_performance');
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
            $table->dropColumn('company_id');
        });
    }
}
