<?php

namespace App\Jobs\Repository;

use App\Enums\QueueNamesEnum;
use App\Http\Clients\ComposerClient;
use App\Models\Repository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(Repository $repository)
 */
class UpdateReleasesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Repository $repository)
    {
        $this->onQueue(QueueNamesEnum::Repository->value);
    }

    /**
     * Execute the job.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle(): void
    {
        $existingReleases = $this->repository->releases()
            ->where('repository_id', $this->repository->id)
            ->pluck('version_normalized')
            ->toArray();

        $client = new ComposerClient($this->repository);

        foreach ($client->releases() as $release) {
            if (in_array($release['version_normalized'], $existingReleases, true)) {
                continue;
            }

            CreateReleaseJob::dispatch($this->repository, $release);
        }
    }
}
