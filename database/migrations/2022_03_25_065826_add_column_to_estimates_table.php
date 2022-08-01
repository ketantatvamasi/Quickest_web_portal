<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->text('est_cover_page_title')->nullable();
            $table->text('est_cover_page_content')->nullable();
            $table->text('est_cover_page_footer_one')->nullable();
            $table->text('est_cover_page_footer_two')->nullable();
            $table->text('est_aboutus_title')->nullable();
            $table->text('est_aboutus_content')->nullable();
            $table->text('est_term_condition_title')->nullable();
            $table->text('est_term_condition_content')->nullable();
            $table->string('product_id')->nullable();
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
            $table->dropColumn('est_cover_page_title');
            $table->dropColumn('est_cover_page_content');
            $table->dropColumn('est_cover_page_footer_one');
            $table->dropColumn('est_cover_page_footer_two');
            $table->dropColumn('est_aboutus_title');
            $table->dropColumn('est_aboutus_content');
            $table->dropColumn('est_term_condition_title');
            $table->dropColumn('est_term_condition_content');
            $table->dropColumn('product_id');
        });
    }
}
