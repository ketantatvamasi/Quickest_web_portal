<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPersonPerformances extends Model
{
    use HasFactory;
    protected $fillable = [ 'performance_date', 'user_id', 'total_task', 'completed_task', 'daily_performance', 'company_id'];
}
