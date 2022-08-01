<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPdfFlagToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('pdf_cover_page_flg')->default(false);
            $table->boolean('pdf_about_us_flg')->default(false);
            $table->boolean('pdf_product_flg')->default(false);
            $table->boolean('pdf_est_flg')->default(false);
            $table->boolean('pdf_terms_flg')->default(false);
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
            $table->dropColumn('pdf_cover_page_flg');
            $table->dropColumn('pdf_about_us_flg');
            $table->dropColumn('pdf_product_flg');
            $table->dropColumn('pdf_est_flg');
            $table->dropColumn('pdf_terms_flg');
        });
    }
}
