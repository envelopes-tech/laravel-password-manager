<?php

namespace Benjafield\LaravelPasswordManager;

use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class PasswordManager
{
    const CIPHER = 'AES-256-CBC';

    protected string $key;

    protected $model;

    public function __construct(string $key = null)
    {
        $this->key = is_null($key)
            ? config('passwords.key')
            : $key;

        $this->model = Password::class;
    }

    /**
     * @throws Exception
     */
    public function setModel($model): self
    {
        if (! class_exists($model)) {
            throw new Exception("Class [$model] does not exist.");
        }

        $this->model = $model;

        return $this;
    }

    public function dynamicKey(int $length = 16): string
    {
        return Str::random($length);
    }

    protected function key(string $dynamic): string
    {
        return $this->key . $dynamic;
    }

    public function encrypter(string $dynamic): Encrypter
    {
        return new Encrypter($this->key($dynamic), static::CIPHER);
    }

    public function encrypt(string $name, string $value): mixed
    {
        $dynamic = $this->dynamicKey();

        return call_user_func(
            $this->model . '::make',
            $name,
            $dynamic,
            $this->encrypter($dynamic)->encrypt($value),
            $value
        );
    }

    public function decrypt(string $dynamic, string $value): string
    {
        return $this->encrypter($dynamic)->decrypt($value);
    }
}