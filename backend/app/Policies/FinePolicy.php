<?php

namespace App\Policies;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FinePolicy
{
    public function store(User $user, Fine $fine)
    {
        return ($user->user_type === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function update(User $user, Fine $fine)
    {
        return ($user->user_type === 'admin' && $user->id !== $fine->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, Fine $fine)
    {
        return ($user->user_type === 'admin' && $user->id !== $fine->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
