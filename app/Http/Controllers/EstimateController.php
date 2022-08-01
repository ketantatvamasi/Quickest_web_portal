<?php

namespace App\Http\Controllers;

use App\Models\{Country,
    Estimate,
    EstimateAutoNumber,
    EstimateItems,
    Product,
    ProposalTemplates,
    Testimonial,
    Unit,
    User
};
use App\Models\admin\ViewUserData;
use Auth;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Crypt, DB, Storage, Validator};
use LogActivity;
use Session;

class EstimateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
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
            $estimate_no = $request->get('estimate_no');
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            // Total records
            $totalRecords = Estimate::select('count(id) as allcount')
                ->where('company_id', $company_id)
                ->where(function ($query) use ($name, $status, $estimate_no) {
                    /*if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('customer_name', '=', $name);
                        });
                    }*/
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('status', '=', $status);
                        });
                    }
                    /* if ($estimate_no != '') {
                         $query->where(function ($query) use ($estimate_no) {
                             $query->where('estimate_no', '=', $estimate_no);
                         });
                     }*/
                })
                ->where(function ($query) use ($user) {
                    $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
                })
                ->where(function ($query) use ($search_arr) {
                    if ($search_arr != '') {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('customer_name', 'like', '%' . $search_arr . '%');
                        });
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('estimate_no', 'like', '%' . $search_arr . '%');
                        });
                    }
                })
                ->count();
            $totalRecordswithFilter = Estimate::select('count(id) as allcount')
                ->where('company_id', $company_id)
                ->where('customer_name', 'like', '%' . $search_arr . '%')
                ->where(function ($query) use ($name, $status, $estimate_no) {
                    /* if ($name != '') {
                         $query->Where(function ($query) use ($name) {
                             $query->where('customer_name', '=', $name);
                         });
                     }*/
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('status', '=', $status);
                        });
                    }
                    /*if ($estimate_no != '') {
                        $query->where(function ($query) use ($estimate_no) {
                            $query->where('estimate_no', '=', $estimate_no);
                        });
                    }*/
                })
                ->where(function ($query) use ($user) {
                    $query->whereRaw('user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('user_id', $user->id);
                })
                ->where(function ($query) use ($search_arr) {
                    if ($search_arr != '') {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('customer_name', 'like', '%' . $search_arr . '%');
                        });
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('estimate_no', 'like', '%' . $search_arr . '%');
                        });
                    }
                })
                ->count();

            $rowperpage = ($rowperpage == -1) ? $totalRecords : $rowperpage;
            //DB::enableQueryLog();
            $records = DB::table('estimates')
                ->leftJoin('users', 'estimates.sales_person_id', '=', 'users.id')
                ->where('estimates.company_id', $company_id)
                ->where(function ($query) use ($user) {
                    $query->whereRaw('estimates.user_id IN  (' . Session::get("get_data_by_id") . ')');
                    $query->orWhere('estimates.user_id', $user->id);
                })
                ->where(function ($query) use ($name, $status, $estimate_no) {
                    /*if ($name != '') {
                        $query->Where(function ($query) use ($name) {
                            $query->where('customer_name', '=', $name);
                        });
                    }*/
                    if ($status != '') {
                        $query->where(function ($query) use ($status) {
                            $query->where('estimates.status', '=', $status);
                        });
                    }
                    /* if ($estimate_no != '') {
                         $query->where(function ($query) use ($estimate_no) {
                             $query->where('estimate_no', '=', $estimate_no);
                         });
                     }*/
                })
                ->where(function ($query) use ($search_arr) {
                    if ($search_arr != '') {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('estimates.customer_name', 'like', '%' . $search_arr . '%');
                        });
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('estimates.estimate_no', 'like', '%' . $search_arr . '%');
                        });
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('users.name', 'like', '%' . $search_arr . '%');
                        });
                    }
                })

