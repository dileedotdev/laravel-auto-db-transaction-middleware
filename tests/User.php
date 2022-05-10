<?php

namespace Dinhdjj\AutoDBTransaction\Tests;

use Illuminate\Foundation\Auth\User as Model;

class User extends Model
{
    public function getTable()
    {
        return 'users';
    }
}
