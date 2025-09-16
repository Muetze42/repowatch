<?php

namespace App\Jobs\Repository;

use App\Enums\QueueNamesEnum;
use App\Exceptions\Filesystem\InvalidReleaseArchiveException;
use App\Http\Clients\ComposerClient;
use App\Models\Release;
use App\Models\Repository;
use App\Support\Facades\ReleaseStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
    public function handle(): void
    {
        $this->initialize();
        $this->extractFiles();

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
     * Extract the distribution files from the provided archive URL.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    protected function extractFiles(): void
    {
        $distFile = basename($this->data['dist']['url']);
        Storage::disk('temp')->put($distFile, $this->client->client->get($this->data['dist']['url'])->body());

        $zip = new ZipArchive();
        $resource = $zip->open(Storage::disk('temp')->path($distFile));

        if ($resource !== true) {
            throw new InvalidReleaseArchiveException(sprintf('Failed to open zip file %s', $distFile));
        }

        if (! $this->extractedNested($zip)) {
            $zip->extractTo(ReleaseStorage::path($this->release->path()));
        }

        $zip->close();

        Storage::disk('temp')->delete($distFile);
    }

    /**
     * Extracts and processes nested files within a zip archive. Return true or false if the archive has nested files or not.
     */
    protected function extractedNested(ZipArchive $zipArchive): bool
    {
        $paths = [];
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $paths[] = $zipArchive->getNameIndex($i);
        }

        $filteredPaths = Arr::map($paths, static fn (string $path): string => trim($path, '/'));
        $filteredPaths = Arr::where($filteredPaths, static fn (string $path): bool => ! str_contains($path, '/'));

        if (count($filteredPaths) !== 1) {
            return false;
        }

        foreach ($paths as $path) {
            $this->handleNestedPath($zipArchive, $path);
        }

        return true;
    }

    /**
     * Handle the processing of a nested path within a release archive.
     */
    protected function handleNestedPath(ZipArchive $zipArchive, string|false $path): void
    {
        if (! is_string($path)) {
            throw new InvalidReleaseArchiveException('Failed to extract nested files');
        }

        $pathParts = explode('/', $path, 2);

        if (empty($pathParts[1])) {
            return;
        }

        if (str_ends_with($pathParts[1], '/')) {
            File::ensureDirectoryExists(ReleaseStorage::path($this->release->path($pathParts[1])));

            return;
        }

        $stream = $zipArchive->getStream($path);

        if ($stream === false) {
            throw new InvalidReleaseArchiveException(sprintf('Failed to open stream for file %s', $path));
        }

        ReleaseStorage::put($this->release->path($pathParts[1]), $stream);
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