//                ->orWhere(function ($query) use ($search_arr) {
//                    if ($search_arr) {
//                        $query->orWhere(function ($query) use ($search_arr) {
//                            $query->where('description', 'like', '%' . $search_arr . '%');
//                        });
//                    }
//                })

                ->select(array('estimates.*', DB::raw("RIGHT(estimates.customer_address, 10) as mobile_no"), "users.name as sales_person_name"))
                ->skip($start)
                ->take($rowperpage)
                ->orderBy($columnName, $columnSortOrder)
                ->get();
            //dd(DB::getQueryLog());

            $data = array();
            $i = 0;
            foreach ($records as $record) {
                $id = Crypt::encrypt($record->id);
                $estimate_date = Carbon::createFromFormat('Y-m-d', $record->estimate_date)->format('d/m/Y');
                $estimate_no = $record->estimate_no;
                $reference = $record->reference;
                $name = $record->customer_name;
                $net_amount = $record->net_amount;
                $expiry_date = $record->expiry_date;
                $subtotal = $record->subtotal;
                $status = $record->status;
                $mobile_no = $record->mobile_no;
                $sales_person_name = $record->sales_person_name;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "estimate_date" => $estimate_date,
                    "estimate_no" => $estimate_no,
                    "download_action" => Storage::url('public/document/' . $company_id . '/' . $estimate_no . '.pdf'),
                    "reference" => $reference,
                    "customer_name" => $name,
                    "expiry_date" => $expiry_date,
                    "subtotal" => '₹ ' . $subtotal,
                    "net_amount" => '₹ ' . ($net_amount + $record->addless_amount),
                    "status" => $status,
                    "action" => $id,
                    "mobile_no" => $mobile_no,
                    "sales_person_name" => $sales_person_name,
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
        return view('estimate.index');
    }

    public function create(Request $request)
    {
        $user_list = [];
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $countries = Country::select(["name", "id"])->where('status', '=', 0)->get();
        $units = Unit::select(["name", "id"])->where('status', '=', 0)->where('company_id', $company_id)->get();
        $estimate_auto_number = EstimateAutoNumber::select(["estimate_prefix", "estimate_next_no"])->where('company_id', $company_id)->get()->first();
        $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
        if ($user->company_id == null) {
            $user_list = User::query()->select(["name", "id", "email"])
                ->where('status', 'Approved')
                ->where('company_id', $company_id)
                ->where(function ($query) use ($company_id) {
                    $query->where(function ($query) use ($company_id) {
                        $query->where('user_id', $company_id);
                        $query->orwhere('role_id', 6);
                    });

                })
                ->get();
        }
        $company_data = ViewUserData::where("id", $proposal_template->company_id)->orderBy('id', 'ASC')->get()->first();
        $testimonial_data = Testimonial::query()->where([['company_id', '=', $company_id], ['is_default', '=', 1]])->select(['id', 'name', 'client_name_one', 'description_one', 'rating_one', 'image_one', 'client_name_two', 'description_two', 'rating_two', 'image_two', 'client_name_three', 'description_three', 'rating_three', 'image_three'])->first();

        return view('estimate.new', compact('countries', 'units', 'estimate_auto_number', 'proposal_template', 'user_list', 'company_data', 'testimonial_data'));
    }

    public function show($id)
    {
        $id = ($id) ? Crypt::decrypt($id) : $id;
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $estimate = Estimate::where("id", $id)->get()->first();

        if (!$estimate) {
            return redirect()->back()->withInput();
        }
        $estimate_items = EstimateItems::where("estimate_id", $id)->orderBy('id', 'ASC')->get(["*"]);
        $company_data = ViewUserData::where("id", $estimate->company_id)->orderBy('id', 'ASC')->get()->first();
        $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
        return view('estimate.show', compact('estimate', 'estimate_items', 'company_data', 'proposal_template'));
    }

    // Generate PDF

    public function createPDF()
    {
        $id = 2;
        $estimate = Estimate::where("id", $id)->get()->first();

        if (!$estimate) {
            return redirect()->back()->withInput();
        }
        $estimate_items = EstimateItems::where("estimate_id", $id)->orderBy('id', 'ASC')->get(["*"]);
        $company_data = ViewUserData::where("id", $estimate->company_id)->orderBy('id', 'ASC')->get()->first();

//        return view('estimate.previewpdf', compact('estimate', 'estimate_items', 'company_data'));
//        $pdf = PDF::loadView('estimate.previewpdf', compact('estimate', 'estimate_items', 'company_data'));

//        view()->share('estimate.previewpdf', compact('estimate', 'estimate_items', 'company_data'));
//        $pdf = PDF::loadView('estimate.previewpdf', compact('estimate', 'estimate_items', 'company_data'));

        $pdf = PDF::loadView('estimate.previewpdf', compact('estimate', 'estimate_items', 'company_data'));


        return $pdf->download('onlinewebtutorblog.pdf');

//        return $pdf->download('pdf_file.pdf');

//        $html = '<h1></h1>';
//
//        PDF::SetTitle('Hello World');
//        PDF::AddPage();
//        PDF::writeHTML($html, true, false, true, false, '');
//
//        PDF::Output('hello_world.pdf');
    }


    public function edit($id)
    {
        $user_list = [];
        $id = ($id) ? Crypt::decrypt($id) : $id;
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $estimate = Estimate::where([["id", $id], ["company_id", "=", $company_id]])->get()->first();
        if (!$estimate) {
            return redirect()->back()->withInput();
        }

        $estimate_items = EstimateItems::where([["estimate_id", $id], ["company_id", "=", $company_id]])->orderBy('id', 'ASC')->get(["*"]);
        $countries = Country::select(["name", "id"])->where('status', '=', 0)->get();
        $units = Unit::select(["name", "id"])->where('status', '=', 0)->where('company_id', $company_id)->get();
        $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $company_id)->get();
        $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $company_id)->get()->first();
        if ($user->company_id == null) {
            $user_list = User::query()->select(["name", "id", "email"])
                ->where('status', 'Approved')
                ->where(function ($query) use ($company_id) {
                    $query->where(function ($query) use ($company_id) {
                        $query->where('company_id', $company_id);
                        $query->orwhere('role_id', 6);
                    });

                })
                ->get();
        }
        $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
        $company_data = ViewUserData::where("id", $estimate->company_id)->orderBy('id', 'ASC')->get()->first();
        return view('estimate.edit', compact('estimate', 'estimate_items', 'countries', 'units', 'user_list', 'products', 'proposal_template', 'company_data', 'testimonials'));
    }

    public function preview($id)
    {
        $user_list = [];
        $id = ($id) ? Crypt::decrypt($id) : $id;
        $estimate = Estimate::where("id", $id)->get()->first();
        if (!$estimate) {
            return redirect()->back()->withInput();
        }
        $user = Auth::user();
        $company_id = $estimate->company_id;
        $estimate_items = EstimateItems::where("estimate_id", $id)->orderBy('id', 'ASC')->get(["*"]);
        $countries = Country::select(["name", "id"])->where('status', '=', 0)->get();
        $units = Unit::select(["name", "id"])->where('status', '=', 0)->where('company_id', $company_id)->get();
        $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $company_id)->get();
        $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $company_id)->get()->first();
