<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{admin\CompanyCategory, City, Country, State, User};
use App\Models\{Permission, Role,UserPermission};
use Illuminate\Support\Facades\Crypt;
use LogActivity;
use Auth,Session,DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            $input = $request->all();
            ## Read value
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc

            // Fetch records
            $name = $request->get('name');
            $status = $request->get('status');
            // Total records

           /* $totalRecords = DB::table('users_views as u1')->leftJoin('users_views as u2','u1.user_id','=', 'u2.id')->leftJoin('roles as r','u1.role_id','=', 'r.id')->select('count(u1.id) as allcount')->where(function ($query) use ($name, $status,$user) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('u1.name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('u1.status', '=', $status);
                    });
                }

            })
            ->where(function ($query) use ($user) {
                $query->whereRaw('u1.user_id IN  (' . Session::get("get_data_by_id") . ')');
                $query->orWhere('u1.user_id', $user->id);
            })
            ->whereNotNull('u1.company_id')
//            ->where('company_id',$company_id)
//            ->orWhere('user_id',Auth::user()->id)
            ->count();*/

            /*$totalRecordswithFilter = DB::table('users_views as u1')->leftJoin('users_views as u2','u1.user_id','=', 'u2.id')->leftJoin('roles as r','u1.role_id','=', 'r.id')->select('count(u1.id) as allcount')->where('u1.name', 'like', '%' . $search_arr . '%')->where(function ($query) use ($name, $status) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('u1.name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('status', '=', $status);
                    });
                }
            })
            ->where(function ($query) use ($user) {
                $query->whereRaw('u1.user_id IN  (' . Session::get("get_data_by_id") . ')');
                $query->orWhere('u1.user_id', $user->id);
            })
            ->whereNotNull('u1.company_id')
//                ->where('company_id',$company_id)
//            ->orWhere('company_id',Auth::user()->id)
//            ->orWhere('user_id',Auth::user()->id)
            ->count();*/
            DB::enableQueryLog();
            $records = DB::table('users_views as u1')->leftJoin('users_views as u2','u1.user_id','=', 'u2.id')->leftJoin('roles as r','u1.role_id','=', 'r.id')->where('u1.company_id',$company_id)->whereNotNull('u1.company_id')
                ->where(function ($query) use ($name, $status) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('u1.name', '=', $name);
                        });
                    }
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('u1.status', '=', $status);
                        });
                    }
                })
                ->where(function ($query) use ($search_arr) {
                    if ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('u1.name', 'like', '%' . $search_arr . '%');
                    });}
                    if ($search_arr) {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('u1.email', 'like', '%' . $search_arr . '%');
                        });
                    }


                })
                ->where(function ($query) use ($user) {
                    $query->whereRaw('u1.user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('u1.user_id', $user->id);
                })
                ->whereNotNull('u1.company_id')
//                ->where(function ($query) use ($user) {
//                    if(!is_null($user->company_id))
//                        $query->where('user_id',Auth::user()->id);
//                })

//                ->where('company_id',$company_id)
//                ->orWhere('company_id',Auth::user()->id)

                ->select(['u1.id','u1.name','u1.mobile_no','u1.email','u2.name AS assign_user','r.name as user_role'])
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();
//            dd(DB::getQueryLog());
            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $user_role = $record->user_role;
                $assign_user = $record->assign_user;
                $name = $record->name;
                $email = $record->email;
                $mobile_no = $record->mobile_no;
//                $status = $record->status;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "user_role" => $user_role,
                    "assign_user" => $assign_user,
                    "name" => $name,
                    "email" => $email,
                    "mobile_no" => $mobile_no,
                    "status" => $status,
                    "action" => $id,
                );
            }
            $totalRecords=0;
            $totalRecordswithFilter=0;
            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordswithFilter,
                "data" => $data
            );
            return json_encode($response);
        }
        return view('user.user');
    }
    public function create(){
        $user = Auth::user();
        $user_list = [];
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $roles = Role::query()->where('company_id',$company_id)->orWhere('display_flag', 1)->get();
        $permissions = Permission::get();
        $countries = Country::select(["name", "id"])->where('status','=',0)->get();

        if($user->company_id==null){
            $user_list = User::select(["name", "id","email"])->where('status','Approved')->where('company_id',$company_id)->get();
        }
        $company_categories = CompanyCategory::select(["name", "id"])->where('status','=',0)->get();
        $pageData = [
            'page_menu'=>"Permission",
            'page_title'=>"Permission",
        ];
        return view('user.create', compact('roles','pageData','permissions','countries','company_categories','user_list'));
    }

    public function store(Request $request){
//        return $request;
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'password'  => 'required',
            'role'      => 'required'
        ]);
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{
            $input = $request->all();

            $company_id = (Auth::user()->company_id)? Auth::user()->company_id : Auth::user()->id;

            if (User::query()->where('email', '=', $input['email'])->where(function ($query) use ($company_id) {
                $query->Where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                });
            })->first()) {
                return response()->json(['success' => 'User exists!'], 409);
            }
            if(!empty($request->user_id)){
                $user_id = $request->user_id;
            }else{
                $user_id = Auth::user()->id;
            }
            $users = User::create([
                'name'     => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country_id'=> $request->country_id,
                'state_id'=> $request->state_id,
                'city_id'=> $request->city_id,
                'address'=> $request->address,
                'pincode'=> $request->pincode,
                'company_id' => $company_id,
                'user_id' => $user_id,
                'mobile_no'=> $request->mobile_no,
                'user_role' => "1",
                'role_id'=>$request->role,
                'permissions' => null,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 'Approved',
                'is_owner' =>0
            ]);
            $insert_id = $users->id;

            if(!empty($input['data'])) {
                $input['data'] = array_map(function ($arr) use ($insert_id, $company_id) {
                    return $arr + ['user_id' => $insert_id, 'company_id' => $company_id];
                }, $input['data']);

                $permission = UserPermission::query()->insert($input['data']);
            }
            return response()->json(['success' => 'User created successfully!'], 201);
