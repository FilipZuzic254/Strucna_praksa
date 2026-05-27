<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;


class ClientPolicy
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
        if ($user->isAdmin() || $user->isShopManager()) {
            return true;
        }
        return false;
    }

    public function update(User $user): bool
    {
        if ($user->isAdmin() || $user->isShopManager()) {
            return true;
        }
        return false;
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
