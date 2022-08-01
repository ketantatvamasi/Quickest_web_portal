<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = [ 'name', 'state_id', 'description', 'status', 'user_id', 'company_id'];

    protected static $logAttributes = ['name', 'state_id', 'description', 'status', 'user_id', 'company_id'];
}
