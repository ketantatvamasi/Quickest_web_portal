<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\ViewUserData;
use Illuminate\Support\Facades\{Crypt, DB, Storage, Validator, File};
use Illuminate\Support\Carbon;
use App\Models\{User, EstimateAutoNumber, ProposalTemplates};
use LogActivity;
use Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $totalRecords = ViewUserData::select('count(*) as allcount')->where(function ($query) use ($name, $status) {
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
            })->whereNull('company_id')->count();
            $totalRecordswithFilter = ViewUserData::select('count(*) as allcount')->where('name', 'like', '%' . $search_arr . '%')->where(function ($query) use ($name, $status) {
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
            })->whereNull('company_id')->count();


//            DB::enableQueryLog();
            $records = DB::table('users_views')
                ->where(function ($query) use ($name, $status) {
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
                })
                ->where(function ($query) use ($search_arr) {
                    $query->orWhere(function ($query) use ($search_arr) {
                        $query->where('name', 'like', '%' . $search_arr . '%');
                    });
                })
                ->whereNull('company_id')
                ->orWhere(function ($query) use ($search_arr) {
                    if ($search_arr) {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('mobile_no', 'like', '%' . $search_arr . '%');
                        });
                    }
                })
                ->select('*')
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
                $company_name = $record->company_name;
                $email = $record->email;
                $mobile_no = $record->mobile_no;
                $address = $record->address;
                $pincode = $record->pincode;
                $country_name = $record->country_name;
                $state_name = $record->state_name;
                $city_name = $record->city_name;
                $business_category_name = $record->business_category_name;
                $website_link = $record->website_link;
                $gst_no = $record->gst_no;
                $status = $record->status;
                $i++;
                $profile_icon = ($record->profile_icon) ? Storage::url($record->profile_icon) : url('assets/images/users/avatar-1.jpg');
                $data[] = array(
                    "id" => $i,
                    "name" => '<img src="' . $profile_icon . '" alt="' . $name . '" title="' . $name . '" class="rounded-circle zoom me-2" height="48"> <p class="m-0 d-inline-block align-middle font-16">' . $name . '</p>',
                    "company_name" => $company_name,
                    "mobile_no" => $mobile_no,
                    "email" => $email,
                    "address" => $address,
                    "pincode" => $pincode,
                    "country_name" => $country_name,
                    "state_name" => $state_name,
                    "city_name" => $city_name,
                    "business_category_name" => $business_category_name,
                    "website_link" => $website_link,
                    "gst_no" => $gst_no,
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

        return view('admin.client.client');
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
            $company_category = User::whereIn('id', $id)->delete();

            LogActivity::addToLog('Client deleted by ' . $user->name, $id);
            return response()->json(['success' => 'Client Deleted!'], 201);
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
                if ($input['status'] == 'Approved') {

                    if (!EstimateAutoNumber::where('company_id', $id)->select('id')->first()) {
                        $data_ins['estimate_prefix'] = 'EST-';
                        $data_ins['estimate_next_no'] = '000001';
                        $data_ins['company_id'] = Crypt::decrypt($value);

                        $unit = EstimateAutoNumber::create($data_ins);
                    }

                }
            }
            if (!User::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'Client exists!'], 422);
            }
            $company_category = User::whereIn('id', $id)->update(["status" => $input['status']]);

            /*Clone Record*/
            if ($input['status'] === 'Approved') {
                $proposalTemplates = ProposalTemplates::first();
                $newProposalTemplates = $proposalTemplates->replicate();
                $newProposalTemplates->company_id = implode(',', $id);
                $newProposalTemplates->save();

                $path = 'public/document/'.implode(',', $id);
                if(!Storage::exists($path)){
                    Storage::makeDirectory($path);
                }
            }

            $data['id'] = $id;
            $data['status'] = $input['status'];
            LogActivity::addToLog('Client status updated by ' . $user->name, $data);

            return response()->json(['success' => 'Client status updated!'], 201);
        }
    }
}
