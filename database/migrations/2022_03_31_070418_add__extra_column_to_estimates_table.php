<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->text('est_cover_page_title_div')->nullable();
            $table->text('est_cover_page_content_div')->nullable();
            $table->text('est_cover_page_footer_one_div')->nullable();
            $table->text('est_cover_page_footer_two_div')->nullable();
            $table->text('est_aboutus_title_div')->nullable();
            $table->text('est_aboutus_content_div')->nullable();
            $table->text('est_term_condition_title_div')->nullable();
            $table->text('est_term_condition_content_div')->nullable();
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
            $table->dropColumn('est_cover_page_title_div');
            $table->dropColumn('est_cover_page_content_div');
            $table->dropColumn('est_cover_page_footer_one_div');
            $table->dropColumn('est_cover_page_footer_two_div');
            $table->dropColumn('est_aboutus_title_div');
            $table->dropColumn('est_aboutus_content_div');
            $table->dropColumn('est_term_condition_title_div');
            $table->dropColumn('est_term_condition_content_div');
        });
    }
}
