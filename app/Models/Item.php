<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [ 'name', 'description', 'item_type', 'unit_id', 'hsn_code','tax_preference','inter_state','intra_state','sales_flag','purchase_flag','sale_price','cost_price','status','user_id', 'company_id'];
}
