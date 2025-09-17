<?php

namespace App\Observers;

use App\Events\Release\ReleaseDeletedEvent;
use App\Models\Release;

class ReleaseObserver
{
    /**
     * Handle the Release "deleted" event.
     */
    public function deleted(Release $release): void
    {
        ReleaseDeletedEvent::dispatch($release->getKey(), $release->path());
    }
}
