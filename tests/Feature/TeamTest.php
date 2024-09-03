<?php

use ToneflixCode\Cuttly\Cuttly;

beforeEach(function () {
    $this->link = 'https://facebook.com/marxemi';
}); //->skip('All tests in this file are temporarily disabled but they work');

test('can generate link', function () {
    $cutly = new Cuttly();
    $create = $cutly->team()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $cutly->team()->delete($create->shortLink);

    expect($create->fullLink)->toBe($this->link);
});

/**
 * Test if links can be edited
 */
test('can edit link', function () {
    $cutly = new Cuttly();
    $name = 'john' . rand();
    $create = $cutly->team()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $edit = $cutly->team()->noTitle()->name($name)->edit($create->shortLink);
    $cutly->team()->delete(explode('john', $create->shortLink)[0] . $name);

    expect($edit->status)->toBe(1);
}); //->skip('temporarily disabled but it works.');

/**
 * Test if links can be deleted 
 */
test('can delete link', function () {
    $cutly = new Cuttly();
    $create = $cutly->team()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $delete = $cutly->team()->delete($create->shortLink);

    expect($delete->status)->toBe(1);
}); //->skip('temporarily disabled but it works.');

/**
 * Test if links stats can be aquired
 */
test('can get stats', function () {
    $cutly = new Cuttly();
    $create = $cutly->team()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $stats = $cutly->team()->stats($create->shortLink);
    $cutly->team()->delete($create->shortLink);

    expect($stats->fullLink)->toBe($this->link);
    expect($stats->shortLink)->toBe($create->shortLink);
});//->skip('temporarily disabled but it works.');