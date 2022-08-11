<?php

namespace Benjafield\LaravelPasswordManager\Contracts;

interface MakesPassword
{
    /**
     * Create a new instance of a password model.
     *
     * @param string $name
     * @param string $dynamic
     * @param string $password
     * @param string|null $text
     * @return self
     */
    public static function make(string $name, string $dynamic, string $password, string $text = null): self;

    /**
     * Set the plain text version of the password.
     * Not included when serialized.
     *
     * @param string $value
     * @return $this
     */
    public function setText(string $value): self;

    /**
     * Retrieve the plain text version of the password.
     *
     * @return string|null
     */
    public function text(): string|null;
}