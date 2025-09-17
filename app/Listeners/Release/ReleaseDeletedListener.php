<?php

namespace App\Listeners\Release;

use App\Events\Release\ReleaseDeletedEvent;
use App\Support\Facades\ReleaseStorage;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReleaseDeletedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReleaseDeletedEvent $releaseDeletedEvent): void
    {
        if (ReleaseStorage::directoryExists($releaseDeletedEvent->releasePath)) {
            ReleaseStorage::deleteDirectory($releaseDeletedEvent->releasePath);
        }
    }
}
