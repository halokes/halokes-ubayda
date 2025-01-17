<?php

namespace App\Policies\Ubayda;

use App\Models\Ubayda\Business;
use App\Models\Ubayda\BusinessUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BusinessUserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function updateBusinessUser(User $user, Business $business)
    {
        // Log::debug('BusinessUserPolicy is hit : updateBusinessUser hit', [
        //     'user_id' => $user->id,
        //     'business_id' => $business->id,
        // ]);

        return $business->users()
            ->where('user_id', $user->id)
            ->where('role', config('ubayda.UBAYDA_BUSINESS_OWNER')) // Assuming 'role' is a column in the pivot table
            ->exists();
    }

    public function deleteBusinessUser(User $user, Business $business)
    {
        return $business->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
