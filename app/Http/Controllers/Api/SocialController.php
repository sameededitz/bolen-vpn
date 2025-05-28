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

            /** @var \App\Models\User $user **/
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // If user exists, update all details except email
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // If user does not exist, create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(10)),
                    'email_verified_at' => now(),
                ]);

                // Assign a trial to the user
                $this->assignTrial($user);
            }

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

            // Retrieve the user from the Apple token
            /** @disregard @phpstan-ignore-line */
            $appleUser = Socialite::driver('apple')->stateless()->userFromToken($request->input('token'));

            if (!$appleUser->user['email_verified']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your email address is not verified. Please verify your Apple account.',
                ], 400);
            }

            // Extract user details
            $appleId = $appleUser->id;
            $email = $appleUser->email;
            $name = $appleUser->name ?? $email;

            /** @var \App\Models\User $user **/
            $user = User::where('apple_id', $appleId)->orWhere('email', $email)->first();

            if ($user) {
                // Update Apple ID if needed
                $user->update(['apple_id' => $appleId]);
            } else {
                // Create a new user
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'apple_id' => $appleId,
                    'password' => Hash::make(Str::random(10)),
                    'email_verified_at' => now(),
                ]);
                $this->assignTrial($user);
            }

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

    public function assignTrial(User $user)
    {
        $trialDays = Option::where('key', 'trial_days')->value('value') ?? 3;
        $trialDays = (int) $trialDays; // Ensure it's an integer

        $activePurchase = $user->purchases()
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if ($activePurchase) {
            // Extend the expiration date by 3 day
            $newExpiresAt = Carbon::parse($activePurchase->expires_at)->addDays($trialDays);

            // Update the expiration date
            $activePurchase->update([
                'expires_at' => $newExpiresAt,
            ]);
        } else {
            // Create a new purchase with 1 day duration
            $user->purchases()->create([
                'plan_id' => null, // Optional: Specify a trial plan ID if applicable
                'started_at' => now(),
                'expires_at' => now()->addDays($trialDays),
                'is_active' => true,
            ]);
        }
    }
}
