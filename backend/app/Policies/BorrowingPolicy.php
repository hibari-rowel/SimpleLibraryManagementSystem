<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BorrowingPolicy
{
    public function store(User $user, Borrowing $borrowing)
    {
        return (in_array($user->user_type, ['admin', 'member']))
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function update(User $user, Borrowing $borrowing)
    {
        return ($user->user_type === 'admin' || $user->id === $borrowing->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, Borrowing $borrowing)
    {
        return ($user->user_type === 'admin' || $user->id === $borrowing->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
