<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        return Socialite::driver('facebook')
            ->scopes([
                'email',
                // 'pages_show_list',
                // 'pages_read_engagement',
                // 'pages_read_user_content',
                // 'pages_manage_ads',
                // 'read_insights'
            ])
            ->redirect();
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


        $user = User::where('provider_id', $facebookUser->id)->first();

        if ($user) {
            // User exists — update other fields, but NOT password
            $user->update([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'provider' => 'facebook',
                'provider_id' => $facebookUser->id,
                'avatar' => $facebookUser->avatar_original,
            ]);
        } else {
            // New user — create with password
            $user = User::create([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'password' => null,
                'provider' => 'facebook',
                'provider_id' => $facebookUser->id,
                'fb_access_token' => $facebookUser->token,
                'role' => 'user',
                'avatar' => $facebookUser->avatar_original,
                'email_verified_at' => now(),
            ]);
        }


        Auth::login($user);

        // Page ID এবং Token সংগ্রহ
        $response = Http::withToken($facebookUser->token)
            ->get('https://graph.facebook.com/me/accounts');
        $pages = $response->json();

        if (!empty($pages['data'])) {
            $user->fb_page_id = $pages['data'][0]['id'];
            $user->fb_page_token = $pages['data'][0]['access_token'];
            $user->save();
        }

        return redirect()->route('user.dashboard')->with('success', 'You have successfully logged in with Facebook.');
    }
}
