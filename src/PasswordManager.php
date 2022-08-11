<?php

namespace Benjafield\LaravelPasswordManager;

use Benjafield\LaravelPasswordManager\Exceptions\KeyLengthException;
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
     * Partial key used for encryption of the passwords.
     *
     * @var string|null
     */
    protected string|null $key;

    /**
     * The FQN of the class used as the password model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new Password Manager instance.
     *
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
     * Set the FQN of the model to be used for passwords.
     *
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
     * Check that the encryption key has been set.
     *
     * @throws Exception
     * @return bool
     */
    protected function checkKey(): bool
    {
        if (is_null($this->key)) {
            throw new Exception("The encryption key is not valid.");
        }

        if (strlen($this->key) !== 16) {
            throw new KeyLengthException("The specified key is not the correct size.");
        }

        return true;
    }

    /**
     * Create a partial dynamic key to be stored with a password.
     *
     * @param int $length
     * @return string
     */
    public function dynamicKey(int $length = 16): string
    {
        return Str::random($length);
    }

    /**
     * Get the full key to be used when encrypting a password.
     *
     * @param string $dynamic
     * @return string
     */
    protected function key(string $dynamic): string
    {
        return $this->key . $dynamic;
    }

    /**
     * Return a new Laravel Encrypter instance with the key and cipher set.
     *
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
     * Encrypt a string into a storable password.
     *
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
     * Decrypt a stored password back to the original string.
     *
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