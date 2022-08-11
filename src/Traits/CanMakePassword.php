<?php

namespace Benjafield\LaravelPasswordManager\Traits;

trait CanMakePassword
{
    /**
     * The plain text version of the password.
     *
     * @var string
     */
    protected string $text;

    /**
     * Create a new instance of a password model.
     *
     * @param string $name
     * @param string $dynamic
     * @param string $password
     * @param string|null $text
     * @return self
     */
    public static function make(string $name, string $dynamic, string $password, string $text = null): self
    {
        $attributes = compact('name', 'dynamic', 'password');

        return (new self($attributes))
            ->setText($text);
    }

    /**
     * Set the plain text version of the password.
     * Not included when serialized.
     *
     * @param string $value
     * @return $this
     */
    public function setText(string $value): self
    {
        $this->text = $value;
        return $this;
    }

    /**
     * Retrieve the plain text version of the password.
     *
     * @return string|null
     */
    public function text(): string|null
    {
        return $this->text;
    }
}