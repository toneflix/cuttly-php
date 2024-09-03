<?php

use ToneflixCode\CuttlyPhp\Cuttly;
use ToneflixCode\CuttlyPhp\Enums\StatsStatus;
use ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException;
use ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException;
use ToneflixCode\CuttlyPhp\Exceptions\Thrower;

it('throws an exception when the API key is not provided', function () {
    $cutly = new Cuttly('');
    $cutly->regular();
})->throws(InvalidApiKeyException::class);

it('throws a custom API key exception message', function () {
    throw InvalidApiKeyException::message('This is a custom API key exception');
})->throws(InvalidApiKeyException::class);

it('throws a Request Exception message from case', function () {
    throw FailedRequestException::fromCase(StatsStatus::INVALID_LINK);
})->throws(FailedRequestException::class);

it('throws an Exception with the thrower class when editing', function () {
    Thrower::editing('{ "code": 2 }');
})->throws(FailedRequestException::class, "Could not save in database.");

it('throws an Exception with the thrower class when shortening', function () {
    Thrower::shortener('{ "code": 2 }');
})->throws(FailedRequestException::class, "The link you provided is not a link.");

it('throws an Exception with the thrower class when reading stats', function () {
    Thrower::stats('{ "status": 0 }');
})->throws(FailedRequestException::class, "This shortened link does not exist.");