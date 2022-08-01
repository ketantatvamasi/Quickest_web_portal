<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\{admin\ViewUserData,
    City,
    Country,
    Customer,
    Estimate,
    EstimateAutoNumber,
    EstimateItems,
    Item,
    Product,
    ProposalTemplates,
    State,
    Testimonial,
    User,
    ViewCustomerData
};
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Crypt, DB, Storage, Validator};
use Image;
use LogActivity;

class EstimateController extends BaseController
{
    protected $logged_user = null;
    protected $company_id = 0;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->logged_user = Auth::user();
            $this->company_id = ($this->logged_user->company_id) ? $this->logged_user->company_id : $this->logged_user->id;
            return $next($request);
        });
    }

    public function itemAutocomplete($search = null)
    {
        $items = Item::select('id', 'name', 'sale_price', 'description', 'inter_state', 'intra_state', 'hsn_code')
            ->where([['name', 'LIKE', '%' . $search . '%'], ['status', '=', 0], ['company_id', '=', $this->company_id]])
            ->get();
        return $this->sendResponse($items, 'Item retrieved successfully');
    }

    public function itemStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'unit_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        $input['user_id'] = $this->logged_user->id;
        $input['sale_price'] = 0 + $input['sale_price'];
        $input['cost_price'] = 0 + $input['cost_price'];
        $input['company_id'] = $this->company_id;
        $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        if (Item::where('name', '=', $input['name'])->where('company_id', $input['company_id'])->where(function ($query) use ($id) {
            if ($id != 0) {
                $query->Where(function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                });
            }
        })->first()) {
            return $this->sendError('Item exists', ['error' => 'Item exists'], 409);
        }
        if ($id == 0) {
            $activityLogMsg = 'Item created by ' . $this->logged_user->name;
            Item::create($input);
        } else {
            Item::find($id)->update($input);
            $activityLogMsg = 'Item updated by ' . $this->logged_user->name;
        }

        // Add activity logs
        $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        LogActivity::addToLog($activityLogMsg, $input);

        return $this->sendResponse([], 'Item Saved');
    }

    public function customerAutocomplete($search = null)
    {
        $customers = ViewCustomerData::select('id', 'name', 'phone_no', 'state_id', 'address', 'pincode', 'country_name', 'state_name', 'city_name')
            ->where([['company_id', '=', $this->company_id], ['status', '=', 0]])
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

        return $this->sendResponse($response, 'Customer retrieved successfully');
    }

    public function customerStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        $input['user_id'] = $this->logged_user->id;
        $input['company_id'] = $this->company_id;
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
            return $this->sendError('Customer phone no exists', ['error' => 'Customer phone no exists'], 409);
        }
        DB::beginTransaction();
        try {
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
            DB::commit();
            return $this->sendResponse([], 'Customer Saved');
        } catch (\Exception $exp) {
            DB::rollBack();
            return $this->sendError('Error in customer create', ['error' => $exp->getMessage()], 400);
        }
    }

    public function getCountry(Request $request)
    {
        $data['countries'] = Country::where('status', '=', 0)->get(["name", "id"]);
        return $this->sendResponse($data, 'Country retrieved successfully');
    }

    public function getState($country_id)
    {
        $data['states'] = State::where([["country_id", '=', $country_id], ['status', '=', 0]])->get(["name", "id"]);
        return $this->sendResponse($data, 'State retrieved successfully');
    }

    public function getCity($state_id)
    {
        $data['cities'] = City::where([["state_id", '=', $state_id], ['status', '=', 0]])->get(["name", "id"]);
        return $this->sendResponse($data, 'City retrieved successfully');
    }

    public function testimonialAutocomplete($search = null)
    {
        $testimonials = Testimonial::select('name', 'id', 'client_name_one', 'image_one', 'rating_one', 'description_one', 'client_name_two', 'image_two', 'rating_two', 'description_two', 'client_name_three', 'image_three', 'rating_three', 'description_three', 'is_default')->where('testimonials.company_id', $this->company_id)->where('testimonials.status', 0)->where('name', 'LIKE', '%' . $search . '%')->where('status', 0)->get();
        $response = array();
        foreach ($testimonials as $testimonial) {
            $response[] = array("value" => $testimonial->id, "label" => $testimonial->name, "image_one" => Storage::url($testimonial->image_one), "description_one" => $testimonial->description_one, "rating_one" => $testimonial->rating_one, "client_name_one" => $testimonial->client_name_one, "description_two" => $testimonial->description_two, "rating_two" => $testimonial->rating_two, "client_name_two" => $testimonial->client_name_two, "description_three" => $testimonial->description_three, "rating_three" => $testimonial->rating_three, "client_name_three" => $testimonial->client_name_three, "image_two" => Storage::url($testimonial->image_two), "image_three" => Storage::url($testimonial->image_three), "is_default" => $testimonial->is_default);
        }
        return $this->sendResponse($response, 'Testimonial retrieved successfully');
    }

    public function testimonialStore(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        $input['user_id'] = $this->logged_user->id;
        $input['company_id'] = ($this->company_id) ? $this->company_id : $this->logged_user->id;
        $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];


        if ($id == 0) {
            $validator = Validator::make($input, [
                'name' => 'required',
                'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            ]);
        }
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        if (Testimonial::where('name', '=', $input['name'])->where('company_id', $input['company_id'])->where(function ($query) use ($id) {
            if ($id != 0) {
                $query->Where(function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                });
            }
        })->first()) {
            return $this->sendError('Testimonial exists', ['error' => 'Testimonial exists'], 409);
        }
        if (isset($input['is_default']) && $input['is_default'] == 1) {
            Testimonial::where([['id', '>', 0], ['company_id', '=', $input['company_id']]])->update(['is_default' => 0]);
        }
        if ($id == 0) {
            $activityLogMsg = 'Testimonial created by ' . $this->logged_user->name;

            if ($request->file('image_one')) {

                $image = $request->file('image_one');
                $input['file'] = time() . '-1.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 442, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

//                    $path = $request->file('image_one')->store('public/uploads');
                $input['image_one'] = 'public/uploads/thumbnail/' . $input['file'];
            }
            if ($request->file('image_two')) {
                $image = $request->file('image_two');
                $input['file'] = time() . '-2.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_two'] = 'public/uploads/thumbnail/' . $input['file'];
//                    $path = $request->file('image_two')->store('public/uploads');
//                    $input['image_two'] = $path;
            }
            if ($request->file('image_three')) {
                $image = $request->file('image_three');
                $input['file'] = time() . '-3.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_three'] = 'public/uploads/thumbnail/' . $input['file'];
//                    $path = $request->file('image_three')->store('public/uploads');
//                    $input['image_three'] = $path;
            }
            $testimonial = Testimonial::create($input);
        } else {
            $testimonialData = Testimonial::find($id);
            if ($request->hasFile('image_one')) {
                $request->validate([
                    'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);

                if (Storage::exists($testimonialData->image_one)) {
                    Storage::delete($testimonialData->image_one);
                }
                $image = $request->file('image_one');
                $input['file'] = time() . '-1.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_one'] = 'public/uploads/thumbnail/' . $input['file'];
//                    $path = $request->file('image_one')->store('public/uploads');
//                    $input['image_one'] = $path;
            }
            if ($request->hasFile('image_two')) {
                $request->validate([
                    'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);
                if (Storage::exists($testimonialData->image_two)) {
                    Storage::delete($testimonialData->image_two);
                }
                $image = $request->file('image_two');
                $input['file'] = time() . '-2.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_two'] = 'public/uploads/thumbnail/' . $input['file'];
//                    $path = $request->file('image_two')->store('public/uploads');
//                    $input['image_two'] = $path;
            }

            if ($request->hasFile('image_three')) {
                $request->validate([
                    'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);
                if (Storage::exists($testimonialData->image_three)) {
                    Storage::delete($testimonialData->image_three);
                }
                $image = $request->file('image_three');
                $input['file'] = time() . '-3.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_three'] = 'public/uploads/thumbnail/' . $input['file'];
//                    $path = $request->file('image_three')->store('public/uploads');
//                    $input['image_three'] = $path;
            }

            $testimonial = Testimonial::find($id)->update($input);
            $activityLogMsg = 'Testimonial updated by ' . $this->logged_user->name;
        }


        // Add activity logs
        $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        LogActivity::addToLog($activityLogMsg, $input);
        return $this->sendResponse([], 'Testimonial Saved');
    }

    public function productAutocomplete($search = null)
    {
        $products = Product::select('id', 'name', 'image_one', 'image_two', 'image_three')->where('products.company_id', $this->company_id)->where('name', 'LIKE', '%' . $search . '%')->where('status', 0)->get();
        $response = array();
        foreach ($products as $product) {
            $response[] = array("value" => $product->id, "label" => $product->name, "image_one" => Storage::url($product->image_one), "image_two" => Storage::url($product->image_two), "image_three" => Storage::url($product->image_three));
        }
        return $this->sendResponse($response, 'Product retrieved successfully');
    }

    public function productStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        $input['user_id'] = $this->logged_user->id;
        $input['company_id'] = ($this->company_id) ? $this->company_id : $this->logged_user->id;
        $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];

        if ($id == 0) {
            $validator = Validator::make($input, [
                'name' => 'required',
                'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            ]);
        }
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }


        if (Product::where('name', '=', $input['name'])->where('company_id', $input['company_id'])->where(function ($query) use ($id) {
            if ($id != 0) {
                $query->Where(function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                });
            }
        })->first()) {
            return $this->sendError('Product exists', ['error' => 'Product exists'], 409);
        }
        if ($id == 0) {
            $activityLogMsg = 'Product created by ' . $this->logged_user->name;

            if ($request->file('image_one')) {

                $image = $request->file('image_one');
                $input['file'] = time() . '-1.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 442, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_one'] = 'public/uploads/thumbnail/' . $input['file'];
            }

            if ($request->file('image_two')) {
                $image = $request->file('image_two');
                $input['file'] = time() . '-2.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_two'] = 'public/uploads/thumbnail/' . $input['file'];
            }

            if ($request->file('image_three')) {
                $image = $request->file('image_three');
                $input['file'] = time() . '-3.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_three'] = 'public/uploads/thumbnail/' . $input['file'];
            }
            $product = Product::create($input);
        } else {
            $productData = Product::find($id);

            if ($request->hasFile('image_one')) {
                $request->validate([
                    'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);

                if (Storage::exists($productData->image_one)) {
                    Storage::delete($productData->image_one);
                }
                $image = $request->file('image_one');
                $input['file'] = time() . '-1.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_one'] = 'public/uploads/thumbnail/' . $input['file'];
            }

            if ($request->hasFile('image_two')) {
                $request->validate([
                    'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);
                if (Storage::exists($productData->image_two)) {
                    Storage::delete($productData->image_two);
                }
                $image = $request->file('image_two');
                $input['file'] = time() . '-2.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_two'] = 'public/uploads/thumbnail/' . $input['file'];
            }

            if ($request->hasFile('image_three')) {
                $request->validate([
                    'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);
                if (Storage::exists($productData->image_three)) {
                    Storage::delete($productData->image_three);
                }
                $image = $request->file('image_three');
                $input['file'] = time() . '-3.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/storage/uploads/thumbnail');
                $imgFile = Image::make($image->getRealPath());
                $imgFile->resize(null, 391, function ($constraint) { //412, 391        460, 442
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);

                $input['image_three'] = 'public/uploads/thumbnail/' . $input['file'];
            }
            $product = Product::find($id)->update($input);
            $activityLogMsg = 'Product updated by ' . $this->logged_user->name;
        }

        // Add activity logs
        $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        LogActivity::addToLog($activityLogMsg, $input);

        return response()->json(['success' => 'Product Saved!'], 201);
    }

    public function getFollowUpByEstimateId($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }
        $event = DB::table('events')
            ->leftjoin('estimates', 'events.estimate_id', 'estimates.id')
            ->leftJoin('users', 'events.user_id', 'users.id')
            ->where([
                ['events.estimate_id', '=', $id],
                ['events.event_type', '=', 'estimate'],
            ])
            ->select('events.*', 'estimates.estimate_no as estimate_no', DB::raw("DATE_FORMAT(events.start_date, '%d-%m-%Y') as display_date"), DB::raw("DATE_FORMAT(events.start_date, '%d-%m-%Y %H:%i:%s') as start_date"), 'estimates.customer_name', DB::raw("RIGHT(estimates.customer_address, 10) as mobile_no"), 'estimates.status', 'users.name as user_name')
            ->orderBy('events.id', 'DESC')
            ->orderBy('events.start_date', 'DESC')
            ->get();

        if (is_null($event)) {
            return $this->sendError('Follow up not found', ['Follow up not found'], 422);
        }
        return $this->sendResponse($event, 'Follow up retrieved successfully');
    }

    public function getEstimateList($assign_user, $status, $search = null)
    {
        $data = DB::table('estimates')
            ->where('company_id', $this->company_id)
            ->where(function ($query) use ($assign_user) {
                $query->whereRaw('user_id IN  (' . $assign_user . ')');
                $query->orWhere('user_id', $this->logged_user->id);
            })
            ->where(function ($query) use ($status) {
                if ($status != 'All') {
                    $query->where('status', '=', $status);
                }
            })
            ->where(function ($query) use ($search, $assign_user) {
                if ($search != '') {
                    $query->where('customer_name', 'like', '%' . $search . '%');
                    $query->orWhere('estimate_no', 'like', '%' . $search . '%');
                }
            })
            ->select(array('id', 'customer_name', 'estimate_no', 'estimate_date', 'addless_amount', 'net_amount', DB::raw("RIGHT(customer_address, 10) as mobile_no"), "status"))
            ->orderBy("id", 'desc')
            ->get();
        if (is_null($data)) {
            return $this->sendError('Estimate not found', ['Follow up not found'], 422);
        }
        return $this->sendResponse($data, 'Estimaste retrieved successfully');
    }

    public function getGenerateLink($id)
    {
        $isExist = Estimate::query()->where('id', $id)->first();
        if (!$isExist)
            return $this->sendError('Id not found', [], 400);

        $id = ($id) ? Crypt::encrypt($id) : $id;
        return $this->sendResponse(["url" => url('/quotes/generate-link/' . $id)], 'Link generated successfully');
    }

    public function postEstimateDuplicate(Request $request)
    {
        $input = $request->all();
        $isExist = Estimate::query()->where([["id", "=", $input['id']], ["company_id", "=", $this->company_id]])->first();
        if (!$isExist)
            return $this->sendError('Id not found', [], 400);

        $estimate_auto_number = DB::table('estimate_auto_numbers')->where('company_id', $this->company_id)
            ->select('estimate_prefix', 'estimate_next_no')
            ->first();
        $estimate_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;

//            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        $id = $input['id'];
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
        EstimateAutoNumber::where('company_id', $this->company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
        return $this->sendResponse(['estimate_id' => $insert_id], 'Estimate Copied!');
    }


    public function postEstimateDelete(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }
        $id = [];
        foreach (explode(",", $request->id) as $value) {
            $id[] = $value; // $id[] = Crypt::decrypt($value);
        }
        if (Estimate::whereIn('id', $id)->delete())
            return $this->sendResponse([], 'Estimate Deleted!');
        else
            return $this->sendError("Error in remove estimate", ["error" => "Error in remove estimate"], 400);
    }


    public function postEstimateCreate(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'customer_name' => 'required',
            'customer_id' => 'required',
            'customer_state_id' => 'required',
            'estimate_no' => 'required',
            'estimate_date' => 'required',
            'subtotal' => 'required',
            'net_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error", ["error" => $validator->errors()->all()], 400);
        }

        if (Estimate::where('estimate_no', '=', $input['estimate_no'])->where('company_id', $this->company_id)->first()) {
            return $this->sendError("Estimate exists", ["error" => "Estimate exists"], 400);
        }

        $proposal_template = ProposalTemplates::where('company_id', $this->company_id)->first();

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
        $data['company_id'] = $this->company_id;
        $data['sales_person_id'] = $this->logged_user->id;
        $data['user_id'] = $input['user_id'];
        $data['item_rate_are'] = $input['item_rate_are'];
        $data['customer_notes'] = $input['customer_notes'];
        $data['term_condition'] = $input['term_condition'];


        $data['est_cover_page_title'] = $proposal_template->cover_title;
        $data['est_cover_page_content'] = $proposal_template->cover_content;
        $data['est_cover_page_footer_one'] = $proposal_template->cover_footer_one;
        $data['est_cover_page_footer_two'] = $proposal_template->cover_footer_two;
        $data['est_aboutus_title'] = $proposal_template->aboutas_title;
        $data['est_aboutus_content'] = $proposal_template->aboutas_content;
        $data['est_term_condition_title'] = $proposal_template->terms_title;
        $data['est_term_condition_content'] = $proposal_template->terms_content;

        $data['est_cover_page_title_div'] = $proposal_template->cover_title;
        $data['est_cover_page_content_div'] = $proposal_template->cover_content;
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
        $data['est_cover_page_footer_one_div'] = $proposal_template->cover_footer_one;
        $data['est_cover_page_footer_two_div'] = $proposal_template->cover_footer_two;
        $data['est_aboutus_title_div'] = $proposal_template->aboutas_title;
        $data['est_aboutus_content_div'] = $proposal_template->aboutas_content;
        $data['est_term_condition_title_div'] = $proposal_template->terms_title;
        $data['est_term_condition_content_div'] = $proposal_template->terms_content;

        $data['testimonial_id'] = $input['testimonial_id'];
        $data['product_id'] = $input['product_id'];
        $data['pdf_cover_page_flg'] = 1;
        $data['pdf_about_us_flg'] = 1;
        $data['pdf_product_flg'] = 1;
        $data['pdf_est_flg'] = 1;
        $data['pdf_terms_flg'] = 1;
        $data['pdf_thank_you_flg'] = 1;
        $data['pdf_testimonial_flg'] = 1;
        $data['status'] = 'Draft';
        $data['tilt'] = $input['tilt'];
        $data['azumuth'] = $input['azumuth'];
        $data['no_of_panel'] = $input['no_of_panel'];
        $data['panel_wattage'] = $input['panel_wattage'];

        $estimate = Estimate::create($data);
        $insert_id = $estimate->id;
        if ($insert_id) {
            $estimateArr = $input["items"];
            foreach ($estimateArr as $key => $csm) {
                $estimateArr[$key]['estimate_id'] = $insert_id;
                $estimateArr[$key]['company_id'] = $this->company_id;
                $estimateArr[$key]['user_id'] = $this->logged_user->id;
            }
            EstimateItems::insert($estimateArr);
        }

        $update_estimate = Estimate::query()->select(['est_cover_page_footer_one_div', 'est_cover_page_footer_two_div'])->where('id', '=', $insert_id)->first()->toArray();

        preg_match_all('#\${(.*?)\}#', strip_tags($update_estimate['est_cover_page_footer_one_div']), $match);
        foreach ($match[1] as $key => $value) {
            $valueArr = explode('.', $value);
            $table = $valueArr[0];
            if ($valueArr[0] == 'companies') {
                $tmpId = $this->company_id;
                $table = 'users_views';
            }

            $result = DB::table($table)
                ->where('id', $tmpId)
                ->select([$valueArr[1]])
                ->get()->first();
            $a = $valueArr[1];
            $update_estimate['est_cover_page_footer_one_div'] = str_replace('${' . $value . '}', $result->$a, $update_estimate['est_cover_page_footer_one_div']);
        }

        preg_match_all('#\${(.*?)\}#', strip_tags($update_estimate['est_cover_page_footer_two_div']), $match);
        foreach ($match[1] as $key => $value) {
            $valueArr = explode('.', $value);
            $table = $valueArr[0];
            if ($valueArr[0] == 'estimates') {
                $tmpId = $insert_id;
            }

            $result = DB::table($table)
                ->where('id', $tmpId)
                ->select([$valueArr[1]])
                ->get()->first();
            $a = $valueArr[1];
            $update_estimate['est_cover_page_footer_two_div'] = str_replace('${' . $value . '}', $result->$a, $update_estimate['est_cover_page_footer_two_div']);
        }

        Estimate::where('id', $insert_id)->update(array('est_cover_page_footer_one_div' => $update_estimate['est_cover_page_footer_one_div'], 'est_cover_page_footer_two_div' => $update_estimate['est_cover_page_footer_two_div']));

        $estimate_auto_number = EstimateAutoNumber::where('company_id', $this->company_id)
            ->select('estimate_prefix', 'estimate_next_no')
            ->get()->first();

        $tmp_est_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;
        if ($tmp_est_no == $input['estimate_no']) {
            $tmp_est_no = $estimate_auto_number->estimate_next_no + 1;

            EstimateAutoNumber::where('company_id', $this->company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
        }
        $this->estimateGeneratePdf($insert_id);

        return $this->sendResponse(["estimate_id" => $insert_id], 'Estimate Saved!');
    }

    public function postEstimateUpdate(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
            'customer_name' => 'required',
            'customer_id' => 'required',
            'customer_state_id' => 'required',
            'estimate_no' => 'required',
            'estimate_date' => 'required',
            'subtotal' => 'required',
            'net_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error", ["error" => $validator->errors()->all()], 400);
        }

        $id = $input['id'];

        if (Estimate::where('estimate_no', '=', $input['estimate_no'])->where('company_id', $this->company_id)->where(function ($query) use ($id) {
            if ($id != 0) {
                $query->Where(function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                });
            }
        })->first()) {
            return $this->sendError("Estimate exists", ["error" => "Estimate exists"], 400);
        }

        $proposal_template = ProposalTemplates::where('company_id', $this->company_id)->first();

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
        $data['company_id'] = $this->company_id;
        $data['sales_person_id'] = $this->logged_user->id;
        $data['user_id'] = $input['user_id'];
        $data['item_rate_are'] = $input['item_rate_are'];
        $data['customer_notes'] = $input['customer_notes'];
        $data['term_condition'] = $input['term_condition'];

        $data['est_cover_page_title'] = $proposal_template->cover_title;
        $data['est_cover_page_content'] = $proposal_template->cover_content;
        $data['est_cover_page_footer_one'] = $proposal_template->cover_footer_one;
        $data['est_cover_page_footer_two'] = $proposal_template->cover_footer_two;
        $data['est_aboutus_title'] = $proposal_template->aboutas_title;
        $data['est_aboutus_content'] = $proposal_template->aboutas_content;
        $data['est_term_condition_title'] = $proposal_template->terms_title;
        $data['est_term_condition_content'] = $proposal_template->terms_content;

        $data['est_cover_page_title_div'] = $proposal_template->cover_title;
        $data['est_cover_page_content_div'] = $proposal_template->cover_content;
        preg_match_all('#\${(.*?)\}#', strip_tags($data['est_cover_page_content_div']), $match);
        foreach ($match[1] as $key => $value) {
            $valueArr = explode('.', $value);
            if ($valueArr[0] == 'customers') {
                $common_id = $data['customer_id'];
                $table = 'customers_views';
            }

            if ($valueArr[0] == 'companies') {
                $table = 'users_views';
            }

            if ($valueArr[0] == 'estimates') {
                $common_id = $data['estimate_id'];
            }
            $result = DB::table($table)
                ->where('id', $common_id)
                ->select([$valueArr[1]])
                ->get()->first();
            $a = $valueArr[1];

            $data['est_cover_page_content_div'] = str_replace('${' . $value . '}', $result->$a, $data['est_cover_page_content_div']);
        }
        $data['est_cover_page_footer_one_div'] = $proposal_template->cover_footer_one;
        $data['est_cover_page_footer_two_div'] = $proposal_template->cover_footer_two;
        $data['est_aboutus_title_div'] = $proposal_template->aboutas_title;
        $data['est_aboutus_content_div'] = $proposal_template->aboutas_content;
        $data['est_term_condition_title_div'] = $proposal_template->terms_title;
        $data['est_term_condition_content_div'] = $proposal_template->terms_content;

        $data['testimonial_id'] = $input['testimonial_id'];
        $data['product_id'] = $input['product_id'];
        /* $data['pdf_cover_page_flg'] = 1;
         $data['pdf_about_us_flg'] = 1;
         $data['pdf_product_flg'] = 1;
         $data['pdf_est_flg'] = 1;
         $data['pdf_terms_flg'] = 1;
         $data['pdf_thank_you_flg'] = 1;
         $data['pdf_testimonial_flg'] = 1;*/
        $data['tilt'] = $input['tilt'];
        $data['azumuth'] = $input['azumuth'];
        $data['no_of_panel'] = $input['no_of_panel'];
        $data['panel_wattage'] = $input['panel_wattage'];

        $estimate = Estimate::find($id)->update($data);

        if ($estimate) {
            $estimateArr = $input["items"];
            foreach ($estimateArr as $key => $csm) {
                $estimateArr[$key]['estimate_id'] = $id;
                $estimateArr[$key]['company_id'] = $this->company_id;
                $estimateArr[$key]['user_id'] = $this->logged_user->id;
            }
            EstimateItems::insert($estimateArr);
        }

        $update_estimate = Estimate::query()->select(['est_cover_page_footer_one_div', 'est_cover_page_footer_two_div'])->where('id', '=', $id)->first()->toArray();

        preg_match_all('#\${(.*?)\}#', strip_tags($update_estimate['est_cover_page_footer_one_div']), $match);
        foreach ($match[1] as $key => $value) {
            $valueArr = explode('.', $value);
            $table = $valueArr[0];
            if ($valueArr[0] == 'companies') {
                $tmpId = $this->company_id;
                $table = 'users_views';
            }

            $result = DB::table($table)
                ->where('id', $tmpId)
                ->select([$valueArr[1]])
                ->get()->first();
            $a = $valueArr[1];
            $update_estimate['est_cover_page_footer_one_div'] = str_replace('${' . $value . '}', $result->$a, $update_estimate['est_cover_page_footer_one_div']);
        }

        preg_match_all('#\${(.*?)\}#', strip_tags($update_estimate['est_cover_page_footer_two_div']), $match);
        foreach ($match[1] as $key => $value) {
            $valueArr = explode('.', $value);
            $table = $valueArr[0];
            if ($valueArr[0] == 'estimates') {
                $tmpId = $id;
            }

            $result = DB::table($table)
                ->where('id', $tmpId)
                ->select([$valueArr[1]])
                ->get()->first();
            $a = $valueArr[1];
            $update_estimate['est_cover_page_footer_two_div'] = str_replace('${' . $value . '}', $result->$a, $update_estimate['est_cover_page_footer_two_div']);
        }

        Estimate::where('id', $id)->update(array('est_cover_page_footer_one_div' => $update_estimate['est_cover_page_footer_one_div'], 'est_cover_page_footer_two_div' => $update_estimate['est_cover_page_footer_two_div']));

        $estimate_auto_number = EstimateAutoNumber::where('company_id', $this->company_id)
            ->select('estimate_prefix', 'estimate_next_no')
            ->get()->first();

        $tmp_est_no = $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no;
        if ($tmp_est_no == $input['estimate_no']) {
            $tmp_est_no = $estimate_auto_number->estimate_next_no + 1;

            EstimateAutoNumber::where('company_id', $this->company_id)->update(array('estimate_next_no' => '00000' . $tmp_est_no));
        }

        $this->estimateGeneratePdf($id);

        return $this->sendResponse(["estimate_id" => $id], 'Estimate Saved!');
    }

    public function estimateGeneratePdf($insert_id)
    {
        //PDF start
        $estimate = Estimate::where([["id", $insert_id], ["company_id", "=", $this->company_id]])->get()->first();
        $estimate_items = EstimateItems::where([["estimate_id", $insert_id], ["company_id", "=", $this->company_id]])->orderBy('id', 'ASC')->get(["*"]);
        $proposal_template = ProposalTemplates::where('company_id', $this->company_id)->first();
        $company_data = ViewUserData::where("id", $this->company_id)->orderBy('id', 'ASC')->get()->first();
        $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $this->company_id)->get();


        $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $this->company_id)->get()->first();
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
        if ($estimate['pdf_cover_page_flg']) {
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
        if ($estimate['pdf_about_us_flg']) {
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
        if ($estimate['pdf_product_flg']) {
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
        if ($estimate['pdf_est_flg']) {
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
        if ($estimate['pdf_terms_flg']) {
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
        if ($estimate['pdf_testimonial_flg']) {
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

        //$pdf::Output('hello_world.pdf', 'I');
        $pdf::Output(public_path('storage/document/' . $this->company_id . '/' . $estimate['estimate_no'] . '.pdf'), 'F');
    }

    public function getEstimateNumber()
    {
        $estimate_auto_number = EstimateAutoNumber::select(["estimate_prefix", "estimate_next_no"])->where('company_id', $this->company_id)->get()->first();
        return $this->sendResponse(["estimate_no" => $estimate_auto_number->estimate_prefix . $estimate_auto_number->estimate_next_no], 'Estimate Number retrieved!');
    }

    public function getSalesman()
    {
        $user_list = [];
        if ($this->logged_user->company_id == null) {
            $user_list['salesman'] = User::query()->select(["name", "id", "email"])
                ->where('status', 'Approved')
                ->where('company_id', $this->company_id)
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('user_id', $this->company_id);
                        $query->orwhere('role_id', 6);
                    });

                })
                ->get();
        }
        $estimate_auto_number = EstimateAutoNumber::select(["estimate_prefix", "estimate_next_no"])->where('company_id', $this->company_id)->get()->first();
        return $this->sendResponse($user_list, 'Salesman retrieved!');
    }

    public function getEstimateSingle($id)
    {
        $data['estimate'] = Estimate::where([["id", $id], ["company_id", "=", $this->company_id]])->get()->first();
        if (!$data['estimate']) {
            return $this->sendError("Estimate id not found", ["error" => "Estimate id not found"], 400);
        }

        $data['estimate_items'] = EstimateItems::where([["estimate_id", $id], ["company_id", "=", $this->company_id]])->orderBy('id', 'ASC')->get(["*"]);
        return $this->sendResponse($data, 'Estimate retrieved!');
    }

}
