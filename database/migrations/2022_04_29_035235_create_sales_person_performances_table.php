<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPersonPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_person_performances', function (Blueprint $table) {
            $table->id();
            $table->date('performance_date')->nullable();
            $table->bigInteger('user_id')->unsignedBigInteger()->default(0);
            $table->integer('total_task')->default(0);
            $table->integer('completed_task')->default(0);
            $table->double('daily_performance', 10, 2)->default(0);
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
        Schema::dropIfExists('sales_person_performances');
    }
}
