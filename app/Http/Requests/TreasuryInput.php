<?php

namespace App\Http\Requests;

use App\Contracts\FormRequestContract;
use App\Dto\AddressDto;
use App\Dto\AkaListDto;
use App\Dto\BaseDto;
use App\Dto\UserDto;

class TreasuryInput implements FormRequestContract
{
    public string $uid;
    public string $lastName;
    public string $sdnType;
    /** @var array<string> $programList */
    public array $programList = [];
    /** @var array<string> $akaList */
    public array $akaList = [];
    /** @var array<string> $addressList */
    public array $addressList = [];

    public function __construct($data)
    {
        $this->uid = $data->uid;
        $this->lastName = $data->lastName;
        $this->sdnType = $data->sdnType;
        $this->setProgramList($data->programList);
        $this->setAkaList($data->akaList);
        $this->setAddressList($data->addressList);
    }

    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function toDto(): BaseDto
    {
        $dto = new UserDto();
        $dto->uid = $this->uid;
        $dto->lastName = $this->lastName;
        $dto->type = $this->sdnType;
        foreach ($this->programList as $item) {
            $dto->programList[] = $item;
        }
        foreach ($this->akaList as $item) {
            $dto->akaList[] = new AkaListDto($item);
        }
        foreach ($this->addressList as $item) {
            $dto->addressList[] = new AddressDto($item);
        }

        return $dto;
    }

    private function setProgramList($data)
    {
        foreach ((array) $data as $item) {
            // хз какой формат данных, так что...
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $this->akaList[] = (string) $subItem;
                }
                break;
            }
            $this->programList[] = (string) $item;
        }
    }

    private function setAkaList($data)
    {
        foreach ((array) $data as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $this->akaList[] = [
                        'uid' => (string) $subItem->uid,
                        'type' => (string) $subItem->type,
                        'category' => (string) $subItem->category,
                        'lastName' => (string) $subItem->lastName,
                    ];
                }
                break;
            }
            $this->akaList[] = [
                'uid' => (string) $item->uid,
                'type' => (string) $item->type,
                'category' => (string) $item->category,
                'lastName' => (string) $item->lastName,
            ];
        }
    }

    private function setAddressList($data)
    {
        foreach ((array) $data as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $this->akaList[] = [
                        'uid' => (string) $subItem->uid,
                        'type' => (string) $subItem->type,
                        'category' => (string) $subItem->category,
                        'lastName' => (string) $subItem->lastName,
                    ];
                }
                break;
            }
            $this->addressList[] = [
                'uid' => (string) $item->uid,
                'city' => (string) $item->city ?? '',
                'country' => (string) $item->country,
                'address1' => (string) $item->address1 ?? '',
                'postalCode' => (string) $item->postalCode ?? '',
            ];
        }
    }
}
