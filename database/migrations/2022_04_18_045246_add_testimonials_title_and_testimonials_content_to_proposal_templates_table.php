<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestimonialsTitleAndTestimonialsContentToProposalTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->text('testimonials_title')->nullable()->after('terms_content');
            $table->text('testimonials_content')->nullable()->after('terms_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->dropColumn('testimonials_title');
            $table->dropColumn('testimonials_content');
        });
    }
}
