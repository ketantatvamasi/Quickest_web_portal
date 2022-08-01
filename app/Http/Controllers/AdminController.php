<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    use AuthenticatesUsers;
    public function index(){
        return view('admin.login');
    }

//    public function login(Request $request){
//        $credentials = $request->only('email','password');
//        if(Auth::guard('admin')->attempt($credentials)){
//            $user = Admin::query()->where('email',$request->email)->first();
//            Auth::guard('admin')->login($user);
//            return redirect()->route('admin.dashboard');
//        }
//        return redirect()->route('admin.login')->with('status','Failed to login process');
//
//    }

//    public function logout(Request $request)
//    {
//        // Auth::guard('admin')->logout();
//        // $request->session()->invalidate();
//        // return redirect()->guest(route( 'admin.login' ));
//
//        Auth::guard('admin')->logout();
//        return redirect()->route('admin.login');
//    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route( 'admin.login' );
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

}
