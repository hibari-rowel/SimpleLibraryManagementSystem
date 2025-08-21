<?php

namespace App\Enum;

enum FineStatus: string
{
    case Paid = 'paid';
    case Unpaid = 'unpaid';
}
