<?php

namespace ToneflixCode\CuttlyPhp\Exceptions;

final class InvalidApiKeyException extends \Exception
{
    /**
     * Create a new invalid api key exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'No api key provided.', $code = 0)
    {
        parent::__construct($message, $code);
    }

    public static function message($message = 'Invalid api key provided.', $code = 0)
    {
        throw new static($message, $code);
    }
}