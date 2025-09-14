<?php

namespace App\Support\Facades;

use App\Services\Storage\ReleaseStorageService;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Illuminate\Support\Facades\Storage
 */
class ReleaseStorage extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return ReleaseStorageService::class;
    }
}
