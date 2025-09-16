<?php

namespace App\Jobs\Repository;

use App\Enums\QueueNamesEnum;
use App\Http\Clients\ComposerClient;
use App\Models\Release;
use App\Models\Repository;
use App\Services\Release\ReleaseExtractor;
use App\Support\Facades\ReleaseStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(Repository $repository, array<string, mixed> $data)
 */
class CreateReleaseJob implements ShouldQueue
{
    use Queueable;

    /**
     * The ComposerClient instance.
     */
    public ComposerClient $client;

    /**
     * The new Release instance.
     */
    public Release $release;

    /**
     * Create a new job instance.
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
     *  }  $data
     */
    public function __construct(public Repository $repository, public array $data)
    {
        $this->onQueue(QueueNamesEnum::Repository->value);
    }

    /**
     * Execute the job.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function handle(ReleaseExtractor $releaseExtractor): void
    {
        $this->initialize();

        $releaseExtractor->extract($this->release, $this->client, $this->data['dist']['url']);

        $this->release->files = $this->mappedReleaseFiles();
        $this->release->save();
    }

    /**
     * Map release files to their respective checksums.
     *
     * @return array<string, string>
     */
    protected function mappedReleaseFiles(): array
    {
        return Arr::mapWithKeys(
            ReleaseStorage::allFiles($this->release->path()),
            fn (string $file): array => [$this->relativeReleasePath($file) => ReleaseStorage::checksum($file)]
        );
    }

    /**
     * Maps and trims the given release file path based on the release's storage path.
     */
    protected function relativeReleasePath(string $file): string
    {
        return str_replace($this->release->path() . '/', '', $file);
    }

    /**
     * Initialize the Job handle.
     */
    protected function initialize(): void
    {
        $this->client = $this->repository->composerClient();

        $this->release = new Release([
            'version' => $this->data['version'],
            'version_normalized' => $this->data['version_normalized'],
            'require' => $this->data['require'],
            'require_dev' => empty($this->data['require-dev']) ? [] : $this->data['require-dev'],
            'released_at' => Carbon::parse($this->data['time']),
        ]);

        $this->release->repository()->associate($this->repository);
    }
}
