<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendEmailVerification;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\ManagesUserDevices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ManagesUserDevices;

    public function signup(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validated->errors()->all()
            ], 422);
        }

        /** @var \App\Models\User $user **/
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        SendEmailVerification::dispatch($user)->delay(now()->addSeconds(5));

        $user->giveTrialIfEligible();

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => new UserResource($user),
            'verification_required' => true,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'device_id' => 'required|string|max:255',
            // 'device_name' => 'required|string|max:255',
            // 'device_type' => 'required|string|in:mobile,desktop,web',
            // 'platform' => 'required|string|max:50',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validated->errors()->all()
            ], 422);
        }

        /** @var \App\Models\User $user **/
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'message' => 'Email not verified'
            ], 403);
        }

        if ($user->isBanned()) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been banned. Please contact support.'
            ], 403);
        }

        if (Auth::attempt($request->only(['email', 'password']))) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();

            $token = $this->createOrRefreshDeviceToken($user, $request);

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully!',
                'user' => new UserResource($user),
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Get the current token
        $token = $user->currentAccessToken();

        if ($token) {
            // Delete the related device if exists
            $user->devices()
                ->where('token_id', $token->id)
                ->delete();

            // Delete the token itself
            /** @disregard @phpstan-ignore-line */
            $token->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully!'
        ], 200);
    }
}
