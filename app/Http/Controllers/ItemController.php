<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LogActivity;
use Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company_id = ($user->company_id)? $user->company_id : $user->id;
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
            $totalRecords = Item::select('count(id) as allcount')->where(function ($query) use ($name, $status) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('status', '=', $status);
                    });
                }
            })->where('company_id',$company_id)->count();
            $totalRecordswithFilter = Item::select('count(id) as allcount')->join('units', 'items.unit_id', '=', 'units.id')->where('items.company_id', $company_id)->where(function ($query) use ($name, $status) {
                if ($name != '') {
                    $query->Where(function ($query) use ($name) {
                        $query->where('items.name', '=', $name);
                    });
                }
                if ($status != '') {
                    $query->where(function ($query) use ($status) {
                        $query->where('items.status', '=', $status);
                    });
                }
            })

            ->where(function ($query) use ($search_arr) {
                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('units.name', 'like', '%' . $search_arr . '%');
                });

                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('items.name', 'like', '%' . $search_arr . '%');
                });

                $query->orWhere(function ($query) use ($search_arr) {
                    $query->where('items.description', 'like', '%' . $search_arr . '%');
                });
            })->where('items.company_id',$company_id)->count();


//            DB::enableQueryLog();
            $records = DB::table('items')
                ->where('items.company_id',$company_id)
                ->join('units', 'items.unit_id', '=', 'units.id')
                ->where(function ($query) use ($name, $status) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('items.name', '=', $name);
                        });
                    }
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('items.status', '=', $status);
                        });
                    }
                })

                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('units.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('items.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('items.description', 'like', '%' . $search_arr . '%');
                    });
                })

                ->select('items.*', 'units.name as unit_name')
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
                $unit_name = $record->unit_name;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "unit_name" => $unit_name,
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
        $units = Unit::select(["name", "id"])->where('status','=',0)->where('company_id', $company_id)->get();
        return view('item',compact('units'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'unit_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $user = Auth::user();
            $input['user_id'] = $user->id;
            $input['sale_price'] = 0+$input['sale_price'];
            $input['cost_price'] = 0+$input['cost_price'];
            $input['company_id'] = ($user->company_id)? $user->company_id : $user->id;
            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            if (Item::where('name', '=', $input['name'])->where('company_id', $input['company_id'])->where(function ($query) use ($id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
            })->first()) {
                return response()->json(['success' => 'Item exists!'], 409);
            }
            if ($id == 0) {
                $activityLogMsg = 'Item created by ' . $user->name;
                $country = Item::create($input);
            } else {
                $country = Item::find($id)->update($input);
                $activityLogMsg = 'Item updated by ' . $user->name;
            }

            // Add activity logs
            $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            LogActivity::addToLog($activityLogMsg, $input);

            return response()->json(['success' => 'Item Saved!'], 201);
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

            $item = Item::find($id)->toArray();

            if (is_null($item)) {
                return response()->json(['success' => 'Item not found!'], 422);
            }
            $item['id'] = Crypt::encrypt($item['id']);
            return response()->json([
                "success" => true,
                "message" => "Item retrieved successfully.",
                "data" => $item
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
            $country = Item::whereIn('id', $id)->delete();

            LogActivity::addToLog('Item deleted by ' . $user->name, $id);
            return response()->json(['success' => 'Item Deleted!'], 201);
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
            if (!Item::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'Item exists!'], 422);
            }
            $item = Item::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = ($input['status'] == 0) ? 'Active' : 'Deactive';
            LogActivity::addToLog('Item status updated by ' . $user->name, $data);

            return response()->json(['success' => 'Item status updated!'], 201);
        }
    }

    public function itemAutocomplete(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            $user = Auth::user();
            $company_id = ($user->company_id)? $user->company_id : $user->id;
            $items = Item::select('id','name','sale_price','description','inter_state','intra_state','hsn_code')->where('items.company_id',$company_id)->where('name', 'LIKE', '%' . $search . '%')->where('status', 0)->get();
            $response = array();
            foreach($items as $item){
                $response[] = array("value"=>$item->id,"label"=>$item->name, "desc"=>$item->description,"sale_price"=>$item->sale_price,"inter_state"=>$item->inter_state,"intra_state"=>$item->intra_state,"hsn_code" => $item->hsn_code);
            }

            return response()->json($response);
            return response()->json($result);
        }
    }
}
