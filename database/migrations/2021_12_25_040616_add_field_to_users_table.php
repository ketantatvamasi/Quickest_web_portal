<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('password');
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('country_id')->unsignedBigInteger()->default(0);
            $table->integer('state_id')->unsignedBigInteger()->default(0);
            $table->integer('city_id')->unsignedBigInteger()->default(0);
            $table->integer('company_category')->unsignedBigInteger()->default(0);
            $table->string('website_link')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('profile_icon')->nullable();
            $table->enum('status', ['Pending','New', 'Approved','Rejected'])->default('New');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('address');
            $table->dropColumn('pincode');
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');
            $table->dropColumn('company_category');
            $table->dropColumn('website_link');
            $table->dropColumn('gst_no');
            $table->dropColumn('profile_icon');
            $table->dropColumn('status');
        });
    }
}
