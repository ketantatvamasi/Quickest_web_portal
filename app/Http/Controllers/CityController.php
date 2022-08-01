<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use LogActivity;
use Auth;

class CityController extends Controller
{
    public function index(Request $request)
    {
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
            $totalRecords = City::select('id')->where(function ($query) use ($name, $status) {
//                if ($name != '') {
//                    $query->Where(function ($query) use ($name) {
//                        $query->where('cities.name', '=', $name);
//                    });
//                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('cities.status', '=', $status);
                    });
                }
            })->count();
            $totalRecordswithFilter = City::select('id')->join('states', 'cities.state_id', '=', 'states.id')->where(function ($query) use ($name, $status) {
//                if ($name != '') {
//                    $query->Where(function ($query) use ($name) {
//                        $query->where('cities.name', '=', $name);
//                    });
//                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('cities.status', '=', $status);
                    });
                }
            })

            ->where(function ($query) use ($search_arr) {
                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('states.name', 'like', '%' . $search_arr . '%');
                });

                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('cities.name', 'like', '%' . $search_arr . '%');
                });

                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('cities.description', 'like', '%' . $search_arr . '%');
                });
            })->count();


//            DB::enableQueryLog();
            $records = DB::table('cities')
                ->join('states', 'cities.state_id', '=', 'states.id')
                ->where(function ($query) use ($name, $status) {
                    /*if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('cities.name', '=', $name);
                        });
                    }*/
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('cities.status', '=', $status);
                        });
                    }
                })

                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('states.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('cities.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('cities.description', 'like', '%' . $search_arr . '%');
                    });
                })

                ->select('cities.name','cities.id','cities.description','cities.status', 'states.name as state_name')
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();

//            dd(DB::getQueryLog());

            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $name = $record->name;
                $description = $record->description;
                $status = $record->status;
                $state_name = $record->state_name;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "state_name" => $state_name,
                    "name" => $name,
                    "description" => $description,
                    "status" => $status,
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
        $states = State::select(["name", "id"])->where('status','=',0)->get();
        return view('city',compact('states'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $user = Auth::user();
            $input['user_id'] = $user->id;
            $state_id = $input['state_id'];
            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            if (City::where('name', '=', $input['name'])->where(function ($query) use ($id,$state_id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
                $query->Where(function ($query) use ($state_id) {
                    $query->where('state_id', '=', $state_id);
                });
            })->first()) {
                return response()->json(['success' => 'State exists!'], 409);
            }
            if ($id == 0) {
                $activityLogMsg = 'State created by ' . $user->name;
                $city = City::create($input);
            } else {
                $city = City::find($id)->update($input);
                $activityLogMsg = 'State updated by ' . $user->name;
            }

            // Add activity logs
            $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            LogActivity::addToLog($activityLogMsg, $input);

            return response()->json(['success' => 'State Saved!'], 201);
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $id = Crypt::decrypt($input['id']);
            $validator = Validator::make($input, [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);
            }
            $user = Auth::user();

            $city = City::find($id)->toArray();

            if (is_null($city)) {
                return response()->json(['success' => 'State not found!'], 422);
            }
            $city['id'] = Crypt::encrypt($city['id']);
            return response()->json([
                "success" => true,
                "message" => "State retrieved successfully.",
                "data" => $city
            ], 201);
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
            $city = City::whereIn('id', $id)->delete();

            LogActivity::addToLog('State deleted by ' . $user->name, $id);
            return response()->json(['success' => 'State Deleted!'], 201);
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
            if (!City::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'State exists!'], 422);
            }
            $city = City::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = ($input['status'] == 0) ? 'Active' : 'Deactive';
            LogActivity::addToLog('State status updated by ' . $user->name, $data);

            return response()->json(['success' => 'State status updated!'], 201);
        }
    }
}
