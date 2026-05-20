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

    public function update(User $user, InventoryItem $record): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, InventoryItem $record): bool
    {
        return $user->isAdmin();
    }
}
