<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Brand\Entities\Brand;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static $cache = [];
    protected $fillable = [];

    const PATH_FILE = 'products';

    public function brand(): Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return string|null
     */
    public function getProductImagesAttribute()
    {
        if (!$this->images) return [];
        return array_map(function($item) {
            return [
                'path'  => $item ? $item : null,
                'url' => $item ? config('app.url_image') . $item : null,
            ];
        }, json_decode($this->images));
    }

}
