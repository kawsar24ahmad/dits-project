<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\FacebookPage;
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
                'pages_show_list',
                'pages_read_engagement',
                'pages_read_user_content',
                'read_insights',
                'pages_manage_posts',
                // 'user_posts',
                // 'pages_read_engagement',
                // 'pages_manage_ads',
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

        // fetch user profile photo
        $response = Http::withToken($facebookUser->token)
            ->get('https://graph.facebook.com/v19.0/me?fields=picture.type(large)');
        if ($response->successful()) {
            $userData = $response->json();
            $user->update([
                'avatar' => $userData['picture']['data']['url'] ?? null,
            ]);
        }


        // Fetch Pages
        $pagesResponse = Http::withToken($facebookUser->token)
            ->get('https://graph.facebook.com/v19.0/me/accounts?fields=id,name,username,category,access_token,picture,cover');

        if ($pagesResponse->successful()) {
            $pages = $pagesResponse->json()['data'] ?? [];

            foreach ($pages as $page) {
                // Try to find existing page or create a new instance (not saved yet)
                $savedPage = FacebookPage::firstOrNew(['page_id' => $page['id']]);

                // Assign or update fields (but only set status if it's a new record)
                $savedPage->user_id = $user->id;
                $savedPage->page_name = $page['name'];
                $savedPage->category = $page['category'] ?? null;
                $savedPage->page_access_token = $page['access_token'];
                $savedPage->profile_picture = $page['picture']['data']['url'] ?? null;
                $savedPage->cover_photo = $page['cover']['source'] ?? null;
                $savedPage->page_username = $page['username'] ?? null;
                $savedPage->likes = $page['fan_count'] ?? null;

                // Only set status if this is a new record
                if (!$savedPage->exists) {
                    $savedPage->status = 'inactive';
                }

                $savedPage->save();

                // Fetch followers count using the page token
                $pageDetails = Http::withToken($page['access_token'])
                    ->get("https://graph.facebook.com/{$page['id']}?fields=followers_count");

                if ($pageDetails->successful()) {
                    $followers = $pageDetails->json()['followers_count'] ?? null;
                    $savedPage->update(['followers' => $followers]);
                }
            }




            return redirect()->route('user.dashboard')->with('success', 'You have successfully logged in with Facebook.');
        }
    }
}
