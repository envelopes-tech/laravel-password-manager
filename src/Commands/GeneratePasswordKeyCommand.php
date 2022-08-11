<?php

namespace Benjafield\LaravelPasswordManager\Commands;

use Benjafield\LaravelPasswordManager\PasswordManager;
use Illuminate\Console\Command;

class GeneratePasswordKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwords:generate-key {length?}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generates an encryption key for the Password Manager.';

    /**
     * Execute the console command.
     *
     * @param PasswordManager $manager
     * @return int
     */
    public function handle(PasswordManager $manager)
    {
        $key = $manager->dynamicKey($this->argument('length') ?? 16);

        $this->info('Key generated successfully:');
        $this->line('<comment>'.$key.'</comment>');

        return 0;
    }
}