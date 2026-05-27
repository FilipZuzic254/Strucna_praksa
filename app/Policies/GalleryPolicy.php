<?php

namespace App\Policies;

use App\Models\User;

class GalleryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        if ($user->isAdmin() || $user->isShopManager()) {
            return true;
        }
        return false;
    }
}
