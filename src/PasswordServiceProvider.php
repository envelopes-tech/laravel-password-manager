<?php

namespace Benjafield\LaravelPasswordManager;

use Benjafield\LaravelPasswordManager\Commands\GeneratePasswordKeyCommand;
use Illuminate\Support\ServiceProvider;

class PasswordServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/passwords.php', 'passwords');

        $this->app->bind('passwords', fn () => new PasswordManager);
    }

    /**
     * Bootstrap package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish the config file.
            $this->publishes([
                __DIR__ . '/../config/passwords.php' => config_path('passwords.php'),
            ], 'config');

            // Publish the database migrations
            if (! class_exists('CreatePasswordsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_passwords_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_passwords_table.php'),
                ], 'migrations');
            }

            // Register console commands.
            $this->commands([
                GeneratePasswordKeyCommand::class,
            ]);
        }
    }
}