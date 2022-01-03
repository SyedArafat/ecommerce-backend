<?php

namespace App\Ecommerce\Auth;

use App\Models\User;

class UserRepository
{
    public function create($data)
    {
        return User::create($data);
    }
}
