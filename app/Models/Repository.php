<?php

namespace App\Models;

use App\Http\Clients\ComposerClient;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Repository>
 */
class Repository extends Model
{
    /**
     * @use HasFactory<\Database\Factories\RepositoryFactory>
     */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'display_name',
        'package_name',
        'feed_url',
        'website_url',
        'max_age_days',
        'description',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'feed_url',
        'username',
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'max_age_days' => 'int',
            'username' => 'encrypted',
            'password' => 'encrypted',
        ];
    }

    /**
     * Get the latest release date as a Carbon instance.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<Carbon|null, never>
     */
    protected function latestReleaseDate(): Attribute
    {
        return Attribute::make(
            get: static fn ($value, $attributes) => isset($attributes['releases_max_released_at'])
                ? Carbon::parse($attributes['releases_max_released_at'])
                : null,
        );
    }

    /**
     * Get the package vendor.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function vendor(): Attribute
    {
        return new Attribute(
            get: fn (): string => $this->vendorPackagePart(0),
        );
    }

    /**
     * Get the package name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn (): string => $this->vendorPackagePart(1),
        );
    }

    /**
     * Get a specific part of the package name by an index.
     */
    protected function vendorPackagePart(int $index): string
    {
        $parts = explode('/', $this->package_name);

        if (! isset($parts[$index]) || ! is_string($parts[$index])) {
            throw new InvalidArgumentException(sprintf('Invalid version part index for Repository [%d]', $this->id));
        }

        return (string) $parts[$index];
    }

    /**
     * Get the releases for this repository.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Release, $this>
     */
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    /**
     * Create a new ComposerClient instance for this model.
     */
    public function composerClient(): ComposerClient
    {
        return new ComposerClient($this);
    }
}
