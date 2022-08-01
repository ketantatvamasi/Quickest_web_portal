<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplates extends Model
{
    use HasFactory;

    protected $table = 'proposal_templates';
    protected $fillable = ['template_name','theme_color_one','theme_color_two','header_logo','cover_img','logo_dimension_one','logo_dimension_img','cover_title','cover_content','cover_footer_one','cover_footer_two','aboutas_img','aboutas_logo_dimension','aboutas_title','aboutas_content','product_title','product_content','terms_title','terms_content','est_title','est_logo_dimension','item_table_no','item_table_item','item_table_description','item_table_hsn','item_table_qty','item_table_rate','item_table_discount','item_table_cgst','item_table_sgst','item_table_igst','item_table_total','est_bank_label','est_bank_details','est_term_condition_lable','est_term_condition_details','est_signature_lable','est_signature_img','est_item_no_flg','est_item_description_flg','testimonials_title','testimonials_content','company_id','user_id','header_logo_left','header_logo_top','header_logo_size','page_top_margin','thank_you_img'];

//    public function getCoverTitleAttribute($value)
//    {
//        $a=htmlentities($value);
//        return htmlentities($a);
//    }

//    public function setCoverTitleAttribute($value)
//    {
//        $this->attributes['cover_title'] = html_entity_decode($value);
//    }
}
