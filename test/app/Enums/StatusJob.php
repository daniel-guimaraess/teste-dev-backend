<?php

namespace App\Enums;

enum StatusJob: string
{
    case OPEN = 'open';
    case FINISHED = 'finished';
    case PAUSED = 'paused';
}