<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class VerificationController extends Controller
{
    public function verify($id, Request $request)
    {
        echo "okay"; die;
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

//        return redirect()->to('/');
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail())
        {
            return response()->json(["msg" => "Email already verified."], 400);
        }
        auth()->user()->sendEmailVerificationNotification();
        return response()->json(["msg" => "Email verification link sent on your email id"]);
    }
}
