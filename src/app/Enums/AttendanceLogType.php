<?php

namespace App\Enums;

enum AttendanceLogType: int
{
    case CLOCK_IN = 1;
    case CLOCK_OUT = 2;
    case REST_START = 3;
    case REST_END = 4;

}