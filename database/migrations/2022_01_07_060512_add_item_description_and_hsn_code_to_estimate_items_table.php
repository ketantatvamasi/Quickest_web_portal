<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemDescriptionAndHsnCodeToEstimateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('item_name');
            $table->renameColumn('discription', 'item_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_items', function (Blueprint $table) {
            $table->dropColumn('hsn_code');
            $table->renameColumn('item_description', 'discription');
        });
    }
}
