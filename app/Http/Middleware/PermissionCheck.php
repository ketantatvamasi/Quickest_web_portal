<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermissionCheck as check;

class PermissionCheck
{
    public function handle(Request $request, Closure $next,$permissoin){


        if(!is_null($permissoin)){
            if(check::check_permission($permissoin)){
                return $next($request);
            }
        }
        return redirect("dashboard");
    }
}
