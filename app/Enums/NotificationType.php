<?php

namespace App\Enums;

enum NotificationType: int
{
    case Alert = 1;
    case Information = 2;
    case Warning = 3;
}
