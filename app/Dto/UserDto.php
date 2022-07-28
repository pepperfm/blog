<?php

namespace App\Dto;

class UserDto extends BaseDto
{
    public string $uid;
    public string $lastName;
    public string $type;
    /** @var array<string> $programList */
    public array $programList = [];
    /** @var array<\App\Dto\AkaListDto> $akaList */
    public array $akaList = [];
    /** @var array<\App\Dto\AddressDto> $addressList */
    public array $addressList = [];
}
