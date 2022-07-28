<?php

namespace App\Services;

use App\Contracts\FactoryContract;

abstract class BaseFactoryService
{
    protected FactoryContract $factory;

    /**
     * @return FactoryContract
     */
    public function getFactory(): FactoryContract
    {
        return $this->factory;
    }

    /**
     * @param FactoryContract $factory
     *
     * @return static
     */
    public function setFactory(FactoryContract $factory): static
    {
        $this->factory = $factory;

        return $this;
    }
}
