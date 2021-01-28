<?php

namespace App\Admin\Controllers;

use App\Message;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthController extends BaseAuthController
{
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
