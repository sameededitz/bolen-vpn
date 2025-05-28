<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Laravel\Sanctum\NewAccessToken;
use App\Models\User;

trait ManagesUserDevices
{
    public function createOrRefreshDeviceToken(User $user, Request $request): NewAccessToken
    {
        $deviceId = $request->input('device_id');

        if (!$deviceId) {
            abort(422, 'Device ID is required.');
        }

        // Delete previous device entry if same device_id exists
        /** @var \App\Models\UserDevice $existingDevice **/
        $existingDevice = $user->devices()->where('device_id', $deviceId)->first();
        if ($existingDevice) {
            if ($existingDevice->token) {
                $existingDevice->token()->delete();
            }
            $existingDevice->delete();
        }

        // Device info using Jenssegers Agent
        $agent = new Agent();
        $deviceName = $request->input('device_name') ?? $agent->device() ?? 'Unknown Device';
        $platform = $request->input('platform') ?? $agent->platform() ?? 'Unknown Platform';
        $deviceType = $request->input('device_type') ??
            ($agent->isTablet() ? 'tablet' : ($agent->isMobile() ? 'mobile' : ($agent->isDesktop() ? 'desktop' : 'web')));

        // Create a new token
        $token = $user->createToken($deviceName, ['*'], now()->addDays(30));

        // Save device
        $user->devices()->create([
            'device_id' => $deviceId,
            'token_id' => $token->accessToken->id,
            'device_name' => $deviceName,
            'device_type' => $deviceType,
            'platform' => $platform,
            'ip_address' => $request->ip(),
            'last_active_at' => now(),
        ]);

        return $token;
    }
}
