<?php

namespace App\Contracts;

interface FactoryContract
{
    public function fromRequest(FormRequestContract $request): static;
}
