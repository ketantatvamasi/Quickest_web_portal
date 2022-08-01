<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateAutoNumber extends Model
{
    use HasFactory;
    protected $fillable = ['estimate_prefix', 'estimate_next_no', 'company_id'];

}
