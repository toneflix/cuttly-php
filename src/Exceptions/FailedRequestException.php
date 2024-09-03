<?php

namespace ToneflixCode\Cuttly\Exceptions;

use ToneflixCode\Cuttly\Enums\EditingStatus;
use ToneflixCode\Cuttly\Enums\ShortnerStatus;
use ToneflixCode\Cuttly\Enums\StatsStatus;

class FailedRequestException extends \Exception
{
    /**
     * Create a new failed request exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message, $code = 0, private ?array $details = null)
    {
        parent::__construct($message, $code);
    }

    /**
     * Match the recieved error code code to the appropriete error
     *
     * @param ShortnerStatus $case
     * @return FailedRequestException|InvalidApiKeyException
     */
    public static function fromCase(
        ShortnerStatus|EditingStatus|StatsStatus $case
    ): self|InvalidApiKeyException {
        return match ($case) {
            ShortnerStatus::ALREADY_SHORTEND => new FailedRequestException("The link you provided has already been shortened.", 400),
            ShortnerStatus::INVALID_LINK     => new FailedRequestException("The link you provided is not a link.", 400),
            ShortnerStatus::NAME_TAKEN       => new FailedRequestException("The preferred link name you provided is already taken.", 400),
            ShortnerStatus::INVALID_API_KEY  => InvalidApiKeyException::message("Invalid api key provided.", 400),
            ShortnerStatus::INVALID_CHARS    => new FailedRequestException("The link you provided includes invalid characters", 400),
            ShortnerStatus::BLOCKED_DOMAIN   => new FailedRequestException("The link you provided is from a blocked domain.", 400),
            ShortnerStatus::LIMIT_REACHED    => new FailedRequestException("You have reached your monthly link limit, please Upgrade.", 400),

            EditingStatus::NOT_SAVED         => new FailedRequestException("Could not save in database.", 400),
            EditingStatus::UNKNOWN_URL       => new FailedRequestException("The url does not exist or you do not own it.", 400),
            EditingStatus::VALIDATION_FAILED => new FailedRequestException("The url didn't pass the validation.", 400),

            StatsStatus::INVALID_LINK    => new FailedRequestException("This shortened link does not exist.", 400),
            StatsStatus::INVALID_API_KEY => InvalidApiKeyException::message("Invalid api key provided.", 400),
        };
    }
}