//            return redirect("user")->withSuccess('User created successfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id){
        $id = ($id) ? Crypt::decrypt($id) : $id;
        $user = User::find($id);
        $company_id = (Auth::user()->company_id)? Auth::user()->company_id : Auth::user()->id;
        if($user){
            //print_r($user->id);exit;
            $countries = Country::select(["name", "id"])->where('status','=',0)->get();
            $company_categories = CompanyCategory::select(["name", "id"])->where('status','=',0)->get();

            $roles = Role::query()->where('company_id',$company_id)->orWhere('display_flag', 1)->get();
            $permissions = Permission::get();
            $user_permissions = UserPermission::query()->where('user_id',$id)->where('company_id',$company_id)->pluck('permission_id')->toArray();
            $user_list = [];
            if(Auth::user()->company_id==null){
                $user_list = User::select(["name", "id","email"])->where('status','=','Approved')->where('company_id','=',Auth::user()->id)->get();
            }
            return view('user.edit',compact('user','roles','permissions','user_permissions','countries','company_categories','user_list'));
        }else{
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'        => 'required',
            'name'      => 'required',
            'email'     => 'required',
            'role'      => 'required'
        ]);
        if($validator->fails()) {
            print_r($validator->messages()->first());exit;
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{
            $input = $request->all();
            $id = $input['id'];
            $user = User::find($id);
            $company_id = (Auth::user()->company_id)? Auth::user()->company_id : Auth::user()->id;

            if (User::query()->where('email', '=', $input['email'])->where('company_id', $company_id)->where(function ($query) use ($id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
            })->first()) {
                return response()->json(['success' => 'Role exists!'], 409);
            }

            if(!empty($request->user_id)){
                $user_id = $request->user_id;
            }else{
                $user_id = Auth::user()->id;
            }
            $update = $user->update([
                'name'     => $request->name,
                'email' => $request->email,
                'country_id'=> $request->country_id,
                'mobile_no'=> $request->mobile_no,
                'state_id'=> $request->state_id,
                'city_id'=> $request->city_id,
                'address'=> $request->address,
                'pincode'=> $request->pincode,
//                'company_id' => Auth::user()->id,
                'user_id' => $user_id,
                'user_role' => "1",
                'role_id'=>$request->role,
                'permissions' => null,
            ]);
            if(!empty($input['data'])) {
                $input['data'] = array_map(function ($arr) use ($id, $company_id) {
                    return $arr + ['user_id' => $id, 'company_id' => $company_id];
                }, $input['data']);

                UserPermission::query()->where('user_id',$id)->delete();

                $permission = UserPermission::query()->insert($input['data']);

            }else{
                UserPermission::query()->where('user_id',$id)->delete();
            }
            return response()->json(['success' => 'User updated successfully!'], 201);
//            return redirect()->route('user.index');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }
            $user = Auth::user();
            $id = [];
            foreach (explode(",", $request->id) as $value) {
                $id[] = Crypt::decrypt($value);
            }
            $unit = User::whereIn('id', $id)->delete();
            LogActivity::addToLog('Unit deleted by ' . $user->name, $id);
            return response()->json(['success' => 'User Deleted!'], 201);
        }
    }

    public function editStatus(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }
            $user = Auth::user();
            $id = [];
            foreach (explode(",", $input['id']) as $value) {
                $id[] = Crypt::decrypt($value);
            }
            if (!User::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'User exists!'], 422);
            }
            $unit = User::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = ($input['status'] == 0) ? 'Active' : 'Deactive';
            LogActivity::addToLog('User status updated by ' . $user->name, $data);

            return response()->json(['success' => 'User status updated!'], 201);
        }
    }

    public function getPermission(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $permissions = Role::query()->find($input['id'])->permissions->pluck('id')->toArray();
//            $permissions = permissions::whereRaw('FIND_IN_SET('.$input['id'].',role_id)')->where('role_id','!=',null)->get();
            if (is_null($permissions)) {
                return response()->json(['success' => 'Permissions not found!'], 422);
            }
            return response()->json([
                "success" => true,
                "message" => "Permissions retrieved successfully.",
                "data" => $permissions
            ], 201);
        }
    }
    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)
            ->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)
            ->get(["name","id"]);
        return response()->json($data);
    }

}
