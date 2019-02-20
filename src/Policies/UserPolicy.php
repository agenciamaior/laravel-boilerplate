<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user) {
        return $user->role == User::ROLE_ADMIN;
    }

    public function create(User $user) {
        return $user->role == User::ROLE_ADMIN;
    }

    public function edit(User $user, User $userToEdit) {
        return $user->role == User::ROLE_ADMIN;
    }

    public function destroy(User $user, User $userToDestroy) {
        return $user->role == User::ROLE_ADMIN && $user->id != $userToDestroy->id;
    }

    public function block(User $user, User $userToBlock) {
        return $user->role == User::ROLE_ADMIN && $user->id != $userToBlock->id;
    }

    public function unblock(User $user, User $userToUnblock) {
        return $user->role == User::ROLE_ADMIN && $user->id != $userToUnblock->id;
    }
}
