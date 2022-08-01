<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageTopFieldToProposalTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->float('page_top_margin', 8, 1)->default(15.5);
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
            $table->dropColumn('page_top_margin');
        });
    }
}
