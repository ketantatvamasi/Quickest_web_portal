<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'description', 'status', 'user_id', 'company_id'];

    protected static $logAttributes = ['name', 'description', 'status', 'user_id', 'company_id'];
}
