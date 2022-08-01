<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'client_name_one', 'description_one', 'rating_one', 'image_one' ,'client_name_two', 'description_two', 'rating_two', 'image_two' ,'client_name_three', 'description_three', 'rating_three', 'image_three', 'status', 'user_id', 'company_id','is_default'];
}
