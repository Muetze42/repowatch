<?php

namespace App\Models;

use App\Observers\ReleaseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Illuminate\Filesystem\join_paths;

/**
 * @property array<array-key, mixed> $files
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Release>
 */
#[ObservedBy([ReleaseObserver::class])]
class Release extends Model
{
    /**
     * @use HasFactory<\Database\Factories\ReleaseFactory>
     */
    use HasFactory;

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'version',
        'version_normalized',
        'require',
        'require_dev',
        'files',
        'released_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'major_version',
        'minor_version',
        'patch_version',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'require' => 'array',
            'require_dev' => 'array',
            'files' => 'array',
            'released_at' => 'datetime',
        ];
    }

    /**
     * Get the repository that owns the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    /**
     * Get the major version.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function majorVersion(): Attribute
    {
        return new Attribute(
            get: fn (): int => $this->versionPart(0),
        );
    }

    /**
     * Get the minor version.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function minorVersion(): Attribute
    {
        return new Attribute(
            get: fn (): int => $this->versionPart(1),
        );
    }

    /**
     * Get the patch version.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function patchVersion(): Attribute
    {
        return new Attribute(
            get: fn (): int => $this->versionPart(2),
        );
    }

    /**
     * Get a specific part of the version number by an index.
     */
    protected function versionPart(int $index): int
    {
        $parts = explode('.', $this->version_normalized ?? '0.0.0');

        return (int) ($parts[$index] ?? 0);
    }

    /**
     * Generate the full path based on the package name, major version, and version.
     */
    public function path(string $path = '', bool $full = true): string
    {
        $paths = [
            $this->repository?->package_name,
            $this->major_version,
        ];

        if ($full) {
            $paths[] = $this->version;
        }

        return join_paths(implode('/', $paths), $path);
    }
}
