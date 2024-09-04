# Cutt.ly PHP

[![Test & Lint](https://github.com/toneflix/cuttly-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/toneflix/cuttly-php/actions/workflows/run-tests.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/toneflix-code/cuttly-php.svg)](https://packagist.org/packages/toneflix-code/cuttly-php)
[![Total Downloads](https://img.shields.io/packagist/dt/toneflix-code/cuttly-php.svg)](https://packagist.org/packages/toneflix-code/cuttly-php)
[![License](https://img.shields.io/packagist/l/toneflix-code/cuttly-php.svg)](https://packagist.org/packages/toneflix-code/cuttly-php)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/toneflix-code/cuttly-php/php)](https://packagist.org/packages/toneflix-code/cuttly-php)
[![codecov](https://codecov.io/gh/toneflix/cuttly-php/graph/badge.svg?token=2O7aFulQ9P)](https://codecov.io/gh/toneflix/cuttly-php)

Cutt.ly is a Link Management Platform with all features you need in one place. Shorten your links, track clicks, and boost your brand with our all-in-one URL Shortener. Create custom short links, generate QR codes, build link-in-bio pages, and gather feedback with surveys. Start optimizing your URLs today and see the impact!y, this library aims to work around the Cutt.ly API implementation by providing a simple PHP wrapper arround it.

Please refere to [Cutt.ly API Documentation](https://cutt.ly/cuttly-api) for detailed api use description.

If you use Laravel you might also want to checkout [Cuttly Laravel](https://github.com/toneflix/cuttly-laravel)

## Requirements

- [PHP >= 8.1](http://php.net/)
- [Guzzle, PHP HTTP client >= 7.1](https://github.com/guzzle/guzzle) - Auto installs with composer
- [PHP dotenv >= 5.6](https://github.com/vlucas/phpdotenv) - Auto installs with composer

## Installation

You can install the package via composer:

```bash
composer require toneflix-code/cuttly-php
```

## Initialization

To start using this library you are required to configure your API keys in a .env file with these variables.

```bash
CUTTLY_API_KEY=your-cutt.ly-API-key
CUTTLY_TEAM_API_KEY=your-cutt.ly-API-key #[Optional] For users with team subscriptions.
```

If your project does not have a .env file, you can create one at the root directory of your project. Alternatively your can pass the api key as parameters when you [initialize the library](#without-env-file).

### With ENV file

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();
```

### Without ENV file

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly(
    apiKey: '121hksdSome23hRandom19212RegularApiKeyString55',
    teamApiKey: 'hksd092uSome00uRandom101nTeamApiKeyStrings31'
);
```

#### Initialization Errors

Where an API key is not provided, the library will throw a `ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException` exception and any other associated action call.

## Regular API Usage

### Shorten Url

To shorten a URL simply call the `shorten(string)` method chained to the `regular()` method of the `Cuttly` instance passing the link you intend to shorten as the only parameter.

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://toneflix.com.ng/learning';
$data = $cutly->regular()->shorten($link);
```

The `shorten()` method returns an instance of `ToneflixCode\CuttlyPhp\Builders\ShortenResponse` which contains all properties of the request response [returned by the API](https://cutt.ly/api-documentation/regular-api).

### Chainable parameters

We have taken our time to ensure that while using this library, you have access to every available feature provided by cuttly in the first place, here is a list of parameters you can chain as methods to further customize your request.

- `name(string)`: Your desired short link - alias - if not already taken
- `noTitle()`: Faster API response time - This parameter disables getting the page title from the source page meta tag which results in faster API response time
  Available for Team Enterprise plan
- `public()`: Settings public click stats for shortened link via AP
- `userDomain()`: Use the domain from the user account that is approved and has the `status: active`.

#### Example Usage

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://toneflix.com.ng/learning';
$data = $cutly->regular()->name('toneflix101')->shorten($link);
```

Do note that `shorten()` should only be called at the end of the chain.

### Edit Short Link

The library also allows you to edit the short links you have created. To edit a link simply call the `edit(string)` method chained to the `regular()` method of the `Cuttly` instance passing the short link you intend to edit as the only parameter.

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix101';
$data = $cutly->regular()->edit($link);
```

Of course, the example above really does nothing and will throw a `ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException`. To actually edit a link, you can chain any of the below methods to your call and voila.

- `name(string)`: New alias / name, if not already taken.
- `userDomain()`: Use the domain from the user account that is approved and has the `status: active`.
- `tag(string)`: The TAG you want to add for shortened link.
- `source(string)`: Change the source url of shortened link.
- `unique(0|1|15-1440)`: Sets a unique stat count for a short link.
- `title()`: It will change the title of url of shortened link.

#### Example Usage

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix101';
$data = $cutly->regular()->name('toneflix404')->userDomain()->unique(1)->edit($link);
```

The `edit()` method returns an instance of `ToneflixCode\CuttlyPhp\Builders\BaseResponse` which contains all properties of the request response [returned by the API](https://cutt.ly/api-documentation/regular-api).

### Link analytics

In order to access URL statistics call the `stats(string)` method chained to the `regular()` method of the `Cuttly` instance passing the short link you intend to get analytics of as the only parameter.

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix404';
$data = $cutly->regular()->stats($link);
```

The `stats()` method returns an instance of `ToneflixCode\CuttlyPhp\Builders\StatsResponse` which contains all properties of the request response [returned by the API](https://cutt.ly/api-documentation/regular-api).

### Delete Short Link

To delete a short link call the `delete(string)` method chained to the `regular()` method of the `Cuttly` instance passing the short link you intend to get delete as the only parameter.

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix404';
$data = $cutly->regular()->delete($link);
```

The `delete()` method returns an instance of `ToneflixCode\CuttlyPhp\Builders\BaseResponse` which contains all properties of the request response [returned by the API](https://cutt.ly/api-documentation/regular-api).

## Team API Usage

The team API implements the same methods and chainable methods available to the Regular API with a few exceptions that we will point out next.

TO use the Team API, instead of calling the `regular()` method on the `Cuttly` instance, we're going to call the `team()` method, you can now chain all the method we have highlighted above to use the Team API.

### Shorten Link

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://toneflix.com.ng/learning';
$data = $cutly->team()->name('toneflix301')->shorten($link);
```

### Edit Short Link

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix301';
$data = $cutly->team()->edit($link);
```

### Link analytics

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix301';
$data = $cutly->team()->stats($link);
```

### Delete Short Link

```php
use ToneflixCode\CuttlyPhp\Cuttly;

$cutly = new Cuttly();

$link = 'https://cutt.ly/toneflix404';
$data = $cutly->team()->delete($link);
```

## Exceptions

### `ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException`

The `ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException` exception is thrown whenever an API key has not been provided or an invalid API key has been provided.

### `ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException`

The `ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException` exception is thrown whenever the Cuttly API returns an error.

### Exception Handling

When you hit an exception, you can handle it in whatever way is best for your use case.

```php
use ToneflixCode\CuttlyPhp\Cuttly;
use ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException;

$cutly = new Cuttly();

try {
  $link = 'https://toneflix.com.ng/learning';
  $data = $cutly->regular()->name('toneflix404')->shorten($link);
} catch (FailedRequestException $th) {
  echo $th->getMessage();
}
```

```php
use ToneflixCode\CuttlyPhp\Cuttly;
use ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException;
use ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException;

try {
  $cutly = new Cuttly();

  $link = 'https://toneflix.com.ng/learning';
  $data = $cutly->regular()->name('toneflix404')->shorten($link);
} catch (FailedRequestException|InvalidApiKeyException $th) {
  echo $th->getMessage();
}
```

For detailed descriptino about what is obtainable from the API, please read the [Cutt.ly Documentations](https://cutt.ly/api-documentation/regular-api).

## Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email code@toneflix.com.ng instead of using the issue tracker.

## Credits

- [Toneflix Code](https://github.com/toneflix)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
