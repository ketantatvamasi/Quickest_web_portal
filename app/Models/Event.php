<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [ 'company_id', 'estimate_id', 'user_id', 'notes', 'event_type','start_date','end_date','class_name','next_follow_up'];
}
