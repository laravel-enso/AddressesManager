<?php

namespace LaravelEnso\Addresses\DynamicRelations\Addressables;

use Closure;
use LaravelEnso\Addresses\Models\Address;
use LaravelEnso\DynamicMethods\Contracts\Method;

class Addresss implements Method
{
    public function name(): string
    {
        return 'address';
    }

    public function closure(): Closure
    {
        return fn () => $this->morphOne(Address::class, 'addressable')
            ->whereIsDefault(true);
    }
}
