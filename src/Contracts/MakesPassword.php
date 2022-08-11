<?php

namespace Benjafield\LaravelPasswordManager\Contracts;

interface MakesPassword
{
    public static function make(string $name, string $dynamic, string $password, string $text = null): self;

    public function setText(string $value): self;

    public function text(): string|null;
}