<?php

namespace App\Http\Controllers\Api;

use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Resources\UserDeviceResource;

class UserController extends Controller
{
    public function user()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'user' => new UserResource($user)
        ], 200);
    }
    
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user),
        ], 200);
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully',
        ], 200);
    }
    public function deleteAccount()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        if ($user->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Account deleted successfully',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to delete account',
        ], 500);
    }

    public function devices()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $devices = $user->devices()
            ->orderBy('last_active_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'devices' => UserDeviceResource::collection($devices),
        ]);
    }

    public function revoke($id)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $device = $user->devices()->find($id);

        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        if ($device->token_id === optional($user->currentAccessToken())->id) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot revoke your current device.',
            ], 400);
        }

        if ($device->token) {
            $device->token()->delete();
        }

        $device->delete();

        return response()->json([
            'status' => true,
            'message' => 'Device access revoked.',
        ]);
    }

    public function revokeAllExceptCurrent()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $currentTokenId = optional($user->currentAccessToken())->id;

        // Delete all device records except current token's device
        $user->devices()
            ->where('token_id', '!=', $currentTokenId)
            ->each(function ($device) {
                if ($device->token) {
                    $device->token()->delete();
                }
                $device->delete();
            });

        return response()->json([
            'status' => true,
            'message' => 'All other devices have been logged out.',
        ]);
    }
}
