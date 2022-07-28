<?php

namespace App\Contracts;

use App\Dto\BaseDto;

interface FormRequestContract
{
    public function toDto(): BaseDto;
}
