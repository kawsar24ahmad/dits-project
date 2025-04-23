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
                // 'email',
                // 'user_posts',
                'pages_show_list',
                // 'pages_read_engagement',
                // 'pages_read_user_content',
                // 'pages_manage_ads',
                'read_insights',
                'pages_read_engagement',
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

        // Step 1: Get Pages
    $pagesResponse = Http::withToken($facebookUser->token)
    ->get('https://graph.facebook.com/v19.0/me/accounts');

    $pages = $pagesResponse->json();

    if (isset($pages['data'][0])) {
        $pageId = $pages['data'][0]['id'];
        $pageAccessToken = $pages['data'][0]['access_token'];

        // Step 2: Save Page Info to User
        $user->fb_page_id = $pageId;
        $user->fb_page_token = $pageAccessToken;
        $user->save();
    } else {
        return redirect()->route('login')->with('error', 'No Facebook pages found.');
    }


        return redirect()->route('user.dashboard')->with('success', 'You have successfully logged in with Facebook.');
    }
}
