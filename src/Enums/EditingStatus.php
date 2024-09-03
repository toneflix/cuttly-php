<?php

namespace ToneflixCode\Cuttly\Enums;

enum EditingStatus: int
{
    case UNKNOWN_ERROR = 0;
    case NOT_SAVED = 2;
    case UNKNOWN_URL = 3;
    case VALIDATION_FAILED = 4;
}
