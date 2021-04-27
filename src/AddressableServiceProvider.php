<?php

namespace LaravelEnso\Addresses;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Addresses\DynamicRelations\Addressables\Addresss;
use LaravelEnso\Addresses\DynamicRelations\Addressables\Addressses;
use LaravelEnso\Addresses\DynamicRelations\Addressables\BillingAddresse;
use LaravelEnso\Addresses\DynamicRelations\Addressables\ShippingAddresses;
use LaravelEnso\Addresses\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class AddressableServiceProvider extends ServiceProvider
{
    protected array $register = [];

    public function boot()
    {
        Collection::wrap($this->register)
            ->each(function ($model) {
                Methods::bind($model, [
                    Addresss::class, Addressses::class, BillingAddresse::class,
                    ShippingAddresses::class,
                ]);

                $model::observe(Observer::class);
            });
    }
}
