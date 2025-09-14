<?php

namespace App\Enums;

enum QueueNamesEnum: string
{
    case Repository = 'repository';

    case Release = 'release';

    case NotificationChannelEmail = 'email';
}
