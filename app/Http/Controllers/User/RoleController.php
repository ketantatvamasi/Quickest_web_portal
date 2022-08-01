<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables,Auth,DB,LogActivity;
use Illuminate\Support\Str;
use App\Models\{Permission, Role,RolePermission};

class RoleController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
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
            $totalRecords = Role::select('COUNT(id) as allcount')->where(function ($query) use ($name) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('name', '=', $name);
                    });
                }
            })
            ->where('company_id',$company_id)
            ->count();
            $totalRecordswithFilter = Role::select('count(*) as allcount')->where('name', 'like', '%' . $search_arr . '%')->where(function ($query) use ($name) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('name', '=', $name);
                    });
                }
            })
            ->where('company_id',$company_id)
            ->count();
            $records = DB::table('roles')
                ->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('name', '=', $name);
                        });
                    }
                })
                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('name', 'like', '%' . $search_arr . '%');
                    });
                })

                ->where(function ($query) use ($company_id) {
                    $query->orWhere(function ($query) use ($company_id) {
                        $query->where('company_id',$company_id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('display_flag', 1);
                    });
                })
//                ->where('company_id',$company_id)
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();
            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $name = $record->name;
                $display_flag = $record->display_flag;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "name" => $name,
                    "display_flag" => $display_flag,
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
        return view('role.view');
    }

    public function create(){
        try{
            $permissions = Permission::query()->get();
            $pageData = [
                'page_menu'=>"Role",
                'page_title'=>"Add Role",
            ];
            return view('role.create', compact('permissions','pageData'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{
            $input = $request->all();
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;

            if (Role::query()->where('name', '=', $input['name'])->where(function ($query) use ($company_id) {
                $query->Where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                });
            })->first()) {
                return response()->json(['success' => 'Role exists!'], 409);
            }

            $role = Role::query()->create([
                'name' => $request->name,
                'slug' => Str::slug($input['name']),
                'company_id' => $company_id,
            ]);
            $insert_id = $role->id;

            if(!empty($input['data'])) {
                $input['data'] = array_map(function ($arr) use ($insert_id, $company_id) {
                    return $arr + ['role_id' => $insert_id, 'company_id' => $company_id];
                }, $input['data']);

                $permission = RolePermission::query()->insert($input['data']);
            }

            return response()->json(['success' => 'Role Saved!'], 201);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id){
        $roleId = Crypt::decrypt($id);
        $role  = Role::query()->where('id',$roleId)->first();

        if($role){
            $permissions = Permission::query()->select(['id','name'])->get();
            $role_permissions = Role::query()->find($roleId)->permissions->pluck('id')->toArray();
            $pageData = [
                'page_menu'=>"Role",
                'page_title'=>"Edit Role",
            ];
            return view('role.edit', compact('role','permissions','pageData','role_permissions'));
        }else{
            return redirect('404');
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id'   => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{
            $input = $request->all();
            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            $id = $input['id'];

            if (Role::query()->where('name', '=', $input['name'])->where('company_id', $company_id)->where(function ($query) use ($id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
            })->first()) {
                return response()->json(['success' => 'Role exists!'], 409);
            }

            $role = Role::query()->find($input['id'])->update([
                'name' => $input['name'],
                'slug' => Str::slug($input['name']),
                'company_id' => $company_id,
            ]);


            if(!empty($input['data'])) {
                $input['data'] = array_map(function ($arr) use ($id, $company_id) {
                    return $arr + ['role_id' => $id, 'company_id' => $company_id];
                }, $input['data']);

                    RolePermission::query()->where('role_id',$id)->delete();

                $permission = RolePermission::query()->insert($input['data']);

            }
            return response()->json(['success' => 'Role updated successfully!'], 201);
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
            Role::query()->whereIn('id', $id)->delete();

//            LogActivity::addToLog('Country deleted by ' . $user->name, $id);
            return response()->json(['success' => 'Country Deleted!'], 201);
        }
    }

//    public function delete($id){
//        $role   = Role::find($id);
//        if($role){
//            $delete = $role->delete();
//            $perm   = $role->permissions()->delete();
//
//            return redirect('roles')->with('success', 'Role deleted!');
//        }else{
//            return redirect('404');
//        }
//    }
}
