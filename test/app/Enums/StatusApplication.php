<?php

namespace App\Enums;

enum StatusApplication: string
{
    case ANALYZING = 'analyzing';
    case SELECTED = 'selected';
    case NOT_SELECTED = 'not_selected';
}