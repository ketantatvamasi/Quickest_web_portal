<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdfTestimonialFlgAndPdfToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('pdf_testimonial_flg')->default(false)->after('pdf_terms_flg');
            $table->boolean('pdf_thank_you_flg')->default(false)->after('pdf_terms_flg');
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
            $table->dropColumn('pdf_testimonial_flg');
            $table->dropColumn('pdf_thank_you_flg');
        });
    }
}
