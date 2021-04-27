<?php

namespace LaravelEnso\Addresses\DynamicRelations\Addressables;

use Closure;
use LaravelEnso\Addresses\Models\Address;
use LaravelEnso\DynamicMethods\Contracts\Method;

class Addressses implements Method
{
    public function name(): string
    {
        return 'addresses';
    }

    public function closure(): Closure
    {
        return fn () => $this->morphMany(Address::class, 'addressable');
    }
}
