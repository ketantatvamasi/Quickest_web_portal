<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.adminlogin');
    }

     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required',
             'password' => 'required',
         ]);

         $credentials = $request->only('email', 'password');
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
            return redirect()->route('dashboard');
        }
        //return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
        return redirect('admin')->with('error', 'Oppes! You have entered invalid credentials');
     }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/admin');
    }
}
