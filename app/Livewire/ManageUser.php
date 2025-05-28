<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Jobs\SendEmailVerification;

class ManageUser extends Component
{
    public User $user;
    public $plans, $selectedPlan;

    public function mount(User $user)
    {
        $this->user = $user->load(['purchases.plan', 'activePlan.plan', 'devices.token']);
        $this->plans = Plan::where('id', '!=', '1')
            ->get();
    }

    public function addPlan()
    {
        $this->validate([
            'selectedPlan' => 'required|exists:plans,id',
        ]);

        $plan = Plan::find($this->selectedPlan);
        if (!$plan) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Selected plan does not exist.', type: 'error');
            return;
        }
        if ($plan->id === 1) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Cannot add the free plan.', type: 'error');
            return;
        }
        if ($this->user->isBanned()) {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Cannot add plan to a banned user.', type: 'error');
            return;
        }
        if ($this->user->activePlan && $this->user->activePlan->plan_id === $plan->id) {
            $this->dispatch('sweetAlert', title: 'Info', message: 'User already has this plan.', type: 'info');
            return;
        }
        $activePurchase = $this->user->activePlan;

        if ($activePurchase) {
            $newExpiresAt = $this->calculateExpiry($activePurchase->end_date, $plan);

            $activePurchase->update([
                'plan_id' => $plan->id,
                'amount_paid' => $activePurchase->amount_paid + $plan->price,
                'end_date' => $newExpiresAt
            ]);

            $message = 'Plan extended successfully!';
        } else {
            $expiresAt = $this->calculateExpiry(now(), $plan);

            $this->user->purchases()->create([
                'plan_id' => $plan->id,
                'amount_paid' => $plan->price,
                'start_date' => now(),
                'end_date' => $expiresAt,
                'status' => 'active',
            ]);

            $message = 'Plan added successfully!';
        }

        $this->selectedPlan = null;

        $this->user->refresh();

        $this->dispatch('sweetAlert', title: 'Success', message: $message, type: 'success');
    }

    public function cancelPurchase()
    {
        if ($this->user->activePlan) {
            $this->user->activePlan->update(['status' => 'cancelled']);
            $message = 'Purchase cancelled successfully!';
        } else {
            $message = 'No active purchase found!';
        }
        $this->dispatch('sweetAlert', title: 'Success', message: $message, type: 'success');

        $this->user->refresh();
    }

    public function verifyEmailManually()
    {
        if ($this->user->hasVerifiedEmail()) {
            $this->dispatch('sweetAlert', title: 'Already Verified', text: 'This email is already verified.', type: 'info');
            return;
        }
        $this->user->markEmailAsVerified();
        $this->dispatch('sweetAlert', title: 'Success', message: 'Email verified successfully!', type: 'success');
        $this->user->refresh();
    }

    public function resendVerificationEmail()
    {
        if (!$this->user->hasVerifiedEmail()) {
            SendEmailVerification::dispatch($this->user)->delay(now()->addSeconds(5));
            $this->dispatch('sweetAlert', title: 'Success', message: 'Verification email resent.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Info', message: 'Email is already verified.', type: 'info');
        }
    }

    public function banUser($reason = null)
    {
        if ($this->user->isBanned()) {
            $this->dispatch('sweetAlert', title: 'Info', message: 'User is already banned.', type: 'info');
            return;
        }

        if (! $this->user->isBanned()) {
            $this->user->update(['banned_at' => now(), 'ban_reason' => $reason]);
            $this->user->tokens()->delete();
            $this->user->devices()->delete();
            $this->dispatch('sweetAlert', title: 'Success', message: 'User banned successfully.', type: 'success');
        }
        $this->user->refresh();
    }

    public function unbanUser()
    {
        if ($this->user->isBanned()) {
            $this->user->update(['banned_at' => null, 'ban_reason' => null]);
            $this->dispatch('sweetAlert', title: 'Success', message: 'User unbanned successfully.', type: 'success');
        }
        $this->user->refresh();
    }

    public function deleteUser()
    {
        $this->user->delete();
        $this->dispatch('sweetAlert', title: 'Success', message: 'User deleted successfully.', type: 'success');
        $this->dispatch('redirect', url: route('all-users'));
    }

    public function revokeDevice($deviceId)
    {
        $device = $this->user->devices()->find($deviceId);
        if ($device) {
            // Delete the token record
            if ($device->token) {
                $device->token()->delete();
            }

            $device->delete();
            $this->dispatch('sweetAlert', title: 'Access Revoked', message: 'Device logged out successfully.', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Device not found.', type: 'error');
        }
        $this->user->refresh();
    }

    public function revokeAllDevices()
    {
        $this->user->tokens()->delete();
        $this->user->devices()->delete();

        $this->dispatch('sweetAlert', title: 'Access Revoked', message: 'All devices logged out successfully.', type: 'success');
        $this->user->refresh();
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.manage-user')
            ->extends('layout.app')
            ->section('content');
    }

    private function calculateExpiry(Carbon $start, Plan $plan): Carbon
    {
        $maxDate = Carbon::create(2038, 1, 19, 3, 14, 7);
        $expiresAt = match ($plan->duration_unit) {
            'day' => $start->addDays($plan->duration),
            'week' => $start->addWeeks($plan->duration),
            'month' => $start->addMonths($plan->duration),
            'year' => $start->addYears($plan->duration),
            default => $start->addDays(7),
        };
        return $expiresAt->greaterThan($maxDate) ? $maxDate : $expiresAt;
    }
}
