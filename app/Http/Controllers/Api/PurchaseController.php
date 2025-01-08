<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ActivationCodeMail;
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
            'expires_at' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }

        $user = Auth::user();

        do {
            $activationCode = Str::random(6);
        } while (Purchase::where('activation_code', $activationCode)->exists());

        /** @var \App\Models\User $user **/
        $purchase = $user->purchases()->create([
            'plan_id' => $request->plan_id,
            'started_at' => now(),
            'expires_at' => $request->expires_at,
            'is_active' => false,
            'activation_code' => $activationCode,
        ]);

        Mail::to($user->email)->send(new ActivationCodeMail($activationCode, $user));

        return response()->json([
            'status' => true,
            'message' => 'Purchase created successfully!',
            'purchase' => $purchase
        ], 201);
    }

    public function Status()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user **/
        $purchases = $user->purchases()->first()->map(function ($purchase) {
            $purchase->is_expired = now()->greaterThan($purchase->expires_at);
            return $purchase;
        });
        return response()->json([
            'status' => true,
            'purchases' => $purchases
        ], 200);
    }

    public function verifyActivationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user **/

        $purchase = $user->purchases()
            ->where('activation_code', $request->activation_code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$purchase) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired activation code.',
            ], 400);
        }

        if ($purchase->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'This activation code has already been used.',
            ], 400);
        }

        // Activate the purchase
        $purchase->update(['is_active' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Purchase activated successfully!',
            'purchase' => $purchase,
        ], 200);
    }
}
