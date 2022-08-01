<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Customer;
use App\Models\ViewCustomerData;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use LogActivity;

class   CustomerController extends Controller
{
    protected $logged_user = null;
    protected $company_id = 0;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_user = \Illuminate\Support\Facades\Auth::user();
            $this->company_id = ($this->logged_user->company_id) ? $this->logged_user->company_id : $this->logged_user->id;
            return $next($request);
        });
    }

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
            $customer_type = $request->get('customer_type');
            // Total records
            $totalRecords = Customer::select('count(id) as allcount')->where(function ($query) use ($name, $status,$customer_type) {
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
                if ($customer_type != '') {
                    $query->where(function ($query) use ($customer_type) {
                        $query->where('customer_type', $customer_type);
                    });
                }
            })->where('company_id',$this->company_id)->count();
            $totalRecordswithFilter = Customer::select('count(customers.id) as allcount')
                ->leftJoin('countries', 'customers.country_id', '=', 'countries.id')
                ->leftJoin('states', 'customers.state_id', '=', 'states.id')
                ->leftJoin('cities', 'customers.city_id', '=', 'cities.id')
                ->where(function ($query) use ($name, $status,$customer_type) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('customers.name', '=', $name);
                        });
                    }
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('customers.status', '=', $status);
                        });
                    }
                    if ($customer_type != '') {
                        $query->where(function ($query) use ($customer_type) {
                            $query->where('customer_type', $customer_type);
                        });
                    }
                })->where('customers.company_id',$this->company_id)
                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('countries.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('states.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('cities.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.customer_type', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.email', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.phone_no', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.description', 'like', '%' . $search_arr . '%');
                    });
                })->count();


//            DB::enableQueryLog();
            $records = DB::table('customers')
                ->leftJoin('countries', 'customers.country_id', '=', 'countries.id')
                ->leftJoin('states', 'customers.state_id', '=', 'states.id')
                ->leftJoin('cities', 'customers.city_id', '=', 'cities.id')
                ->where('customers.company_id',$this->company_id)
                ->where(function ($query) use ($name, $status,$customer_type) {
                    if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('customers.name', '=', $name);
                        });
                    }
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('customers.status', '=', $status);
                        });
                    }
                    if ($customer_type != '') {
                        $query->where(function ($query) use ($customer_type) {
                            $query->where('customer_type', $customer_type);
                        });
                    }
                })
                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('countries.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('states.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('cities.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.customer_type', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.name', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.email', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.phone_no', 'like', '%' . $search_arr . '%');
                    });

                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('customers.description', 'like', '%' . $search_arr . '%');
                    });
                })
                ->select('customers.*', 'countries.name as country_name', 'states.name as state_name', 'cities.name as city_name')
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();

//            dd(DB::getQueryLog());

            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $customer_type = $record->customer_type;
                $name = $record->name;
                $email = $record->email;
                $phone_no = $record->phone_no;
                $address = $record->address;
                $pincode = $record->pincode;
                $description = $record->description;
                $status = $record->status;
                $country_name = $record->country_name;
                $state_name = $record->state_name;
                $city_name = $record->city_name;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "customer_type" => $customer_type,
                    "name" => $name,
                    "email" => $email,
                    "phone_no" => $phone_no,
                    "address" => $address,
                    "pincode" => $pincode,
                    "country_name" => $country_name,
                    "state_name" => $state_name,
                    "city_name" => $city_name,
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

        $countries = Country::select(["name", "id"])->where('status', '=', 0)->get();
        return view('customer', compact('countries'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'phone_no' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $input['user_id'] = $this->logged_user->id;
            $input['company_id'] = ($this->company_id);
            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];

            if (Customer::where('phone_no', '=', $input['phone_no'])
                ->where('company_id', $input['company_id'])
                ->where(function ($query) use ($id) {
                    if ($id != 0) {
                        $query->Where(function ($query) use ($id) {
                            $query->where('id', '!=', $id);
                        });
                    }
                })
                ->first()) {
                return response()->json(['success' => 'Customer phone no exists!'], 409);
            }
            if ($id == 0) {
                $activityLogMsg = 'Customer created by ' . $this->logged_user->name;
                $customer = Customer::create($input);
            } else {
                $customer = Customer::find($id)->update($input);
                $activityLogMsg = 'Customer updated by ' . $this->logged_user->name;
            }

            // Add activity logs
            $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            LogActivity::addToLog($activityLogMsg, $input);

            return response()->json(['success' => 'Customer Saved!'], 201);
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

            $customers = Customer::find($id)->toArray();

            if (is_null($customers)) {
                return response()->json(['success' => 'Customer not found!'], 422);
            }
            $customers['id'] = Crypt::encrypt($customers['id']);
            return response()->json([
                "success" => true,
                "message" => "Customer retrieved successfully.",
                "data" => $customers
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
            $id = [];
            foreach (explode(",", $request->id) as $value) {
                $id[] = Crypt::decrypt($value);
            }
            $country = Customer::whereIn('id', $id)->delete();

            LogActivity::addToLog('Customer deleted by ' . $this->logged_user->name, $id);
            return response()->json(['success' => 'Customer Deleted!'], 201);
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
            $id = [];
            foreach (explode(",", $input['id']) as $value) {
                $id[] = Crypt::decrypt($value);
            }
            if (!Customer::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'Customer exists!'], 422);
            }
            $customers = Customer::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = ($input['status'] == 0) ? 'Active' : 'Deactive';
            LogActivity::addToLog('Customer status updated by ' . $this->logged_user->name, $data);

            return response()->json(['success' => 'Customer status updated!'], 201);
        }
    }

    public function customerAutocomplete(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            $user = Auth::user();
            $customers = ViewCustomerData::select('id', 'name', 'phone_no', 'state_id', 'address', 'pincode', 'country_name', 'state_name', 'city_name')
//                ->join('countries', 'country_id', '=', 'countries.id')
//                ->join('states', 'state_id', '=', 'states.id')
//                ->join('cities', 'city_id', '=', 'cities.id')
                ->where('company_id',$this->company_id)
                ->where('status', 0)
//                ->where('name', 'LIKE', '%' . $search . '%')
                ->where(function ($query) use ($search) {
                    $query->orWhere(function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                    $query->orWhere(function ($query) use ($search) {
                        $query->where('phone_no', 'like', $search . '%');
                    });
                })
                ->get();
            $response = array();
            foreach ($customers as $customer) {
                $response[] = array("value" => $customer->id, "label" => $customer->name, "desc" => $customer->phone_no, "state_id" => $customer->state_id, "country_name" => $customer->country_name, "state_name" => $customer->state_name, "city_name" => $customer->city_name, "address" => $customer->address, "pincode" => $customer->pincode, "phone_no" => $customer->phone_no);
            }

            return response()->json($response);
            return response()->json($result);
        }
    }
}
