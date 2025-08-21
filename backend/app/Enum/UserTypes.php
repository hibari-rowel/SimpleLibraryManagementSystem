<?php

namespace App\Enum;

enum UserTypes: string
{
    case Admin = 'admin';
    case Member = 'member';
}
