<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $app_logged_in_at
 * @property string|null $app_registered_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $github_id
 * @property Carbon|null $github_logged_in_at
 * @property Carbon|null $github_registered_at
 * @property string|null $discord_id
 * @property Carbon|null $discord_logged_in_at
 * @property Carbon|null $discord_registered_at
 * @property mixed $0
 * @property mixed $1
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAppLoggedInAt($value)
 * @method static Builder|User whereAppRegisteredAt($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDiscordId($value)
 * @method static Builder|User whereDiscordLoggedInAt($value)
 * @method static Builder|User whereDiscordRegisteredAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereGithubId($value)
 * @method static Builder|User whereGithubLoggedInAt($value)
 * @method static Builder|User whereGithubRegisteredAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static function createFromRequest($data) : self
    {
        $user = new self();
        $user->name = $data['name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->password = Hash::make($data['password'] ?? null);
        $user->app_registered_at = Carbon::now();
        $user->app_logged_in_at = Carbon::now();
        $user->save();
        return $user;
    }

    public function updateFromRequest($data) : self
    {
        $this->password = Hash::make($data['password']);
        $this->app_registered_at = Carbon::now();
        $this->app_logged_in_at = Carbon::now();
        $this->save();
        return $this;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'app_logged_in_at',
        'app_registered_at',
        'github_id',
        'github_logged_in_at',
        'github_registered_at',
        'discord_logged_in_at',
        'discord_registered_at',
        'discord_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'github_id',
        'discord_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'app_logged_in_at' => 'datetime',
        'app_registered_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'github_logged_in_at' => 'datetime',
        'github_registered_at' => 'datetime',
        'discord_logged_in_at' => 'datetime',
        'discord_registered_at' => 'datetime',
    ];
}
