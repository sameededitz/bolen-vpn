<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Option;
use Illuminate\Support\Str;
use App\Services\AppleToken;
use Illuminate\Http\Request;
use App\Traits\ManagesUserDevices;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class SocialController extends Controller
{
    use ManagesUserDevices;

    public function handleGoogleCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'device_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $accessToken = $request->input('token');
        try {
            /** @disregard @phpstan-ignore-line */
            $googleUser = Socialite::driver('google')->userFromToken($accessToken);
            // Check if the user already exists

            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $name = $googleUser->getName();

            // First, check by google_id
            /** @var \App\Models\User $user **/
            $user = User::where('google_id', $googleId)->first();

            if (!$user && $email) {
                // Then fallback to email
                $existing = User::where('email', $email)->first();
                if ($existing) {
                    $existing->update(['google_id' => $googleId]);
                    $user = $existing;
                }
            }

            // Create new if no user found
            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(Str::random(10)),
                    'email_verified_at' => now(),
                ]);
            }
            $user->giveTrialIfEligible();

            if ($user->isBanned()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is banned. Please contact support.'
                ], 403);
            }

            // Log the user in
            Auth::login($user);

            // Create or refresh the device token
            $token = $this->createOrRefreshDeviceToken($user, $request);

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully!',
                'user' => $user,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Error logging in with Google Api: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error logging in with Google. Please try again later.',
                'error' => 'Error:' . $e->getMessage()
            ], 500);
        }
    }

    public function handleAppleCallback(Request $request, AppleToken $appleToken)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'device_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        try {
            // Generate a fresh client secret
            config()->set('services.apple.client_secret', $appleToken->generateClientSecret(true));

            /** @disregard @phpstan-ignore-line */
            $appleUser = Socialite::driver('apple')->stateless()->userFromToken($request->input('token'));

            if (!$appleUser->user['email_verified']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your email address is not verified. Please verify your Apple account.',
                ], 400);
            }

            // Extract user detailsAdd commentMore actions
            $appleId = $appleUser->id;
            $email = $appleUser->email;
            $name = $appleUser->name ?? $email;

            $user = User::where('apple_id', $appleId)->first();

            if (!$user && $email) {
                $existing = User::where('email', $email)->first();
                if ($existing) {
                    $existing->update(['apple_id' => $appleId]);
                    $user = $existing;
                }
            }

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'apple_id' => $appleId,
                    'password' => Hash::make(Str::random(10)),
                    'email_verified_at' => now(),
                ]);
            }
            $user->giveTrialIfEligible();

            if ($user->isBanned()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is banned. Please contact support.'
                ], 403);
            }

            // Log the user in
            Auth::login($user);

            // Create or refresh the device token
            $token = $this->createOrRefreshDeviceToken($user, $request);

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully!',
                'user' => $user,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Error logging in with Apple Api: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error logging in with Apple. Please try again later.',
                'error' => 'Error:' . $e->getMessage()
            ]);
        }
    }
}
