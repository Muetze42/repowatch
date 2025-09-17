<?php

namespace App\Events\Release;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @method static mixed dispatch(int $releaseId, string $releasePath)
 */
class ReleaseDeletedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $releaseId, public string $releasePath)
    {
        //
    }
}
