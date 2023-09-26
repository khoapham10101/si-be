<?php

namespace Modules\Gender\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected static $cache = [];

    public static function male()
    {
        return static::$cache['Male'] ??= static::where('name', 'Male')->firstOrFail();
    }

    public static function female()
    {
        return static::$cache['Female'] ??= static::where('name', 'Female')->firstOrFail();
    }

    public static function other()
    {
        return static::$cache['Other'] ??= static::where('name', 'Other')->firstOrFail();
    }
}
