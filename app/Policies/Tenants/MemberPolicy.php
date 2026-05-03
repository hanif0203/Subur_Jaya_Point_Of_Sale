<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\Member;
use App\Models\Tenants\User;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('lihat member');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Member $member): bool
    {
        return $user->can('lihat member');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('buat member');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): bool
    {
        return $user->can('ubah member');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->can('hapus member');
    }
}