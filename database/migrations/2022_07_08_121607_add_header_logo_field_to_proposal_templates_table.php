<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeaderLogoFieldToProposalTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->unsignedInteger('header_logo_left')->default(0);
            $table->unsignedInteger('header_logo_top')->default(0);
            $table->unsignedInteger('header_logo_size')->default(0);
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
            $table->dropColumn('header_logo_left');
            $table->dropColumn('header_logo_top');
            $table->dropColumn('header_logo_size');
        });
    }
}
