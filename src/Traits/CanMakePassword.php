<?php

namespace Benjafield\LaravelPasswordManager\Traits;

trait CanMakePassword
{
    protected string $text;

    public static function make(string $name, string $dynamic, string $password, string $text = null): self
    {
        $attributes = compact('name', 'dynamic', 'password');

        return (new self($attributes))
            ->setText($text);
    }

    public function setText(string $value): self
    {
        $this->text = $value;
        return $this;
    }

    public function text(): string|null
    {
        return $this->text;
    }
}