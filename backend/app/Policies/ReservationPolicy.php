<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    public function store(User $user, Reservation $reservation)
    {
        return (in_array($user->user_type, ['admin', 'member']))
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function update(User $user, Reservation $reservation)
    {
        return ($user->user_type === 'admin' || $user->id !== $reservation->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, Reservation $reservation)
    {
        return ($user->user_type === 'admin' || $user->id !== $reservation->user_id)
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
