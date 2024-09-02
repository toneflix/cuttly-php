<?php

use ToneflixCode\Cuttly\Cuttly;

test('can initialize', function () {
    $cutly = new Cuttly('api-key');
    expect($cutly->apiKey)->toBe('api-key');
});

test('can be tested', function () {
    $cutly = new Cuttly('api-key');
    expect($cutly->apiKey)->toBe('api-key');
});