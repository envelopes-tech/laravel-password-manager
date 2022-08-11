# Laravel Password Manager

A really simple Laravel package to encrypt and decrypt passwords in storage.

## Installation

Composer:

```bash
$ composer require cbenjafield/laravel-password-manager
```

After installation, publish the config:

```bash
$ php artisan vendor:publish --provider="Benjafield\LaravelPasswordManager\PasswordServiceProvider" --tag="config"
```

Then, publish the database migrations:

```bash
$ php artisan vendor:publish --provider="Benjafield\LaravelPasswordManager\PasswordServiceProvider" --tag="migrations"
```

## Usage

TO DO

## Licence

The MIT Licence (MIT). Please refer to [Licence file](LICENCE.md) for more information.