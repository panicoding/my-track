<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RefreshSpotifyTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->spotifyAccountConnected()) {
            $spotifyPayload = Auth::user()->spotify_payload;

                $response = Http::asForm()
                    ->withBasicAuth(config('services.spotify.client_id'), config('services.spotify.client_secret'))
                    ->post('https://accounts.spotify.com/api/token', [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $spotifyPayload['refresh_token']
                    ])
                    ->json();

                DB::table('users')
                    ->where('id', Auth::user()->id)
                    ->update([
                        'spotify_payload->access_token' => $response['access_token'],
                    ]);

                Auth::user()->touch();
        }

        return $next($request);
    }
}
