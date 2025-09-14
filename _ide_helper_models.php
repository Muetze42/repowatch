<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Provider>
 * @property int $id
 * @property string $name
 * @property string $feed_url
 * @property string|null $website_url
 * @property string $username
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Repository> $repositories
 * @property-read int|null $repositories_count
 * @method static \Database\Factories\ProviderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provider whereWebsiteUrl($value)
 */
	class Provider extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Release>
 * @property int $id
 * @property int $repository_id
 * @property string $version
 * @property string $version_normalized
 * @property array<array-key, mixed> $require
 * @property array<array-key, mixed> $require_dev
 * @property array<array-key, mixed> $files
 * @property \Illuminate\Support\Carbon|null $released_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Repository $repository
 * @method static \Database\Factories\ReleaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereReleasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereRepositoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereRequire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereRequireDev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Release whereVersionNormalized($value)
 */
	class Release extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Repository>
 * @property int $id
 * @property int $provider_id
 * @property string $display_name
 * @property string $package_name
 * @property string|null $custom_feed_url
 * @property string|null $website_url
 * @property string|null $description
 * @property array<array-key, mixed>|null $tags
 * @property int|null $max_age_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $feed_url
 * @property-read \App\Models\Provider $provider
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Release> $releases
 * @property-read int|null $releases_count
 * @method static \Database\Factories\RepositoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereCustomFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereMaxAgeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository wherePackageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Repository whereWebsiteUrl($value)
 */
	class Repository extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $github_id
 * @property string $github_login
 * @property string $name
 * @property string|null $email
 * @property string|null $avatar_url
 * @property bool $admin
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_admin
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGithubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGithubLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

