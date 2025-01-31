<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ActivationCodeMail;
use App\Models\ActivationCode;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function addPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        do {
            $activationCode = Str::random(10);
        } while (ActivationCode::where('code', $activationCode)->exists());

        ActivationCode::create([
            'plan_id' => $request->plan_id,
            'code' => $activationCode,
        ]);

        Mail::to($user->email)->send(new ActivationCodeMail($activationCode, $user));

        return response()->json([
            'status' => true,
            'message' => 'Purchase created successfully! Activation code sent to your email.',
        ], 201);
    }

    public function Status()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user **/
        $purchases = $user->purchases()->get()->map(function ($purchase) {
            $purchase->is_expired = now()->greaterThan($purchase->expires_at);
            return $purchase;
        });
        // $purchases = $user->purchases()
        //     ->where('is_active', true)
        //     ->where('expires_at', '>', now())
        //     ->first();
        return response()->json([
            'status' => true,
            'purchases' => $purchases
        ], 200);
    }

    public function redeemActivationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $activationCode = ActivationCode::where('code', $request->activation_code)
            ->where('is_used', false)
            ->first();

        if (!$activationCode) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or already used activation code.',
            ], 400);
        }

        if (!is_null($activationCode->user_id) && $activationCode->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'This activation code is not assigned to you.',
            ], 403);
        }

        $plan = $activationCode->plan;
        $purchase = $user->purchases()
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        $duration = $plan->duration;
        $expiresAt = match ($plan->duration_unit) {
            'day'   => now()->addDays($duration),
            'week'  => now()->addWeeks($duration),
            'month' => now()->addMonths($duration),
            'year'  => now()->addYears($duration),
            default => now()->addDays(7),
        };

        if ($purchase) {
            // Extend the existing purchase expiration date
            $purchase->update([
                'expires_at' => Carbon::parse($purchase->expires_at)->add($expiresAt->diff(now())),
            ]);
        } else {
            // Create a new purchase
            $purchase = $user->purchases()->create([
                'plan_id' => $plan->id,
                'started_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true,
            ]);
        }

        $activationCode->update([
            'is_used' => true,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Purchase activated successfully!',
            'purchase' => $purchase,
        ], 200);
    }
}
