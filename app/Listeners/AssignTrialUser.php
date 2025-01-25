<?php

namespace App\Listeners;

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

            $activePurchase = $user->purchases()
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->first();

            if ($activePurchase) {
                // Extend the expiration date by 3 day
                $activePurchase->update([
                    'expires_at' => $activePurchase->expires_at->addDays(3),
                ]);
            } else {
                // Create a new purchase with 1 day duration
                $user->purchases()->create([
                    'plan_id' => null, // Optional: Specify a trial plan ID if applicable
                    'started_at' => now(),
                    'expires_at' => now()->addDays(3),
                    'is_active' => true,
                ]);
            }
        }
    }
}
