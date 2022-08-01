<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_permissions extends Model
{
    use HasFactory;
    protected $fillable = [ 'permissions_name', 'role_id', 'status','slug'];
}
