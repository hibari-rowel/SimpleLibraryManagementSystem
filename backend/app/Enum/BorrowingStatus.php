<?php

namespace App\Enum;

enum BorrowingStatus: string
{
    case Borrowed = 'borrowed';
    case Returned = 'returned';
    case Overdue = 'overdue';
}
