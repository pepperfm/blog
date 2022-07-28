<?php

namespace App\Factories;

use App\Services\Images\UserImage;

use App\Contracts\CommonContract;

class UserFactory extends BaseFactory
{
    public function make(): CommonContract
    {
        return new UserImage($this->dto);
    }
}
