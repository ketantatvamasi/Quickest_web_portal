<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;


class GoogleSocialiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('social_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect('/dashboard');

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'social_type'=> 'google',
                    'password' => Hash::make('12345678'),
                    'permissions' => 'user_view,user_edit,user_delete,unit_view,unit_edit,unit_delete,item_view,item_edit,item_delete,product_view,product_edit,product_delete,country_view,country_edit,country_delete,estimates_view,estimates_edit,estimates_delete,state_view,state_edit,state_delete,roles_view,roles_edit,roles_delete,customer_view,customer_edit,customer_delete,city_view,city_edit,city_delete'
                ]);

                Auth::login($newUser);

                return redirect('/dashboard');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
