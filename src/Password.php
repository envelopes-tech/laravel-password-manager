<?php

namespace Benjafield\LaravelPasswordManager;

use Benjafield\LaravelPasswordManager\Contracts\MakesPassword;
use Benjafield\LaravelPasswordManager\Traits\CanMakePassword;
use Illuminate\Database\Eloquent\Model;

class Password extends Model implements MakesPassword
{
    use CanMakePassword;

    protected $fillable = [
        'name', 'dynamic', 'password',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('passwords.table'));
    }
}