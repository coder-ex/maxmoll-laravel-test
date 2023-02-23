<?php

namespace App\Helpers;

enum TypeOrder: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
}

enum TypeStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
