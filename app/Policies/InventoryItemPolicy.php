<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryItem;

class InventoryItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        if ($user->isAdmin() || $user->isTechnician()) {
            return true;
        }
        return false;
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
