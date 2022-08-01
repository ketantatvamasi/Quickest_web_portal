<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimatesTestimonials extends Model
{
    use HasFactory;
    protected $table = 'estimates_testimonials';
    protected $fillable = ['estimate_id', 'testimonial_id'];
}
