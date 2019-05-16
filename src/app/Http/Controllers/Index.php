<?php

namespace LaravelEnso\Addresses\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Addresses\app\Models\Address;
use LaravelEnso\Addresses\App\Http\Requests\ValidateIndexRequest;
use LaravelEnso\Addresses\app\Http\Resources\Address as Resource;

class Index extends Controller
{
    public function __invoke(ValidateIndexRequest $request)
    {
        return Resource::collection(
            Address::for($request->validated())
                ->ordered()
                ->get()
        );
    }
}