//        if ($user->company_id == null) {
        $user_list = User::query()->select(["name", "id", "email"])
            ->where('status', 'Approved')
            ->where(function ($query) use ($company_id) {
                $query->where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                    $query->orwhere('role_id', 6);
                });

            })
            ->get();
//        }
        $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
        $company_data = ViewUserData::where("id", $estimate->company_id)->orderBy('id', 'ASC')->get()->first();
        return view('estimate.preview', compact('estimate', 'estimate_items', 'countries', 'units', 'user_list', 'products', 'proposal_template', 'company_data', 'testimonials'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $pdf_cover_page_flg = 0;
            if ($request->has('pdf_cover_page_flg')) {
                $pdf_cover_page_flg = 1;
            }
            $pdf_about_us_flg = 0;
            if ($request->has('pdf_about_us_flg')) {
                $pdf_about_us_flg = 1;
            }
            $pdf_product_flg = 0;
            if ($request->has('pdf_product_flg')) {
                $pdf_product_flg = 1;
            }
            $pdf_est_flg = 0;
            if ($request->has('pdf_est_flg')) {
                $pdf_est_flg = 1;
            }
            $pdf_terms_flg = 0;
            if ($request->has('pdf_terms_flg')) {
                $pdf_terms_flg = 1;
            }

            $pdf_thank_you_flg = 0;
            if ($request->has('pdf_thank_you_flg')) {
                $pdf_thank_you_flg = 1;
            }

            $pdf_testimonial_flg = 0;
            if ($request->has('pdf_testimonial_flg')) {
                $pdf_testimonial_flg = 1;
            }

            $validator = Validator::make($input, [
                'customer_name' => 'required',
                'customer_id' => 'required',
                'customer_state_id' => 'required',
//                'company_state_id' => 'required',
                'estimate_no' => 'required',
                'estimate_date' => 'required',
                'subtotal' => 'required',
                'net_amount' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;

            if (Estimate::where('estimate_no', '=', $input['estimate_no'])->where('company_id', $company_id)->first()) {
                return response()->json(['success' => 'Estimate exists!'], 409);
            }
            $data = array();
            $data['customer_name'] = $input['customer_name'];
            $data['customer_address'] = $input['customer_address'];
            $data['customer_id'] = $input['customer_id'];
            $data['customer_state_id'] = $input['customer_state_id'];
//            $data['company_state_id'] = $input['company_state_id'];
            $data['estimate_no'] = $input['estimate_no'];
            $data['reference'] = $input['reference'];
            $data['estimate_date'] = Carbon::createFromFormat('d/m/Y', $input['estimate_date'])->format('Y-m-d');
            $data['expiry_date'] = Carbon::createFromFormat('d/m/Y', $input['expiry_date'])->format('Y-m-d');
            $data['subtotal'] = $input['subtotal'];
            $data['total_cgst_amount'] = $input['total_cgst_amount'];
            $data['total_sgst_amount'] = $input['total_sgst_amount'];
            $data['total_igst_amount'] = $input['total_igst_amount'];
            $data['addless_amount'] = 0 + $input['addless_amount'];
            $data['addless_title'] = $input['addless_title'];
            $data['net_amount'] = $input['net_amount'];
            $data['company_id'] = $company_id;
            $data['sales_person_id'] = $user->id;
            $data['user_id'] = $input['user_id'];
            $data['item_rate_are'] = $input['item_rate_are'];
            $data['customer_notes'] = $input['customer_notes'];
            $data['term_condition'] = $input['term_condition'];

            $data['est_cover_page_title'] = $input['est_cover_page_title'];
            $data['est_cover_page_content'] = $input['est_cover_page_content'];

            $data['est_cover_page_footer_one'] = $input['est_cover_page_footer_one'];
            $data['est_cover_page_footer_two'] = $input['est_cover_page_footer_two'];
            $data['est_aboutus_title'] = $input['est_aboutus_title'];
            $data['est_aboutus_content'] = $input['est_aboutus_content'];
            $data['est_term_condition_title'] = $input['est_term_condition_title'];
            $data['est_term_condition_content'] = $input['est_term_condition_content'];
            $data['est_cover_page_title_div'] = $input['est_cover_page_title_div'];
            $data['est_cover_page_content_div'] = $input['est_cover_page_content_div'];
            preg_match_all('#\${(.*?)\}#', strip_tags($data['est_cover_page_content_div']), $match);
            foreach ($match[1] as $key => $value) {
                $valueArr = explode('.', $value);
                if ($valueArr[0] == 'customers') {
                    $id = $data['customer_id'];
                    $table = 'customers_views';
                }

                if ($valueArr[0] == 'companies') {
                    $table = 'users_views';
                }

                if ($valueArr[0] == 'estimates') {
                    $id = $data['estimate_id'];
                }
                $result = DB::table($table)
                    ->where('id', $id)
                    ->select([$valueArr[1]])
                    ->get()->first();
                $a = $valueArr[1];

                $data['est_cover_page_content_div'] = str_replace('${' . $value . '}', $result->$a, $data['est_cover_page_content_div']);

            }
            $data['est_cover_page_footer_one_div'] = $input['est_cover_page_footer_one_div'];
            $data['est_cover_page_footer_two_div'] = $input['est_cover_page_footer_two_div'];
            $data['est_aboutus_title_div'] = $input['est_aboutus_title_div'];
            $data['est_aboutus_content_div'] = $input['est_aboutus_content_div'];
            $data['est_term_condition_title_div'] = $input['est_term_condition_title_div'];
            $data['est_term_condition_content_div'] = $input['est_term_condition_content_div'];
            $data['testimonial_id'] = $input['testimonial_id'];
            $data['product_id'] = implode(',', $input['product_id']);
            $data['pdf_cover_page_flg'] = $pdf_cover_page_flg;
            $data['pdf_about_us_flg'] = $pdf_about_us_flg;
            $data['pdf_product_flg'] = $pdf_product_flg;
            $data['pdf_est_flg'] = $pdf_est_flg;
            $data['pdf_terms_flg'] = $pdf_terms_flg;
            $data['pdf_thank_you_flg'] = $pdf_thank_you_flg;
            $data['pdf_testimonial_flg'] = $pdf_testimonial_flg;
            $data['status'] = 'Draft';
            $data['tilt'] = $input['tilt'];
            $data['azumuth'] = $input['azumuth'];
            $data['no_of_panel'] = $input['no_of_panel'];
            $data['panel_wattage'] = $input['panel_wattage'];

            $activityLogMsg = 'Estimate created by ' . $user->name;
            $estimate = Estimate::create($data);
            $insert_id = $estimate->id;
            $input['log_id'] = $insert_id;
            $input['log_type'] = 'estimate';
            LogActivity::addToLog($activityLogMsg, $input, 1);
            if ($insert_id) {
                $estimate_coll = collect($input['data']);
                $estimateArr = $estimate_coll->values()->toArray();
                foreach ($estimateArr as $key => $csm) {
                    $estimateArr[$key]['estimate_id'] = $insert_id;
                    $estimateArr[$key]['company_id'] = $company_id;
                    $estimateArr[$key]['user_id'] = $user->id;
                }
                EstimateItems::insert($estimateArr);


            }

            $estimate_auto_number = EstimateAutoNumber::where('company_id', $company_id)
                ->select('estimate_prefix', 'estimate_next_no')
                ->get()->first();

            $tmp_est_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;
            if ($tmp_est_no == $input['estimate_no']) {
                $tmp_est_no = $estimate_auto_number->estimate_next_no + 1;

                EstimateAutoNumber::where('company_id', $company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
            }

            //PDF start

            $estimate = Estimate::where([["id", $insert_id], ["company_id", "=", $company_id]])->get()->first();
            $estimate_items = EstimateItems::where([["estimate_id", $insert_id], ["company_id", "=", $company_id]])->orderBy('id', 'ASC')->get(["*"]);
            $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
            $company_data = ViewUserData::where("id", $company_id)->orderBy('id', 'ASC')->get()->first();
            $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $company_id)->get();


            $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $company_id)->get()->first();
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) use ($proposal_template) {
                if ($pdf->PageNo() > 1) {
                    $image_file = public_path(Storage::url($proposal_template->header_logo));
                    $pdf->Image($image_file, $proposal_template->header_logo_left, $proposal_template->header_logo_top, $proposal_template->header_logo_size, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
//                    $pdf->Image($image_file, 164, 2, 40, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    $pdf->SetY(7);
                    // Set font
                    $pdf->SetFont('helvetica', 'B', 20);
                    $pdf->setPageMark();
                    /* $pdf->SetAlpha(0.1);
                     $img_file = public_path('storage/logo.png');
                     $pdf->Image($img_file, 50, 135, 100, '', 0, 0, '', false, 300, '', false, false, false);*/

                    // Title
//                $pdf->Cell(0, 15, 'Heaven Designs Pvt Ltd.', 0, false, 'C', 0, '', 1, false, 'M', 'M');
//            $pdf->line(1, 20, 209, 20, array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => "#dee2e6"));
                }
            });

// Custom Footer
            $pdf::setFooterCallback(function ($pdf) use ($proposal_template) {
                if ($pdf->PageNo() > 1) {
                    $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_one . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                    $pdf->SetY(-9.6);

                } else {
                    $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                    $pdf->SetY(-9.6);
                }
                $pdf->writeHTML($footer, true, false, true, false, '');
//                $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:' . $proposal_template->theme_color_one . ';">Heaven Designs</a></td><td style="text-align: right;color:' . $proposal_template->theme_color_one . ';">Social Media Link</td></tr></table>';
//                $pdf->SetY(-9.6);
//                $pdf->writeHTML($footer, true, false, true, false, '');
            });

            $pdf::SetAuthor('System');
            $pdf::SetTitle('My Report');
            $pdf::SetSubject('Report of System');


            //First page
            if ($pdf_cover_page_flg) {
                $pdf::SetMargins(0, 0, 0, false);
                $pdf::SetFontSubsetting(false);
                $pdf::SetFontSize('12px');
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::SetAutoPageBreak(false, PDF_MARGIN_FOOTER);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.cover-page', compact('estimate', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Second page
            if ($pdf_about_us_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetFontSubsetting(true);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.about-page', compact('estimate', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Third page
            if($pdf_product_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.product-page', compact('products', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Fourth page
            if($pdf_est_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.estimate-page', compact('estimate', 'estimate_items', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Fifth page
            if($pdf_terms_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 10);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.term-and-condition-page', compact('estimate', 'proposal_template'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Sixth page
            if($pdf_testimonial_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.testimonial-page', compact('proposal_template', 'company_data', 'testimonials'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Seven page
            $pdf::startPageGroup();
            $pdf::SetMargins(7, 15.5, 7, false);
            $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
            $pdf::SetFont('helvetica', 'R', 11);
            $pdf::AddPage('P', 'A4');

            $view = \View::make('pdf.thank-you-page', compact('company_data', 'proposal_template'));
            $html = $view->render();
            $pdf::writeHTML($html, true, false, true, false, '');

//            $pdf::Output('hello_world.pdf', 'I');
            $pdf::Output(public_path('storage/document/' . $company_id . '/' . $data['estimate_no'] . '.pdf'), 'F');
            //PDF end

            return response()->json(['success' => 'Estimate Saved!', 'estimate_id' => $insert_id], 201);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            $pdf_cover_page_flg = 0;
            if ($request->has('pdf_cover_page_flg')) {
                $pdf_cover_page_flg = 1;
            }
            $pdf_about_us_flg = 0;
            if ($request->has('pdf_about_us_flg')) {
                $pdf_about_us_flg = 1;
            }
            $pdf_product_flg = 0;
            if ($request->has('pdf_product_flg')) {
                $pdf_product_flg = 1;
            }
            $pdf_est_flg = 0;
            if ($request->has('pdf_est_flg')) {
                $pdf_est_flg = 1;
            }
            $pdf_terms_flg = 0;
            if ($request->has('pdf_terms_flg')) {
                $pdf_terms_flg = 1;
            }

            $pdf_thank_you_flg = 0;
            if ($request->has('pdf_thank_you_flg')) {
                $pdf_thank_you_flg = 1;
            }

            $pdf_testimonial_flg = 0;
            if ($request->has('pdf_testimonial_flg')) {
                $pdf_testimonial_flg = 1;
            }

            $validator = Validator::make($input, [
                'id' => 'required',
                'customer_name' => 'required',
                'customer_id' => 'required',
                'customer_state_id' => 'required',
//                'company_state_id' => 'required',
                'estimate_no' => 'required',
                'estimate_date' => 'required',
                'subtotal' => 'required',
                'net_amount' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $user = Auth::user();
            $company_id = ($user->company_id) ? $user->company_id : $user->id;
//            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            $id = $input['id'];
            if (Estimate::where('estimate_no', '=', $input['estimate_no'])->where('company_id', $company_id)->where(function ($query) use ($id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
            })->first()) {
                return response()->json(['success' => 'Estimate exists!'], 409);
            }
            $data = array();
            $data['customer_name'] = $input['customer_name'];
            $data['customer_address'] = $input['customer_address'];
            $data['customer_id'] = $input['customer_id'];
            $data['customer_state_id'] = $input['customer_state_id'];
//            $data['company_state_id'] = $input['company_state_id'];
            $data['estimate_no'] = $input['estimate_no'];
            $data['reference'] = $input['reference'];
            $data['estimate_date'] = Carbon::createFromFormat('d/m/Y', $input['estimate_date'])->format('Y-m-d');
            $data['expiry_date'] = Carbon::createFromFormat('d/m/Y', $input['expiry_date'])->format('Y-m-d');
            $data['subtotal'] = $input['subtotal'];
            $data['total_cgst_amount'] = $input['total_cgst_amount'];
            $data['total_sgst_amount'] = $input['total_sgst_amount'];
            $data['total_igst_amount'] = $input['total_igst_amount'];
            $data['addless_amount'] = 0 + $input['addless_amount'];
            $data['addless_title'] = $input['addless_title'];
            $data['net_amount'] = $input['net_amount'];
            $data['company_id'] = $company_id;
            $data['sales_person_id'] = $user->id;
//            $data['user_id'] = $user->id;
            $data['item_rate_are'] = $input['item_rate_are'];
            $data['customer_notes'] = $input['customer_notes'];
            $data['term_condition'] = $input['term_condition'];

            $data['est_cover_page_title'] = $input['est_cover_page_title'];
            $data['est_cover_page_content'] = $input['est_cover_page_content'];

            $data['est_cover_page_content_div'] = $data['est_cover_page_content'];
            preg_match_all('#\${(.*?)\}#', strip_tags($data['est_cover_page_content_div']), $match);

            foreach ($match[1] as $key => $value) {
                $valueArr = explode('.', $value);
                $table = $valueArr[0];
                if ($valueArr[0] == 'customers') {
                    $tmpId = $data['customer_id'];
                    $table = 'customers_views';
                }

                if ($valueArr[0] == 'companies') {
                    $table = 'users_views';
                    $tmpId = $company_id;
                }

                if ($valueArr[0] == 'estimates') {
                    $tmpId = $data['estimate_id'];
                }
                $result = DB::table($table)
                    ->where('id', $tmpId)
                    ->select([$valueArr[1]])
                    ->get()->first();
                $a = $valueArr[1];

                $data['est_cover_page_content_div'] = str_replace('${' . $value . '}', $result->$a, $data['est_cover_page_content_div']);
            }
            $data['est_cover_page_footer_one'] = $input['est_cover_page_footer_one'];
            $data['est_cover_page_footer_two'] = $input['est_cover_page_footer_two'];
            $data['est_aboutus_title'] = $input['est_aboutus_title'];
            $data['est_aboutus_content'] = $input['est_aboutus_content'];
            $data['est_term_condition_title'] = $input['est_term_condition_title'];
            $data['est_term_condition_content'] = $input['est_term_condition_content'];

            $data['est_cover_page_title_div'] = $input['est_cover_page_title_div'];
            $data['est_cover_page_footer_one_div'] = $input['est_cover_page_footer_one'];
            preg_match_all('#\${(.*?)\}#', strip_tags($data['est_cover_page_footer_one_div']), $match);
            foreach ($match[1] as $key => $value) {
                $valueArr = explode('.', $value);
                $table = $valueArr[0];
                if ($valueArr[0] == 'companies') {
                    $tmpId = $company_id;
                    $table = 'users_views';
                }

                $result = DB::table($table)
                    ->where('id', $tmpId)
                    ->select([$valueArr[1]])
                    ->get()->first();
                $a = $valueArr[1];
                $data['est_cover_page_footer_one_div'] = str_replace('${' . $value . '}', $result->$a, $data['est_cover_page_footer_one_div']);
            }
            $data['est_cover_page_footer_two_div'] = $input['est_cover_page_footer_two'];
            preg_match_all('#\${(.*?)\}#', strip_tags($data['est_cover_page_footer_two_div']), $match);
            foreach ($match[1] as $key => $value) {
                $valueArr = explode('.', $value);
                $table = $valueArr[0];
                if ($valueArr[0] == 'estimates') {
                    $tmpId = $input['id'];
                }

                $result = DB::table($table)
                    ->where('id', $tmpId)
                    ->select([$valueArr[1]])
                    ->get()->first();
                $a = $valueArr[1];
                $data['est_cover_page_footer_two_div'] = str_replace('${' . $value . '}', $result->$a, $data['est_cover_page_footer_two_div']);
            }
            $data['est_aboutus_title_div'] = $input['est_aboutus_title_div'];
            $data['est_aboutus_content_div'] = $input['est_aboutus_content_div'];
            $data['est_term_condition_title_div'] = $input['est_term_condition_title_div'];
            $data['est_term_condition_content_div'] = $input['est_term_condition_content_div'];
            $data['testimonial_id'] = $input['testimonial_id'];
            $data['product_id'] = implode(',', $input['product_id']);
            $data['pdf_cover_page_flg'] = $pdf_cover_page_flg;
            $data['pdf_about_us_flg'] = $pdf_about_us_flg;
            $data['pdf_product_flg'] = $pdf_product_flg;
            $data['pdf_est_flg'] = $pdf_est_flg;
            $data['pdf_terms_flg'] = $pdf_terms_flg;
            $data['pdf_thank_you_flg'] = $pdf_thank_you_flg;
            $data['pdf_testimonial_flg'] = $pdf_testimonial_flg;
            $data['tilt'] = $input['tilt'];
            $data['azumuth'] = $input['azumuth'];
            $data['no_of_panel'] = $input['no_of_panel'];
            $data['panel_wattage'] = $input['panel_wattage'];

            $activityLogMsg = 'Estimate updated by ' . $user->name;
            $estimate = Estimate::find($input['id'])->update($data);
            $input['log_id'] = $input['id'];
            $input['log_type'] = 'estimate';
            LogActivity::addToLog($activityLogMsg, $input, 1);
            if ($estimate) {
                EstimateItems::where("estimate_id", $input['id'])->delete();
                $estimate_coll = collect($input['data']);
                $estimateArr = $estimate_coll->values()->toArray();

                foreach ($estimateArr as $key => $csm) {
                    $estimateArr[$key]['estimate_id'] = $input['id'];
                    $estimateArr[$key]['company_id'] = $company_id;
                    $estimateArr[$key]['user_id'] = $user->id;
                }
                EstimateItems::insert($estimateArr);
            }

            $estimate_auto_number = EstimateAutoNumber::where('company_id', $company_id)
                ->select('estimate_prefix', 'estimate_next_no')
                ->get()->first();

            $tmp_est_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;
            if ($tmp_est_no == $input['estimate_no']) {
                $tmp_est_no = $estimate_auto_number->estimate_next_no + 1;

                EstimateAutoNumber::where('company_id', $company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
            }

            //PDF start

            $estimate = Estimate::where([["id", $input['id']], ["company_id", "=", $company_id]])->get()->first();
            $estimate_items = EstimateItems::where([["estimate_id", $input['id']], ["company_id", "=", $company_id]])->orderBy('id', 'ASC')->get(["*"]);
            $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
            $company_data = ViewUserData::where("id", $company_id)->orderBy('id', 'ASC')->get()->first();
            $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $company_id)->get();


            $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $company_id)->get()->first();
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf::setHeaderCallback(function ($pdf) use ($proposal_template) {
                if ($pdf->PageNo() > 1) {
                    $image_file = public_path(Storage::url($proposal_template->header_logo));
                    $pdf->Image($image_file, $proposal_template->header_logo_left, $proposal_template->header_logo_top, $proposal_template->header_logo_size, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
//                    $pdf->Image($image_file, 164, 2, 40, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    $pdf->SetY(7);
                    // Set font
                    $pdf->SetFont('helvetica', 'B', 20);
                    $pdf->setPageMark();
                    /* $pdf->SetAlpha(0.1);
                     $img_file = public_path('storage/logo.png');
                     $pdf->Image($img_file, 50, 135, 100, '', 0, 0, '', false, 300, '', false, false, false);*/

                    // Title
//                $pdf->Cell(0, 15, 'Heaven Designs Pvt Ltd.', 0, false, 'C', 0, '', 1, false, 'M', 'M');
//            $pdf->line(1, 20, 209, 20, array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => "#dee2e6"));
                }
            });

// Custom Footer
            $pdf::setFooterCallback(function ($pdf) use ($proposal_template) {
                if ($pdf->PageNo() > 1) {
                    $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_one . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                    $pdf->SetY(-9.6);

                } else {
                    $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                    $pdf->SetY(-9.6);
                }
                $pdf->writeHTML($footer, true, false, true, false, '');
                /* $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:' . $proposal_template->theme_color_one . ';">Heaven Designs</a></td><td style="text-align: right;color:' . $proposal_template->theme_color_one . ';">Social Media Link</td></tr></table>';
                 $pdf->SetY(-9.6);
                 $pdf->writeHTML($footer, true, false, true, false, '');*/
            });

            $pdf::SetAuthor('System');
            $pdf::SetTitle('My Report');
            $pdf::SetSubject('Report of System');


            //First page
            if($pdf_cover_page_flg) {
                $pdf::SetMargins(0, 0, 0, false);
                $pdf::SetFontSubsetting(false);
                $pdf::SetFontSize('12px');
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::SetAutoPageBreak(false, PDF_MARGIN_FOOTER);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.cover-page', compact('estimate', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Second page
            if($pdf_about_us_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetFontSubsetting(true);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.about-page', compact('estimate', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Third page
            if($pdf_product_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.product-page', compact('products', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Fourth page
            if($pdf_est_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.estimate-page', compact('estimate', 'estimate_items', 'proposal_template', 'company_data'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Fifth page
            if($pdf_terms_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 10);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.term-and-condition-page', compact('estimate', 'proposal_template'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Sixth page
            if($pdf_testimonial_flg) {
                $pdf::startPageGroup();
                $pdf::SetMargins(7, 15.5, 7, false);
                $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
                $pdf::SetFont('helvetica', 'R', 11);
                $pdf::AddPage('P', 'A4');

                $view = \View::make('pdf.testimonial-page', compact('proposal_template', 'company_data', 'testimonials'));
                $html = $view->render();
                $pdf::writeHTML($html, true, false, true, false, '');
            }

            //Seven page
            $pdf::startPageGroup();
            $pdf::SetMargins(7, 15.5, 7, false);
            $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
            $pdf::SetFont('helvetica', 'R', 11);
            $pdf::AddPage('P', 'A4');

            $view = \View::make('pdf.thank-you-page', compact('company_data', 'proposal_template'));
            $html = $view->render();
            $pdf::writeHTML($html, true, false, true, false, '');

//            $pdf::Output('hello_world.pdf', 'I');
            $pdf::Output(public_path('storage/document/' . $company_id . '/' . $data['estimate_no'] . '.pdf'), 'F');
            //PDF end
            return response()->json(['success' => 'Estimate Saved!', 'url' => url('quotes/edit/' . Crypt::encrypt($input['id'])) . '/1'], 201);
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
            $unit = Estimate::whereIn('id', $id)->delete();

            LogActivity::addToLog('Estimate deleted by ' . $user->name, $id);
            return response()->json(['success' => 'Estimate Deleted!'], 201);
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
            if (!Estimate::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'Estimate exists!'], 422);
            }
            $unit = Estimate::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = $input['status'];
            $data['log_id'] = $id[0];
            $data['log_type'] = 'estimate';

            LogActivity::addToLog('Estimate status updated by ' . $user->name, $data, 1);

            return response()->json(['success' => 'Estimate status updated!'], 201);
        }
    }

    public function getEstimateNumber(Request $request)
    {
        $user = Auth::user();

        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        if ($request->ajax()) {


            $records = DB::table('estimate_auto_numbers')->where('company_id', $company_id)
                ->select('estimate_prefix', 'estimate_next_no')
                ->get();

            return json_encode($records);
        }
    }

    public function updateEstimateNumber(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'estimate_next_no' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }

            $user = Auth::user();

            $input['company_id'] = ($user->company_id) ? $user->company_id : $user->id;

            if (Estimate::where('estimate_no', '=', $input['estimate_prefix'] . $input['estimate_next_no'])->where('company_id', '=', $input['company_id'])->select('id')->first()) {
                return response()->json(['success' => 'Estimate ' . $input['estimate_next_no'] . ' already exists!'], 409);
            }

            $estimate_auto_number = EstimateAutoNumber::where('company_id', $input['company_id'])->update($input);
            $activityLogMsg = 'Estimate number updated by ' . $user->name;

            // Add activity logs
            $input['user_id'] = $user->id;
            LogActivity::addToLog($activityLogMsg, $input);

            return response()->json(['success' => 'Estimate number Saved!', 'data' => $input], 201);
        }
    }

    public function getRecentActivities(Request $request)
    {
        $log_id = $request->id;
//        $log_id = Crypt::decrypt($request->id);
        return $recent_activity = LogActivity::logActivityLists($log_id, array('event-follow-up', 'estimate'));

    }

    public function estimatePdfInfo(Request $request)
    {
        $input = $request->all();
//        echo "<pre>";
//        print_r($input);
        $user = Auth::user();
        $id = ($user->company_id) ? $user->company_id : $user->id;
        $data = array();

        foreach ($input['matches'] as $key => $value) {

            $valueArr = explode('.', $value);
//            DB::enableQueryLog();
            $table = $valueArr[0];
            if ($valueArr[0] == 'customers') {
                $id = $input['customer_id'];
                $table = 'customers_views';
            }

            if ($valueArr[0] == 'companies') {
                $table = 'users_views';
            }

            if ($valueArr[0] == 'estimates') {
                $id = $input['estimate_id'];
            }
            $result = DB::table($table)
                ->where('id', $id)
                ->get([$valueArr[1]])->first();

            $a = $valueArr[1];
//            dd(DB::getQueryLog());
            $data[$value] = $result->$a;
        }
        return json_encode($data);
    }

    public function estimateDuplicate(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $user = Auth::user();

            $company_id = ($user->company_id) ? $user->company_id : $user->id;
            $estimate_auto_number = DB::table('estimate_auto_numbers')->where('company_id', $company_id)
                ->select('estimate_prefix', 'estimate_next_no')
                ->first();
            $estimate_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;

            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            $estimate = Estimate::query()->where("id", $id)->first();
            $newEstimate = $estimate->replicate();
            $newEstimate->estimate_no = $estimate_no;
            $newEstimate->status = 'Draft';
            $newEstimate->save();
            $insert_id = $newEstimate->id;
            $estimateItems = EstimateItems::where('estimate_id', $id)->get();

            foreach ($estimateItems as $estimateItems) {
                $newEstimateItems = $estimateItems->replicate();
                $newEstimateItems->estimate_id = $insert_id;
                $newEstimateItems->save();
            }

            $tmp_est_no = $estimate_auto_number->estimate_next_no + 1;
            EstimateAutoNumber::where('company_id', $company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
            return response()->json(['success' => 'Estimate Copied!', 'estimate_id' => $insert_id], 201);
        }
    }

    public function generateLink($id)
    {
        $data['id'] = $id;
        $id = ($id) ? Crypt::decrypt($id) : $id;
        $estimate = Estimate::query()->where("id", $id)->first();
        $data['company_id'] = $estimate->company_id;
        $data['status'] = $estimate->status;
        $data['estimate_no'] = $estimate->estimate_no;
        $data['customer_name'] = $estimate->customer_name;
        $data['net_amount'] = $estimate->net_amount + $estimate->addless_amount;
//        $data['pdf_path'] = Storage::url('storage/document/' . $data['estimate_no'] . '.pdf');
        return view('estimate.customer-preview', compact('data'));
    }

    public function updateStatus(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            Estimate::where('id', $id)->update(['status' => $input['val']]);
            return response()->json(['success' => 'Update status!'], 201);
        }
    }

    public function testPost(Request $request)
    {
        $input = $request->all();
        if (!Session::has('categoryNameSession')) {
            $data = array();
        }
        if (Session::has('categoryNameSession')) {
            $data = Session::get('categoryNameSession');
        }
        array_push($data, $input['name']);
        Session::put('categoryNameSession', $data);
        return view('estimate.test');

    }

    public function test()
    {
        return view('estimate.test');
    }

}
