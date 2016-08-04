# Sift PHP SDK
A PHP wrapper around Sift API. Supports PHP versions `>5.4` and `>7.0`.

## Installation
`composer require easilydo/sift-php-sdk`

## Usage
POST /v1/users
```php
$siftApi = new Easilydo\SiftApi('api_key', 'api_secret');

try {
  $response = $sift->addUser('en_US', 'testuser');
} catch(Easilydo\Exceptions\SiftRequestException $e) {
  echo 'SiftApi returned an error: ' . $e->getMessage();
}
```

## Tests
Ensure all dependencies are installed by running `php composer.phar install`, or
`composer install` if you have composer installed globally.

`bin/phpspec run` runs all tests.

## Documentation
Documentation for the SDK can be found
[here](https://github.com/agent8/sift-php-sdk/blob/master/docs/API.md).

## Changelog
Changelog can be found
[here](https://github.com/agent8/sift-php-sdk/blob/master/docs/CHANGELOG.md).
