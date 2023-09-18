<?php

namespace Modules\Permission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Entities\Role;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'action', 'module_name'];

    /**
     * Get the roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
