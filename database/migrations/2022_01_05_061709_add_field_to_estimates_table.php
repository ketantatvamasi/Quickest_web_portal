<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->bigInteger('customer_id')->unsignedBigInteger()->default(0);
            $table->bigInteger('customer_state_id')->unsignedBigInteger()->default(0);
            $table->bigInteger('company_state_id')->unsignedBigInteger()->default(0);
            $table->string('estimate_no')->nullable();
            $table->string('reference')->nullable();
            $table->date('estimate_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->double('subtotal', 10, 2)->default(0);
            $table->double('total_cgst_amount', 10, 2)->default(0);
            $table->double('total_sgst_amount', 10, 2)->default(0);
            $table->double('total_igst_amount', 10, 2)->default(0);
            $table->float('addless_amount', 5, 2)->default(0);
            $table->double('net_amount', 10, 2)->default(0);
            $table->bigInteger('company_id')->unsignedBigInteger()->default(0);
            $table->bigInteger('user_id')->unsignedBigInteger()->default(0);
            $table->bigInteger('sales_person_id')->unsignedBigInteger()->default(0);
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
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_id');
            $table->dropColumn('customer_state_id');
            $table->dropColumn('company_state_id');
            $table->dropColumn('estimate_no');
            $table->dropColumn('reference');
            $table->dropColumn('estimate_date');
            $table->dropColumn('expiry_date');
            $table->dropColumn('subtotal');
            $table->dropColumn('total_cgst_amount');
            $table->dropColumn('total_sgst_amount');
            $table->dropColumn('total_igst_amount');
            $table->dropColumn('addless_amount');
            $table->dropColumn('net_amount');
            $table->dropColumn('company_id');
            $table->dropColumn('user_id');
            $table->dropColumn('sales_person_id');
        });
    }
}
