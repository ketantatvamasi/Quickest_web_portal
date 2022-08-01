<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Crypt, Validator};
use LogActivity;

class UnitController extends BaseController
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

    public function __invoke(Request $request)
    {
        $unit = Unit::query()->where('company_id', $this->company_id)->get();
        return $this->sendResponse($unit, 'Unit retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        $input['user_id'] = $this->logged_user->id;
        $input['company_id'] = $this->company_id;
        $id = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        if (Unit::query()->where(['name', '=', $input['name']],['company_id','=', $input['company_id']])->select('id')->where(function ($query) use ($id) {
            if ($id !== 0) {
                $query->Where(function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                });
            }
        })->first()) {
            return $this->sendError('Unit exists', ['error' => 'Unit exists'], 409);
        }
        if ($id === 0) {
            $activityLogMsg = 'Unit created by ' . $this->logged_user->name;
            $unit = Unit::query()->create($input);
        } else {
            $unit = Unit::query()->find($id)->update($input);
            $activityLogMsg = 'Unit updated by ' . $this->logged_user->name;
        }

        // Add activity logs
        $input['id'] = ($input['id']) ? Crypt::decrypt($input['id']) : $input['id'];
        LogActivity::addToLog($activityLogMsg, $input);
        return $this->sendResponse([], 'Unit Saved');
    }

    public function show(Request $request)
    {
        $input = $request->all();
        $id = Crypt::decrypt($input['id']);
        $validator = Validator::make($input, [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        $unit = Unit::query()->find($id);

        if (is_null($unit)) {
            return $this->sendError('Unit not found', ['error' => 'Unit not found!'], 422);
        }
        $unit['id'] = Crypt::encrypt($unit['id']);
        return $this->sendResponse($unit, 'Unit retrieved successfully');
    }

    public function destroy(Request $request)
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
            $id[] = Crypt::decrypt($value);
        }
        $unit = Unit::query()->whereIn('id', $id)->delete();

        LogActivity::addToLog('Unit deleted by ' . $this->logged_user->name, $id);
        return $this->sendResponse([], 'Unit Deleted!');
    }

    public function editStatus(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
        }

        $id = [];
        foreach (explode(",", $input['id']) as $value) {
            $id[] = Crypt::decrypt($value);
        }

        $unit = Unit::query()->whereIn('id', $id)->update(["status" => $input['status']]);

        $data['id'] = $id;
        $data['status'] = ($input['status'] === 0) ? 'Active' : 'Deactive';
        LogActivity::addToLog('Unit status updated by ' . $this->logged_user->name, $data);

        return $this->sendResponse([], 'Unit status updated!');
    }
}
