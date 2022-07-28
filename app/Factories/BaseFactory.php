<?php

namespace App\Factories;

use App\Contracts\FactoryContract;
use App\Contracts\FormRequestContract;

use App\Dto\BaseDto;

abstract class BaseFactory implements FactoryContract
{
    public BaseDto $dto;

    /**
     * @param FormRequestContract $request
     *
     * @return static
     */
    public function fromRequest(FormRequestContract $request): static
    {
        $this->dto = $request->toDto();

        return $this;
    }
}
