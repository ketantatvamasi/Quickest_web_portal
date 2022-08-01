<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [ 'name', 'sortname', 'phonecode', 'description', 'status', 'user_id', 'company_id'];

    protected static $logAttributes = ['name', 'sortname', 'phonecode', 'description', 'status', 'user_id', 'company_id'];
}
