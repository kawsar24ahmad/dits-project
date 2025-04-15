<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback(Request $request)
    {
        $facebookUser = Socialite::driver('facebook')->user();
        // dd( $facebookUser);


        $user = User::where('facebook_id', $facebookUser->id)->first();

        if ($user) {
            // User exists — update other fields, but NOT password
            $user->update([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
                'avatar' => $facebookUser->avatar_original,

            ]);
        } else {
            // New user — create with password
            $user = User::create([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'password' => null,
                'facebook_id' => $facebookUser->id,
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
                'role_id'=> 1,
                'role' => 'customer',
                'avatar' => $facebookUser->avatar_original,
                'email_verified_at' => now(),
            ]);
        }

        // Auth::login($user);

        // $authUser = auth()->user();
        // dd($authUser->password);
        if (is_null($user->password)) {
            return redirect()->route('password.set', $user->facebook_id);
        }
        Auth::login($user);

        return redirect('/customer')->with('success', 'You have successfully logged in with Facebook.');
    }
}
