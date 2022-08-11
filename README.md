# Laravel Password Manager

A really simple Laravel package to encrypt and decrypt passwords in storage.

## Installation

Composer:

```bash
composer require cbenjafield/laravel-password-manager
```

After installation, publish the config:

```bash
php artisan vendor:publish --provider="Benjafield\LaravelPasswordManager\PasswordServiceProvider" --tag="config"
```

Add a 16 character long key to your .env file. Also, if you would like to change the passwords table name, you may specify that too.

```dotenv
PASSWORDS_KEY="Your 16 Character Long String Here"
PASSWORDS_TABLE="optional_password_table_name"
```

Then, publish the database migrations:

```bash
php artisan vendor:publish --provider="Benjafield\LaravelPasswordManager\PasswordServiceProvider" --tag="migrations"
```

Run the migrations:
```bash
php artisan migrate
```

## Usage

### Encrypting a password

You can make use of dependency injection:

```php
<?php

namespace App\Http\Controllers;

use Benjafield\LaravelPasswordManager\PasswordManager;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function store(Request $request, PasswordManager $passwords)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $password = $passwords->encrypt($request->name, $request->password);

        // Call the save method to store the password in the database.
        $password->save();

        return response($password->password, 201);
    }
}
```

Or you can use the facade:

```php
use Benjafield\LaravelPasswordManager\Facades\PasswordsFacade;

$password = PasswordsFacade::encrypt($request->name, $request->password);

// Call the save method to store the password in the database.
$password->save();
```

### Decrypting a password

To decrypt a password, you need to retrieve the dynamic key used to encrypt it in the first place.

```php
use Benjafield\LaravelPasswordManager\PasswordManager;
use Benjafield\LaravelPasswordManager\Password;

public function show(int $id, PasswordManager $passwords)
{
    // Retrieve the password from the database.
    $password = Password::findOrFail($id);
    
    // Decrypt the password back into the original string.
    $decrypted = $passwords->decrypt($password->dynamic, $password->password);
}
```

## Licence

The MIT Licence (MIT). Please refer to [Licence File](LICENCE.md) for more information.