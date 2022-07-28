<?php

namespace App\Dto;

class AddressDto extends BaseDto
{
    public string $uid;
    public ?string $address1 = null;
    public ?string $city = null;
    public string $country;
    public ?string $portalCode = null;
}
