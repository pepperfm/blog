<?php

namespace App\Contracts;

use App\Dto\BaseDto;

interface CommonContract
{
    public function loadFromDto(BaseDto $dto): static;
}
