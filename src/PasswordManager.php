<?php

namespace Benjafield\LaravelPasswordManager;

use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class PasswordManager
{
    /**
     * The cipher used for encryption.
     */
    const CIPHER = 'AES-256-CBC';

    /**
     * @var string|null
     */
    protected string|null $key;

    /**
     * @var string
     */
    protected $model;

    /**
     * @param string|null $key
     */
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

    /**
     * @throws Exception
     * @return bool
     */
    protected function checkKey(): bool
    {
        if (is_null($this->key)) {
            throw new Exception("The encryption key is not valid.");
        }

        return true;
    }

    /**
     * @param int $length
     * @return string
     */
    public function dynamicKey(int $length = 16): string
    {
        return Str::random($length);
    }

    /**
     * @param string $dynamic
     * @return string
     */
    protected function key(string $dynamic): string
    {
        return $this->key . $dynamic;
    }

    /**
     * @param string $dynamic
     * @return Encrypter
     * @throws Exception
     */
    public function encrypter(string $dynamic): Encrypter
    {
        $this->checkKey();

        return new Encrypter($this->key($dynamic), static::CIPHER);
    }

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     * @throws Exception
     */
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

    /**
     * @param string $dynamic
     * @param string $value
     * @return string
     * @throws Exception
     */
    public function decrypt(string $dynamic, string $value): string
    {
        return $this->encrypter($dynamic)->decrypt($value);
    }
}