<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if(Auth::check() && $user->currentAccessToken()) {
            $tokenId = $request->user()->currentAccessToken()->id;

            $device = $request->user()->devices()
                ->where('token_id', $tokenId)
                ->first();

            if ($device) {
                $device->update([
                    'last_active_at' => now()
                ]);
            }
        }

        return $next($request);
    }
}
