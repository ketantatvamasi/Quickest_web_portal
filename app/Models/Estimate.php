<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $table = 'estimates';
    protected $fillable = ['customer_name', 'customer_id', 'customer_state_id', 'company_state_id', 'estimate_no', 'reference', 'estimate_date', 'expiry_date', 'subtotal', 'total_cgst_amount', 'total_sgst_amount', 'total_igst_amount', 'addless_amount', 'net_amount','company_id','user_id','sales_person_id','item_rate_are','customer_notes','term_condition','customer_address','addless_title','status','est_cover_page_title','est_cover_page_content','est_cover_page_footer_one','est_cover_page_footer_two','est_aboutus_title','est_aboutus_content','est_term_condition_title','est_term_condition_content','product_id','pdf_cover_page_flg','pdf_about_us_flg','pdf_product_flg','pdf_est_flg','pdf_terms_flg','pdf_testimonial_flg','pdf_thank_you_flg','est_cover_page_title_div','est_cover_page_content_div','est_cover_page_footer_one_div','est_cover_page_footer_two_div','est_aboutus_title_div','est_aboutus_content_div','est_term_condition_title_div','est_term_condition_content_div','testimonial_id','tilt','azumuth','no_of_panel','panel_wattage'];
}
