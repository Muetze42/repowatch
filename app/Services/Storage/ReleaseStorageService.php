<?php

namespace App\Services\Storage;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemManager;

class ReleaseStorageService extends FilesystemManager
{
    /**
     * Create a new filesystem manager instance.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Get the default driver name.
     *
     */
    public function getDefaultDriver(): string
    {
        return 'releases';
    }
}
