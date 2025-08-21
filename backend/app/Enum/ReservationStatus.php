<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case Pending = 'pending';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
}
