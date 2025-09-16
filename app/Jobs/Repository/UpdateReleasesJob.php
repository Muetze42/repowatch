<?php

namespace App\Jobs\Repository;

use App\Enums\QueueNamesEnum;
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
     * @var list<string>
     */
    protected array $existingReleases;

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
        $this->existingReleases = array_values($this->repository->releases()
            ->where('repository_id', $this->repository->id)
            ->pluck('version_normalized')
            ->toArray());

        $composerClient = $this->repository->composerClient();

        foreach ($composerClient->releases() as $release) {
            if (! $this->shouldCreateRelease($release)) {
                continue;
            }

            CreateReleaseJob::dispatch($this->repository, $release);
        }
    }

    /**
     * Determine if a release should create based on the given release data.
     *
     * @param array{
     *      name: string,
     *      version: string,
     *      version_normalized: string,
     *      source: array{
     *          type: string,
     *          url: string,
     *          reference: string,
     *          shasum: string,
     *      },
     *      dist: array{
     *          type: string,
     *          url: string,
     *          reference: string,
     *          shasum: string,
     *      },
     *      require: array<string, string>,
     *      require-dev: array<string, string>,
     *      time: string,
     *  }  $release
     */
    protected function shouldCreateRelease(array $release): bool
    {
        return ! in_array($release['version_normalized'], $this->existingReleases);
    }
}
