<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsts', function (Blueprint $table) {
            $table->id();
            $table->float('name', 5, 2);
            $table->boolean('status')->default(false);
            $table->bigInteger('company_id')->unsignedBigInteger()->default(0);
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
        Schema::dropIfExists('gsts');
    }
}
