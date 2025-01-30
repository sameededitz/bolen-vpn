<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ActivationCodeMail;
use App\Models\ActivationCode;
use App\Models\Purchase;
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

        // Check if the user already has a purchase for this plan
        $existingPurchase = $user->purchases()
            ->where('plan_id', $activationCode->plan_id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingPurchase) {
            return response()->json([
                'status' => false,
                'message' => 'You already have an active purchase for this plan.',
            ], 400);
        }

        $purchase = $user->purchases()->create([
            'plan_id' => $activationCode->plan_id,
            'started_at' => now(),
            'expires_at' => now()->addMonths($activationCode->plan->duration),
            'is_active' => true,
        ]);

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
