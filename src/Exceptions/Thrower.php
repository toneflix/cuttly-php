<?php

namespace ToneflixCode\Cuttly\Exceptions;

use ToneflixCode\Cuttly\Cuttly;
use ToneflixCode\Cuttly\Enums\EditingStatus;
use ToneflixCode\Cuttly\Enums\ShortnerStatus;
use ToneflixCode\Cuttly\Enums\StatsStatus;

class Thrower
{
    /**
     * Throws the corresponding exception when shortening a url.
     *
     * @param  string  $body
     * @return void
     */
    public static function shortener(string $body)
    {
        if (Cuttly::jsonValidate($body)) {
            $body = json_decode($body);

            /**
             * Your API key is incorrect and a json with parameter auth:false has been returned
             */
            if (isset($body->auth)) {
                $body->code = 4;
            }

            $case = $body->code ?? $body->url->status ?? 0;

            if ($case === 0) {
                throw new FailedRequestException((string)$body, 400);
            } else {
                throw FailedRequestException::fromCase(ShortnerStatus::from($case));
            }
        } else {
            throw new FailedRequestException($body, 1);
        }
    }

    /**
     * Throws the corresponding exception when editing a link.
     *
     * @param  string  $body
     * @return void
     */
    public static function editing(string $body)
    {
        if (Cuttly::jsonValidate($body)) {
            $body = json_decode($body);

            $case = $body->code ?? $body->status ?? 0;

            if ($case === 0) {
                throw new FailedRequestException((string)$body, 400);
            } else {
                throw FailedRequestException::fromCase(EditingStatus::from($case));
            }
        } else {
            throw new FailedRequestException($body, 1);
        }
    }

    /**
     * Throws the corresponding exception when editing a link.
     *
     * @param  string  $body
     * @return void
     */
    public static function stats(string $body)
    {
        if (Cuttly::jsonValidate($body)) {
            $body = json_decode($body);

            $case = $body->status ?? 0;

            if ($case === 0) {
                throw new FailedRequestException((string)$body, 400);
            } else {
                throw FailedRequestException::fromCase(StatsStatus::from($case));
            }
        } else {
            throw new FailedRequestException($body, 1);
        }
    }
}
