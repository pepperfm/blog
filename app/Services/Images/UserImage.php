<?php

namespace App\Services\Images;

use App\Contracts\CommonContract;

use App\Dto\BaseDto;

class UserImage implements CommonContract
{
    public string $uid;
    public string $lastName;
    public string $type;
    /** @var array<string> $programList */
    public array $programList = [];
    /** @var array<string> $akaList */
    public array $akaList = [];
    /** @var array<string> $addressList */
    public array $addressList = [];

    public function __construct(?BaseDto $dto = null)
    {
        if ($dto) {
            $this->loadFromDto($dto);
        }
    }

    public function loadFromDto(BaseDto $dto): static
    {
        /** @var \App\Dto\UserDto $dto */
        $this->uid = $dto->uid;
        $this->lastName = $dto->lastName;
        $this->type = $dto->type;
        $this->programList = $dto->programList;
        $this->akaList = $dto->akaList;
        $this->addressList = $dto->addressList;

        return $this;
    }
}
