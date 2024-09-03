<?php

namespace ToneflixCode\Cuttly\Enums;

enum ShortnerStatus: int
{
    case UNKNOWN_ERROR = 0;
    case ALREADY_SHORTEND = 1;
    case INVALID_LINK = 2;
    case NAME_TAKEN = 3;
    case INVALID_API_KEY = 4;
    case INVALID_CHARS = 5;
    case BLOCKED_DOMAIN = 6;
    case LIMIT_REACHED = 8;
}