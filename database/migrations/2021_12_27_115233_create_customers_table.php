<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsignedBigInteger()->default(0);
            $table->enum('customer_type', ['Business', 'Individual'])->default('Business');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_no')->unique();
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('country_id')->unsignedBigInteger()->default(0);
            $table->integer('state_id')->unsignedBigInteger()->default(0);
            $table->integer('city_id')->unsignedBigInteger()->default(0);
            $table->string('profile_icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('customers');
    }
}
