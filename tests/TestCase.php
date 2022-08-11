<?php

namespace Tests;

use Benjafield\LaravelPasswordManager\PasswordServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function getPackageProviders($app): array
    {
        return [
            PasswordServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'passwords');
        $app['config']->set('database.connections.passwords', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        config()->set('passwords.table', 'passwords');
        config()->set('passwords.key', 'JeJrkKZX3nkBN2iQ');

        include_once __DIR__ . '/../database/migrations/create_passwords_table.php.stub';

        (new \CreatePasswordsTable)->up();
    }
}