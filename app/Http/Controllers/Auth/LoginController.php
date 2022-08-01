<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

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
//        $this->middleware('guest')->except('logout');
    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }

//    public function login(Request $request)
//    {
//        $request->validate([
//            'email' => 'required',
//            'password' => 'required',
//        ]);
//
//        $credentials = $request->only('email', 'password');
//        if(Auth::attempt($credentials)){
//            return redirect()->route('dashboard');
//        }
//        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
//    }

//    public function logout(Request $request) {
//        Auth::logout();
//        return redirect('/login');
//    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request,$user)
    {

        $roleData = DB::table('roles')->where('id','=',$user->role_id)->select('name as role_name')->first();
        $role_name="Founder";
        if($roleData)
            $role_name = $roleData->role_name;

            $abc = $this->multilevel_categories($user->id);
            $array = $this->nestedToSingle($abc);
            if(empty($array))
                $array[]=$user->id;
            Session::put('get_data_by_id',implode(',',$array));
            Session::put('role_name',$role_name);


//        $rawSql = <<<SQL
//                    with recursive cte as ( select id, user_id, 1 lvl from users union all select c.id, t.user_id, lvl + 1 from cte c inner join users t on t.id = c.user_id ) select id,connect (id,group_concat(user_id order by lvl) as all_parents) abc from cte group by id;
//                    SQL;

//        $results = DB::select($rawSql);
//        dd($results);
        return redirect()->route('dashboard');
    }


    public function multilevel_categories($parent_id=0){
        $query = DB::table('users')->select('id')->where('user_id',$parent_id)->get();

        $catData=[];
        if($query->count()>0){
            foreach($query as $row)
            {
                $catData[]=[
                    'id'=>$row->id,
                    'nested_categories'=>$this->multilevel_categories($row->id)
                ];
            }
            return $catData;

        }else{
            return $catData=[];
        }
    }

    public function nestedToSingle(array $array)
    {
        $singleDimArray = [];

        foreach ($array as $item) {

            if (is_array($item)) {
                $singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));

            } else {
                $singleDimArray[] = $item;
            }
        }

        return $singleDimArray;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Session::flush();
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
        return redirect()->to( '/' );
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}
