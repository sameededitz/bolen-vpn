<?php

namespace App\Listeners;

use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;

class AssignTrialUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        /** @var \App\Models\User $user **/
        $user = $event->user;
        if ($user) {
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
}
