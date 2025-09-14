<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Release>
 */
class Release extends Model
{
    /**
     * @use HasFactory<\Database\Factories\ReleaseFactory>
     */
    use HasFactory;

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
}
