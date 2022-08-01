<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->user()->status == 'Approved') {
            return $next($request);
        }

        if (auth()->user()->status == 'New') {
            return redirect()->route('profile')->with('new','Please fill below field');
        }

        if (auth()->user()->status == 'Pending') {
            return redirect()->route('profile')->with('pending','You have registered successfully. Your account will we actived soon.');
        }
        if (auth()->user()->status == 'Rejected') {
            return redirect()->to('/profile')->with('rejected','Your account is rejected');
        }
        return redirect()->route('profile');
    }



}
