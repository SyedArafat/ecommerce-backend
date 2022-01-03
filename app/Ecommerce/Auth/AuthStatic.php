<?php

namespace App\Ecommerce\Auth;

class AuthStatic
{
    /**
     * @return string[]
     */
    public static function registerValidateRules(): array
    {
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];
    }
}
