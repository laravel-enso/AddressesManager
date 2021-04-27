<?php

namespace LaravelEnso\Addresses\DynamicRelations\Addressables;

use Closure;
use LaravelEnso\Addresses\Models\Address;
use LaravelEnso\DynamicMethods\Contracts\Method;

class BillingAddresse implements Method
{
    public function name(): string
    {
        return 'billingAddress';
    }

    public function closure(): Closure
    {
        return fn () => $this->morphOne(Address::class, 'addressable')
            ->whereIsBilling(true);
    }
}
