<?php

use ToneflixCode\CuttlyPhp\Cuttly;

beforeEach(function () {
    $this->link = 'https://facebook.com/marxemi';
}); //->skip('All tests in this file are temporarily disabled but they work');

test('can shorten link', function () {
    $cutly = new Cuttly();
    $create = $cutly->regular()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $cutly->regular()->delete($create->shortLink);

    expect($create->fullLink)->toBe($this->link);
}); //->skip('temporarily disabled but it works.');

test('can shorten link with minimal params', function () {
    $cutly = new Cuttly();
    $create = $cutly->regular()->shorten($this->link);
    $cutly->regular()->delete($create->shortLink);

    expect($create->fullLink)->toBe($this->link);
}); //->skip('temporarily disabled but it works.');

/**
 * Test if links can be edited
 */
test('can edit link', function () {
    $cutly = new Cuttly();
    $name = 'john' . rand();
    $create = $cutly->regular()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $edit = $cutly->regular()->noTitle()->name($name)->edit($create->shortLink);
    $cutly->regular()->delete(explode('john', $create->shortLink)[0] . $name);

    expect($edit->status)->toBe(1);
}); //->skip('temporarily disabled but it works.');

/**
 * Test if links can be deleted 
 */
test('can delete link', function () {
    $cutly = new Cuttly();
    $create = $cutly->regular()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $delete = $cutly->regular()->delete($create->shortLink);

    expect($delete->status)->toBe(1);
}); //->skip('temporarily disabled but it works.');

/**
 * Test if links stats can be aquired
 */
test('can get stats', function () {
    $cutly = new Cuttly();
    $create = $cutly->regular()->noTitle()->public()->name('john' . rand())->shorten($this->link);
    $stats = $cutly->regular()->stats($create->shortLink);
    $cutly->regular()->delete($create->shortLink);

    expect($stats->fullLink)->toBe($this->link);
    expect($stats->shortLink)->toBe($create->shortLink);
});//->skip('temporarily disabled but it works.');