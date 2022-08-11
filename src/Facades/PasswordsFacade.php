<?php

namespace Benjafield\LaravelPasswordManager\Facades;

use Illuminate\Support\Facades\Facade;

class PasswordsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'passwords';
    }
}