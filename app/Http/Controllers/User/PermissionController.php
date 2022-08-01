<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables,Auth,DB,LogActivity;
use App\Models\{User_permissions as permissions, User_roles as roles};

class PermissionController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
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
            $totalRecords = permissions::select('count(*) as allcount')->where(function ($query) use ($name, $status) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('permissions_name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('status', '=', $status);
                    });
                }
            })->count();
            $totalRecordswithFilter = permissions::select('count(*) as allcount')->where('permissions_name', 'like', '%' . $search_arr . '%')->where(function ($query) use ($name, $status) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('permissions_name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('status', '=', $status);
                    });
                }
            })->count();
            $records = DB::table('user_permissions')
                ->where(function ($query) use ($name, $status) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('permissions_name', '=', $name);
                        });
                    }
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('status', '=', $status);
                        });
                    }
                })
                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('permissions_name', 'like', '%' . $search_arr . '%');
                    });
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();
            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $name = $record->permissions_name;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "permissions_name" => $name,
                    "action" => $id,
                );
            }
            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordswithFilter,
                "data" => $data
            );
            return json_encode($response);
        }
        return view('permission.view');
    }

    public function getList(Request $request){
        $data  = Permission::get();

        return Datatables::of($data)
                ->addColumn('roles', function($data){
                    $roles = $data->roles()->get();
                    $badges = '';
                    foreach ($roles as $key => $role) {
                        $badges .= '<span class="">'.$role->name.'</span>,';
                    }
                    return $badges;
                })
                ->addColumn('action', function($data){
                    return '<div class="table-actions">
                                <a href="'.url('permission-delete/'.$data->id).'"><i class="uil-trash-alt"></i></a>
                            </div>';
                })
                ->rawColumns(['roles','action'])
                ->make(true);
    }

    public function create(){
        try{
            $roles = roles::get();
            $pageData = [
                'page_menu'=>"Permission",
                'page_title'=>"Permission",
            ];
            return view('permission.create', compact('roles','pageData'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'permissions_name' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{
            if(!empty($request->roles)){
                $permission = permissions::create([
                    'permissions_name' => $request->permissions_name,
                    'slug' => str_replace(' ', '_', strtolower($request->permissions_name)),
                    'role_id' => implode(",",$request->roles)
                ]);
            }else{
                $permission = permissions::create([
                    'permissions_name' => $request->permissions_name
                ]);
            }
            return redirect('admin/permissions')->with('success', 'Permission created succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id){
        $role  = Role::where('id',$id)->first();
        if($role){
            $role_permission = $role->permissions()->pluck('id')->toArray();
            $permissions = Permission::pluck('name','id');
            $pageData = [
                'page_menu'=>"Role",
                'page_title'=>"Add Role",
            ];
            return view('permission.edit', compact('role','role_permission','permissions','pageData'));
        }else{
            return redirect('404');
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'id'   => 'required'
        ]);        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{            
            $role = Role::find($request->id);
            $update = $role->update(['name' => $request->role]);
            $role->syncPermissions($request->permissions);
            return redirect('permissions')->with('success', 'Permission info updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function delete($id){
        $permission   = Permission::find($id);
        if($permission){
            $delete = $permission->delete();
            $perm   = $permission->roles()->delete();
            return redirect('permissions')->with('success', 'Permission deleted!');
        }else{
            return redirect('404');
        }
    }
}
