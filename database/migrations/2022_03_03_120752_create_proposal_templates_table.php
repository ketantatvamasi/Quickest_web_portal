<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name',50)->nullable();
            $table->string('theme_color_one',10)->nullable();
            $table->string('theme_color_two',10)->nullable();
            $table->string('header_logo',205)->nullable();
            $table->string('cover_img',255)->nullable();
            $table->string('logo_dimension_one',10)->nullable();
            $table->string('logo_dimension_img',10)->nullable();
            $table->text('cover_title')->nullable();
            $table->text('cover_content')->nullable();
            $table->text('cover_footer_one')->nullable();
            $table->text('cover_footer_two')->nullable();
            $table->string('aboutas_img',255)->nullable();
            $table->string('aboutas_logo_dimension',10)->nullable();
            $table->text('aboutas_title')->nullable();
            $table->text('aboutas_content')->nullable();
            $table->string('est_title',20)->nullable();
            $table->string('est_logo_dimension',10)->nullable();
            $table->string('item_table_no',50)->nullable();
            $table->string('item_table_item',50)->nullable();
            $table->string('item_table_description',50)->nullable();
            $table->string('item_table_hsn',50)->nullable();
            $table->string('item_table_qty',50)->nullable();
            $table->string('item_table_rate',50)->nullable();
            $table->string('item_table_discount',50)->nullable();
            $table->string('item_table_cgst',50)->nullable();
            $table->string('item_table_sgst',50)->nullable();
            $table->string('item_table_igst',50)->nullable();
            $table->string('item_table_total',50)->nullable();
            $table->string('est_bank_label',20)->nullable();
            $table->text('est_bank_details')->nullable();
            $table->string('est_term_condition_lable',20)->nullable();
            $table->text('est_term_condition_details')->nullable();
            $table->string('est_signature_lable',20)->nullable();
            $table->string('est_signature_img',255)->nullable();
            $table->boolean('est_item_no_flg')->default(0);
            $table->boolean('est_item_description_flg')->default(0);
            $table->bigInteger('company_id')->unsignedBigInterger()->default(0);
            $table->bigInteger('user_id')->unsignedBigInterger()->default(0);
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
        Schema::dropIfExists('proposal_templates');
    }
}
