<?php

namespace App\Http\Controllers;

use App\Models\{admin\CompanyCategory, City, Country, State, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use LogActivity,Auth;

class ProfileController extends Controller
{
    public function edit(User $user)
    {

        $user = Auth::user();
        if ($user->status == 'Approved') {
            return redirect("dashboard");
        }
        $countries = Country::select(["name", "id"])->where('status','=',0)->get();
        $company_categories = CompanyCategory::select(["name", "id"])->where('status','=',0)->get();
        return view('auth.profile', compact('user','countries','company_categories'));
    }
    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)->where('status','=',0)
            ->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)->where('status','=',0)
            ->get(["name","id"]);
        return response()->json($data);
    }


    public function update(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        $validator = Validator::make($input, [
            'company_name' => 'required',
            'mobile_no' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);;
        }

        if($request->hasFile('profile_icon')){
            $request->validate([
                'profile_icon' => 'image|mimes:jpeg,png,jpg|max:1024',
            ]);
            if(Storage::exists($user->profile_icon))
            {
                Storage::delete($user->profile_icon);
            }

            $path = $request->file('profile_icon')->store('public/profile');
            $input['profile_icon'] = $path;
        }


        $input['status'] = 'Pending';

        User::find($user->id)->update($input);
        $activityLogMsg = 'Profile updated by ' . $user->name;
        LogActivity::addToLog($activityLogMsg, $input);

        return response()->json(['success' => 'Profile Updated!'], 201);

    }
}
