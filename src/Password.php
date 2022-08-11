<?php

namespace Benjafield\LaravelPasswordManager;

use Benjafield\LaravelPasswordManager\Contracts\MakesPassword;
use Benjafield\LaravelPasswordManager\Traits\CanMakePassword;
use Illuminate\Database\Eloquent\Model;

class Password extends Model implements MakesPassword
{
    use CanMakePassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'dynamic', 'password',
    ];

    /**
     * Create a new Eloquent instance and set the database table.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('passwords.table'));
    }
}