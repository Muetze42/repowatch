<?php

namespace App\Observers;

use App\Models\Release;
use App\Support\Facades\ReleaseStorage;

class ReleaseObserver
{
    /**
     * Handle the Release "deleted" event.
     */
    public function deleted(Release $release): void
    {
        if (ReleaseStorage::directoryExists($release->path())) {
            ReleaseStorage::deleteDirectory($release->path());
        }
    }
}
