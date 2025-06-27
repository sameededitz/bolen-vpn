<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    protected $fillable = [
        'plan_id',
        'user_id',
        'code',
        'is_used',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUsed(): bool
    {
        return $this->is_used;
    }

    /**
     * Scope a query to only include activation codes for a specific plan.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $planId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPlan($query, $planId)
    {
        return $query->where('plan_id', $planId);
    }

    /**
     * Scope a query to only include activation codes for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include unused activation codes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope a query to only include used activation codes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    /**
     * Generate a specified number of unique activation codes for a given plan.
     *
     * @param Plan $plan
     * @param int $quantity
     * @return array
     */
    public static function generateCodes(Plan $plan, int $quantity)
    {
        $codes = [];
        for ($i = 0; $i < $quantity; $i++) {
            do {
                $code = Str::upper(Str::random(10));
            } while (self::where('code', $code)->exists());

            $codes[] = self::create([
                'plan_id' => $plan->id,
                'code' => $code,
            ]);
        }
        return $codes;
    }
}
