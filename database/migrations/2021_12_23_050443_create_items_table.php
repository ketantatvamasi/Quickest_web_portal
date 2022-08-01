<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('item_type', ['Goods', 'Service']);
            $table->integer('unit_id')->unsignedBigInteger()->default(0);
            $table->string('hsn_code')->nullable();
            $table->enum('tax_preference', ['Taxable', 'Non-Taxable']);
            $table->float('inter_state')->default(0.00);
            $table->float('intra_state')->default(0.00);
            $table->boolean('sales_flag')->default(false);
            $table->boolean('purchase_flag')->default(false);
            $table->decimal('sale_price', 10, 2)->default(0.00);
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->boolean('status')->default(false);
            $table->integer('user_id')->unsignedBigInteger()->default(0);
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
        Schema::dropIfExists('items');
    }
}
