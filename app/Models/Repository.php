<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'custom_feed_url',
        'website_url',
        'max_age_days',
        'description',
        'tags',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'custom_feed_url',
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
            'tags' => 'array',
        ];
    }

    /**
     * Get the feed URL attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    protected function feedUrl(): Attribute
    {
        return new Attribute(
            get: fn (): string => $this->custom_feed_url ?: $this->provider->feed_url,
        );
    }

    /**
     * Get the provider that owns the repository.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Provider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
