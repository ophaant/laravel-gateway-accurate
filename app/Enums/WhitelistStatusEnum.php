<?php

namespace App\Enums;

enum WhitelistStatusEnum: string
{
    case ENABLE = 'Enable';
    case DISABLE = 'Disable';
    case PROGRESS = 'Progress';
}
