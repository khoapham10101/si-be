<?php

namespace Modules\UserStatus\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatus extends Model
{
    use SoftDeletes, HasFactory;

    protected static $cache = [];
    protected $fillable = [];

    public static function active()
    {
        return static::$cache['Active'] ??= static::where('name', 'Active')->firstOrFail();
    }

    public static function inactive()
    {
        return static::$cache['Inactive'] ??= static::where('name', 'Inactive')->firstOrFail();
    }
}
