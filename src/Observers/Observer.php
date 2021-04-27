<?php

namespace LaravelEnso\Addresses\Observers;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class Observer
{
    private function deleting()
    {
        $shouldRestrict = Config::get('enso.addresses.onDelete') === 'restrict'
            && $model->addresses()->exists();

        if ($shouldRestrict) {
            throw new ConflictHttpException(
                __('The entity has addresses and cannot be deleted')
            );
        }
    }

    private function deleted()
    {
        if (Config::get('enso.addresses.onDelete') === 'cascade') {
            $model->addresses()->delete();
        }
    }
}
