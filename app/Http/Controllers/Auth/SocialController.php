<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    protected $providers = [
        'facebook',
        'spotify',
    ];

    protected $scopes = [
        'facebook' => [
            'user_friends',
            'email',
        ],
        'spotify' => [
            'user-read-private',
            'user-read-email',
            'user-read-currently-playing',
            'user-read-playback-position',
            'user-library-read',
            'user-top-read',
            'playlist-read-private',
            'user-read-playback-state',
            'user-read-recently-played',
        ]
    ];

    public function redirectToProvider(string $driver): RedirectResponse
    {
        return Socialite::driver($driver)
            ->scopes($this->scopes[$driver])
            ->redirect();
    }

    public function handleProviderCallback(string $driver): RedirectResponse
    {
        $user = Socialite::driver($driver)->user();

        if ('spotify' == $driver) {
            return $this->connectSpotifyAccount($user);
        }

        return $this->handleUser($user, $driver);
    }

    public function handleUser($providerUser, $driver)
    {
        $user = User::where('email', $providerUser->getEmail())->first();

        if ($user) {
            $user->update([
                'avatar' => $providerUser->getAvatar(),
                'provider' => $driver,
                'provider_id' => $providerUser->getId(),
                'access_token' => $providerUser->token,
            ]);
        } else {
            $user = User::create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'avatar' => $providerUser->getAvatar(),
                'provider' => $driver,
                'provider_id' => $providerUser->getId(),
                'access_token' => $providerUser->token,
                'password' => bcrypt($providerUser->getName() . $providerUser->getId() . $providerUser->token)
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }

    protected function connectSpotifyAccount($user)
    {
        $payload = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'provider_id' => $user->getId(),
            'access_token' => $user->token,
            'refresh_token' => $user->refreshToken,
            'expiration_date' => Carbon::now()->addSeconds($user->expiresIn),
        ];

        Auth::user()->update([
            'spotify_payload' => $payload,
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
