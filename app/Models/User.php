<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'provider',
        'provider_id',
        'access_token',
        'spotify_payload',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'spotify_payload' => 'array',
    ];

    public function spotifyAccountConnected()
    {
        return ! is_null($this->spotify_payload);
    }

    public function currentlyPlaying()
    {
        if (! $this->spotifyAccountConnected()) {
            return false;
        }

        $response = Http::withToken($this->spotify_payload['access_token'])
            ->get('https://api.spotify.com/v1/me/player/currently-playing?market=ES&additional_types=episode');

        if (! $response->successful()) {
            return false;
        }

        $player = $response->json();

        if (! $player['is_playing']) {
            return false;
        }

        return $player;
    }

    public function facebookFriends()
    {
        return collect(app('facebook')->get('/me/friends')->getDecodedBody()['data'])->map(function ($friend) {
            return User::where('provider_id', $friend['id'])->first();
        })->reject(function ($user) {
            return is_null($user) || ! $user->currentlyPlaying();
        });
    }
}
