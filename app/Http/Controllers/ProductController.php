<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use LogActivity;
use Auth;
use Image;

class ProductController extends Controller
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
            $totalRecords = Product::select('count(*) as allcount')->where(function ($query) use ($name, $status) {
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
            $totalRecordswithFilter = Product::select('count(*) as allcount')->where('name', 'like', '%' . $search_arr . '%')->where(function ($query) use ($name, $status) {
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


//            DB::enableQueryLog();
            $records = DB::table('products')
                ->where('company_id',$company_id)
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
                ->orWhere(function ($query) use ($search_arr) {
                    if ($search_arr) {
                        $query->orWhere(function ($query) use ($search_arr) {
                            $query->where('description', 'like', '%' . $search_arr . '%');
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
                $description = $record->description;
                $status = $record->status;
                $i++;
                $data[] = array(
                    "id" => $i,
                    "name" => $name,
                    "image_one" => Storage::url($record->image_one),
                    "image_two" => Storage::url($record->image_two),
                    "image_three" => Storage::url($record->image_three),
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

        return view('product');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
//            dd($input);
            $validator = Validator::make($input, [
                'name' => 'required',
            ]);


            $user = Auth::user();

            $input['user_id'] = $user->id;
            $input['company_id'] = ($user->company_id)? $user->company_id : $user->id;
            $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            if($id==0){
                $validator = Validator::make($input, [
                    'name' => 'required',
                    'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                    'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                    'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                ]);
            }
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);;
            }


            if (Product::where('name', '=', $input['name'])->where('company_id', $input['company_id'])->where(function ($query) use ($id) {
                if ($id != 0) {
                    $query->Where(function ($query) use ($id) {
                        $query->where('id', '!=', $id);
                    });
                }
            })->first()) {
                return response()->json(['success' => 'Product exists!'], 409);
            }
            if ($id == 0) {
                $activityLogMsg = 'Product created by ' . $user->name;

                if ($request->file('image_one')) {

                    $image = $request->file('image_one');
                    $input['file'] = time().'-1.'.$image->getClientOriginalExtension();

                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 442, function($constraint) { //412, 391        460, 442
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);




//                    $path = $request->file('image_one')->store('public/uploads');
                    $input['image_one'] = 'public/uploads/thumbnail/'.$input['file'];
                }

                if ($request->file('image_two')) {
                    $image = $request->file('image_two');
                    $input['file'] = time().'-2.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 391, function($constraint) { //412, 391        460, 442
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);

                    $input['image_two'] = 'public/uploads/thumbnail/'.$input['file'];
//                    $path = $request->file('image_two')->store('public/uploads');
//                    $input['image_two'] = $path;
                }

                if ($request->file('image_three')) {
                    $image = $request->file('image_three');
                    $input['file'] = time().'-3.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 391, function($constraint) { //412, 391        460, 442
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);

                    $input['image_three'] = 'public/uploads/thumbnail/'.$input['file'];
//                    $path = $request->file('image_three')->store('public/uploads');
//                    $input['image_three'] = $path;
                }
                $product = Product::create($input);
            } else {
                $productData = Product::find($id);

                if($request->hasFile('image_one')){
                    $request->validate([
                        'image_one' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                    ]);

                    if(Storage::exists($productData->image_one))
                    {
                        Storage::delete($productData->image_one);
                    }
                    $image = $request->file('image_one');
                    $input['file'] = time().'-1.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 391, function($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);

                    $input['image_one'] = 'public/uploads/thumbnail/'.$input['file'];
//                    $path = $request->file('image_one')->store('public/uploads');
//                    $input['image_one'] = $path;
                }

                if($request->hasFile('image_two')){
                    $request->validate([
                        'image_two' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                    ]);
                    if(Storage::exists($productData->image_two))
                    {
                        Storage::delete($productData->image_two);
                    }
                    $image = $request->file('image_two');
                    $input['file'] = time().'-2.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 391, function($constraint) { //412, 391        460, 442
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);

                    $input['image_two'] = 'public/uploads/thumbnail/'.$input['file'];
//                    $path = $request->file('image_two')->store('public/uploads');
//                    $input['image_two'] = $path;
                }

                if($request->hasFile('image_three')){
                    $request->validate([
                        'image_three' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                    ]);
                    if(Storage::exists($productData->image_three))
                    {
                        Storage::delete($productData->image_three);
                    }
                    $image = $request->file('image_three');
                    $input['file'] = time().'-3.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/storage/uploads/thumbnail');
                    $imgFile = Image::make($image->getRealPath());
                    $imgFile->resize(null, 391, function($constraint) { //412, 391        460, 442
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$input['file']);

                    $input['image_three'] = 'public/uploads/thumbnail/'.$input['file'];
//                    $path = $request->file('image_three')->store('public/uploads');
//                    $input['image_three'] = $path;
                }
                $product = Product::find($id)->update($input);
                $activityLogMsg = 'Product updated by ' . $user->name;
            }

            // Add activity logs
            $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
            LogActivity::addToLog($activityLogMsg, $input);

            return response()->json(['success' => 'Product Saved!'], 201);
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

            $product = Product::find($id)->toArray();

            if (is_null($product)) {
                return response()->json(['success' => 'Product not found!'], 422);
            }
            $product['id'] = Crypt::encrypt($product['id']);
            return response()->json([
                "success" => true,
                "message" => "Product retrieved successfully.",
                "data" => $product
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
            $product = Product::whereIn('id', $id)->delete();

            LogActivity::addToLog('Product deleted by ' . $user->name, $id);
            return response()->json(['success' => 'Product Deleted!'], 201);
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
            if (!Product::whereIn('id', $id)->first()) {
                return response()->json(['success' => 'Product exists!'], 422);
            }
            $product = Product::whereIn('id', $id)->update(["status" => $input['status']]);

            $data['id'] = $id;
            $data['status'] = ($input['status'] == 0) ? 'Active' : 'Deactive';
            LogActivity::addToLog('Product status updated by ' . $user->name, $data);

            return response()->json(['success' => 'Product status updated!'], 201);
        }
    }

    public function productAutocomplete(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            $user = Auth::user();
            $company_id = ($user->company_id)? $user->company_id : $user->id;
            $products = Product::select('id','name','image_one','image_two','image_three')->where('products.company_id',$company_id)->where('name', 'LIKE', '%' . $search . '%')->where('status', 0)->get();
            $response = array();
            foreach($products as $product){
                $response[] = array("value"=>$product->id,"label"=>$product->name, "image_one"=>Storage::url($product->image_one),"image_two"=>Storage::url($product->image_two),"image_three"=>Storage::url($product->image_three));
            }

            return response()->json($response);
//            return response()->json($result);
        }
    }
}
