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
        return $user->isAdmin();
    }

    public function update(User $user, Client $record): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Client $record): bool
    {
        return $user->isAdmin();
    }
}
