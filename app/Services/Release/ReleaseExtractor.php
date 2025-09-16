<?php

namespace App\Services\Release;

use App\Exceptions\Filesystem\InvalidReleaseArchiveException;
use App\Http\Clients\ComposerClient;
use App\Models\Release;
use App\Support\Facades\ReleaseStorage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ReleaseExtractor
{
    protected Release $release;

    /**
     * Extract a zip archive from the given URL to the target destination path.
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function extract(Release $release, ComposerClient $composerClient, string $distUrl): void
    {
        $this->release = $release;

        $distFile = basename($distUrl);
        Storage::disk('temp')->put($distFile, $composerClient->client->get($distUrl)->body());

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
}
