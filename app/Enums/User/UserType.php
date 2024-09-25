<?php

namespace App\Enums\User;

enum UserType : string
{
    case ADMIN = 'Administrator';
    case USER = 'User';
}