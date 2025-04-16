<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class ProdutoPolicy
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
        return (bool) $user->is_admin;
    }

    public function create(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
