<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'tags',
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
            'tags' => 'array',
            'username' => 'encrypted',
            'password' => 'encrypted',
        ];
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
}
