<?php

namespace Modules\Role\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Permission\Entities\Permission;
use Modules\User\Entities\User;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    const ADMIN = 'admin';
    const SALER = 'saler';
    const USER = 'user';

    protected $fillable = [];

    protected static $cache = [];

    public static function administrator()
    {
        return static::$cache[static::ADMIN] ??= static::where('name', static::ADMIN)->firstOrFail();
    }

    public static function saler()
    {
        return static::$cache[static::SALER] ??= static::where('name', static::SALER)->firstOrFail();
    }

    public static function user()
    {
        return static::$cache[static::USER] ??= static::where('name', static::USER)->firstOrFail();
    }

    /**
     * Get the permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Get the users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }

}
