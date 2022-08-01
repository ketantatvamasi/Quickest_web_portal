<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Storage};
use Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
//            return response()->json($validator->errors());
        }

        $user = User::create([
            'is_owner' => $request->is_owner,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password)
        ]);
        $otp = rand(1000, 9999);

        if (User::where('email', '=', $request->email)->update(['otp' => $otp])) {
            $mail_details = [
                'subject' => 'Testing Application OTP',
                'body' => 'Your OTP is : ' . $otp
            ];

            \Mail::to($request->email)->send(new \App\Mail\SendOtpMail($mail_details));
            return $this->sendResponse([], 'OTP sent successfully');
//            return response(["status" => 200, "message" => "OTP sent successfully"]);
        } else {
            return $this->sendError('Validation error', ['error' => "Invalid"], 400);
        }
//        Auth::login($user, true);
//        $user->sendEmailVerificationNotification();
//        $token = $user->createToken('auth_token')->plainTextToken;
//
//        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Unauthorized', ['error' => 'Unauthorised'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $abc = $this->multilevel_categories($user->id);
        $array = $this->nestedToSingle($abc);
        if (empty($array)) {
            $array[] = $user->id;
        }

        if ($user->profile_icon == null) {
            $user->profile_icon = null;
        } else {
            $user->profile_icon = Storage::url($user->profile_icon);
        }
        $data['user'] = $user;
        $data['assign_user'] = implode(',', $array);
        $data['access_token'] = $token;
        $data['token_type'] = 'Bearer';
        return $this->sendResponse($data, 'User signed in');
    }

    public function multilevel_categories($parent_id = 0)
    {
        $query = DB::table('users')->select('id')->where('user_id', $parent_id)->get();

        $catData = [];
        if ($query->count() > 0) {
            foreach ($query as $row) {
                $catData[] = [
                    'id' => $row->id,
                    'nested_categories' => $this->multilevel_categories($row->id)
                ];
            }
            return $catData;

        } else {
            return $catData = [];
        }
    }

    public function nestedToSingle(array $array)
    {
        $singleDimArray = [];

        foreach ($array as $item) {

            if (is_array($item)) {
                $singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));

            } else {
                $singleDimArray[] = $item;
            }
        }

        return $singleDimArray;
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_icon == null) {
            $user->profile_icon = null;
        } else {
            $user->profile_icon = Storage::url($user->profile_icon);
        }
        return $user;
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse(["msg" => 'You have successfully logged out'], 'You have successfully logged out');
//        return [
//            'message' => 'You have successfully logged out and the token was successfully deleted'
//        ];
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
//            return response()->json($validator->errors());
        }
        $otp = rand(1000, 9999);

        $user = User::where('email', '=', $request->email)->update(['otp' => $otp]);

        if ($user) {
            $mail_details = [
                'subject' => 'Testing Application OTP',
                'body' => 'Your OTP is : ' . $otp
            ];

            \Mail::to($request->email)->send(new \App\Mail\SendOtpMail($mail_details));
            return response(["status" => 200, 'message' => 'OTP sent successfully']);
        } else {
            return response(["status" => 200, 'message' => 'Invalid']);
        }
    }

    public function verifyOtp(Request $request)
    {
        $user = User::where([['email', '=', $request->email], ['otp', '=', $request->otp]])->first();
        if ($user) {
            auth()->login($user, true);
            User::where('email', '=', $request->email)->update(['otp' => null]);
//            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            $token = $user->createToken('auth_token')->plainTextToken;

            $abc = $this->multilevel_categories($user->id);
            $array = $this->nestedToSingle($abc);
            if (empty($array)) {
                $array[] = $user->id;
            }

            if ($user->profile_icon == null) {
                $user->profile_icon = null;
            } else {
                $user->profile_icon = Storage::url($user->profile_icon);
            }
            $data['user'] = $user;
            $data['assign_user'] = implode(',', $array);
            $data['access_token'] = $token;
            $data['token_type'] = 'Bearer';
            return $this->sendResponse($data, 'User signed in');
//            return response(["status" => 200, "message" => "Success", 'user' => auth()->user(), 'access_token' => $accessToken]);
        } else {
            return response(["status" => 401, 'message' => 'Invalid']);
        }
    }

    public function verifyOtpRegister(Request $request)
    {
        $user = User::where([['email', '=', $request->email], ['otp', '=', $request->otp]])->first();
        if ($user) {
            User::where('email', '=', $request->email)->update(['otp' => null,'email_verified_at'=>date('Y-m-d H:i:s')]);
            return $this->sendResponse([], 'Successfully OTP verified');
        } else {
            return $this->sendError('Invalid OTP', ['error' => "Invalid OTP"], 400);
        }
    }

    public function isUserVerified(Request $request)
    {
        if(Auth::user()->hasVerifiedEmail()){
            return $this->sendResponse([], 'User verified.');
        }else{
            return $this->sendError('User dont verified.', ['error' => "User dont verified."], 200);
        }
    }

    public function isCheckUserStatus(Request $request)
    {
        if (Auth::user()->status == 'Approved') {
            return $this->sendResponse([['account_status'=>'Approved']], 'Your account is activated');
        }

        if (Auth::user()->status == 'New') {
            return $this->sendResponse([['account_status'=>'New']], 'Please fill below field');
        }

        if (Auth::user()->status == 'Pending') {
            return $this->sendResponse([['account_status'=>'Pending']], 'You have registered successfully. Your account will we actived soon.');
        }

        if (Auth::user()->status == 'Rejected') {
            return $this->sendResponse([['account_status'=>'Rejected']], 'Your account is rejected.');
        }
    }

    public function companyProfileUpdate(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        $validator = \Illuminate\Support\Facades\Validator::make($input, [
            'company_name' => 'required',
            'mobile_no' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', ['error' => $validator->errors()->all()], 400);
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
        return $this->sendResponse([], 'Profile Updated!');

    }
}
