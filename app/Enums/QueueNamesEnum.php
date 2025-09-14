<?php

namespace App\Enums;

enum QueueNamesEnum: string
{
    case Repository = 'repository';

    case NotificationChannelEmail = 'email';
}
