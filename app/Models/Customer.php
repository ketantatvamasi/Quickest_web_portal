<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [ 'user_id', 'customer_type', 'name', 'email', 'phone_no','address','pincode','country_id','state_id','city_id','profile_icon','description','status','gst_no', 'company_id'];
}
