<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function store(User $user, Category $category)
    {
        return ($user->user_type === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function update(User $user, Category $category)
    {
        return ($user->user_type === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, Category $category)
    {
        return ($user->user_type === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
