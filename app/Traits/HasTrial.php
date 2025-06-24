<?php

namespace App\Traits;

use App\Models\Option;

trait HasTrial
{
    public function giveTrialIfEligible(): void
    {
        if ($this->has_had_trial || $this->purchases()->exists()) {
            return;
        }

        $days = (int) Option::where('key', 'trial_days')
            ->first()
            ?->value;

        if (!$days) {
            return;
        }

        $this->update([
            'trial_ends_at' => now()->addDays($days),
            'has_had_trial' => true,
        ]);
    }
}
