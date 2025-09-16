<?php

namespace App\Http\Clients;

use App\Exceptions\Composer\ComposerTooManyRequestsException;
use App\Exceptions\Composer\ComposerUnauthorizedException;
use App\Models\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ComposerClient
{
    /**
     * The client instance.
     */
    public PendingRequest $client;

    public function __construct(public Repository $repository)
    {
        $this->client = Http::baseUrl($repository->feed_url);

        $username = $repository->username;
        $password = $repository->password;

        if (! empty($username) && ! empty($password)) {
            $this->client->withBasicAuth($username, $password);
        }
    }

    /**
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function metadataUrl(): string
    {
        $response = $this->client->get('packages.json');
        $this->validateResponse($response);

        $data = $response->json();

        return $data['metadata-url'];
    }

    /**
     * @return array<array-key, array{
     *     name: string,
     *     version: string,
     *     version_normalized: string,
     *     source: array{
     *         type: string,
     *         url: string,
     *         reference: string,
     *         shasum: string,
     *     },
     *     dist: array{
     *         type: string,
     *         url: string,
     *         reference: string,
     *         shasum: string,
     *     },
     *     require: array<string, string>,
     *     require-dev: array<string, string>,
     *     time: string,
     * }>
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function releases(): array
    {
        $releases = $this->allReleases();

        return Arr::where(
            $releases,
            function (array $value) {
                return Carbon::parse($value['time'])
                    ->addDays($this->repository->max_age_days)
                    ->isNowOrFuture();
            }
        );
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function validateResponse(Response $response): void
    {
        if ($response->unauthorized() || $response->forbidden()) {
            throw new ComposerUnauthorizedException($response, $response->toException());
        }

        if ($response->tooManyRequests()) {
            throw new ComposerTooManyRequestsException($response, $response->toException());
        }

        $response->throw();
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function validateLicense(string $username, string $password): bool
    {
        $this->client->withBasicAuth($username, $password);

        try {
            $allReleases = $this->allReleases();
        } catch (ComposerUnauthorizedException) {
            return false;
        }

        if ($allReleases === []) {
            return false;
        }

        $testFile = data_get(last($allReleases), 'dist.url');

        if (! is_string($testFile)) {
            return false;
        }

        return $this->client->head($testFile)->successful();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function allReleases(): array
    {
        $metadataUrl = str_replace('%package%', $this->repository->package_name, $this->metadataUrl());
        $response = $this->client->get($metadataUrl);

        $this->validateResponse($response);

        return $response->json()['packages'][$this->repository->package_name];
    }
}
