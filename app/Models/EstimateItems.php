<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateItems extends Model
{
    use HasFactory;

    protected $table = 'estimate_items';
    protected $fillable = ['estimate_id', 'item_id', 'item_name', 'quantity', 'price', 'discount', 'discount_flag', 'gst_per', 'cgst_amount', 'sgst_amount', 'igst_amount', 'total', 'company_id', 'user_id	','hsn_code','item_description'];
}
