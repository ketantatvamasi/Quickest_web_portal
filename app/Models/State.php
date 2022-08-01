<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'states';
    protected $fillable = [ 'name', 'country_id', 'description', 'status', 'user_id', 'company_id'];

    protected static $logAttributes = ['name', 'country_id', 'description', 'status', 'user_id', 'company_id'];
}
