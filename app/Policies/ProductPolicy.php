<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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

    public function update(User $user, Product $record): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Product $record): bool
    {
        return $user->isAdmin();
    }
}
