<?php

use ToneflixCode\CuttlyPhp\Cuttly;

test('can load api key', function () {
    $cutly = new Cuttly();
    $cutly->init();

    expect($cutly->apiKey)->toBeAlphaNumeric();
    expect($cutly->teamApiKey)->toBeAlphaNumeric();
});

test('can set api key without .env', function () {
    $key  = '121hksdSome23hRandom19212Strings55';
    $tkey = 'hksd092uSome00uRandom101nStrings31';

    $cutly = new Cuttly(
        apiKey: $key,
        teamApiKey: $tkey
    );

    expect($cutly->apiKey)->toBe($key);
    expect($cutly->teamApiKey)->toBe($tkey);
});
