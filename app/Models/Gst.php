<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gst extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'status', 'company_id'];

    protected static $logAttributes = ['name', 'status', 'company_id'];
}
