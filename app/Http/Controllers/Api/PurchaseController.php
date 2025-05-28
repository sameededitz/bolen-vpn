<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ActivationCode;
use App\Mail\UserActivationCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\PurchaseResource;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function generateCode(Request $request)
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

        $plan = Plan::findOrFail($request->plan_id);
        if(!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found.',
            ], 404);
        }

        // Check if plan_id is 1
        if ($plan->id == 1) {
            return response()->json([
                'status' => false,
                'message' => 'This plan is not eligible for activation code generation.',
            ], 403);
        }

        do {
            $activationCode = Str::random(10);
        } while (ActivationCode::where('code', $activationCode)->exists());

        ActivationCode::create([
            'plan_id' => $request->plan_id,
            'code' => $activationCode,
        ]);

        Mail::to($user->email)->send(new UserActivationCode($activationCode, $user, $plan));

        return response()->json([
            'status' => true,
            'message' => 'Purchased successfully! Activation code sent to your email.',
        ], 201);
    }

    public function active()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $activePlan = $user->purchases()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('plan')
            ->first();

        return response()->json([
            'status' => true,
            'purchase' => $activePlan ? new PurchaseResource($activePlan) : null,
        ], 200);
    }

    public function redeemActivationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 400);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $activationCode = ActivationCode::where('code', $request->code)
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
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();

        $price = $plan->price;
        if ($purchase && $purchase->plan_id !== $plan->id) {
            return response()->json([
                'status' => false,
                'message' => 'You already have an active plan. Please cancel it before activating a new one.',
            ], 400);
        }

        if ($purchase) {
            $newEndDate = $this->calculateExpiration(
                Carbon::parse($purchase->end_date),
                $plan->duration,
                $plan->duration_unit
            );

            // Update the purchase with the new expiration date
            $purchase->update([
                'plan_id' => $plan->id,
                'end_date' => $newEndDate,
                'status' => 'active',
                'amount_paid' => $purchase->amount_paid + $price,
            ]);

            $message = 'Subscription Extended successfully!';
        } else {
            $expiresAt = $this->calculateExpiration(now(), $plan->duration, $plan->duration_unit);
            // Create a new purchase
            $purchase = $user->purchases()->create([
                'plan_id' => $plan->id,
                'amount_paid' => $price,
                'start_date' => now(),
                'end_date' => $expiresAt,
                'status' => 'active',
            ]);

            $message = 'Subscription started successfully!';
        }

        $activationCode->update([
            'is_used' => true,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => $message,
            'purchase' => new PurchaseResource($purchase->load('plan', 'user')),
        ], 200);
    }

    public function history()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $purchases = $user->purchases()->with('plan', 'user')->latest()->get();
        return response()->json([
            'status' => true,
            'purchases' => PurchaseResource::collection($purchases),
        ], 200);
    }

    private function calculateExpiration($startDate, $duration, $unit)
    {
        return match ($unit) {
            'day'   => $startDate->addDays($duration),
            'week'  => $startDate->addWeeks($duration),
            'month' => $startDate->addMonths($duration),
            'year'  => $startDate->addYears($duration),
            default => $startDate->addDays(7),
        };
    }
}
