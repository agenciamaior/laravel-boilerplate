<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user) {
        // Suas regras aqui
    }

    public function create(User $user) {
        // Suas regras aqui
    }

    public function edit(User $user, User $userToEdit) {
        // Suas regras aqui
    }

    public function destroy(User $user, User $userToDestroy) {
        // Suas regras aqui
    }

    public function block(User $user, User $userToBlock) {
        // Suas regras aqui
    }

    public function unblock(User $user, User $userToUnblock) {
        // Suas regras aqui
    }
}
