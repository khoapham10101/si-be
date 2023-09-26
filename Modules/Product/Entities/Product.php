<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Brand\Entities\Brand;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static $cache = [];
    protected $fillable = [];
    protected $appends = ['is_wishlist'];

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
        if (!$this->images) {
            return [];
        }
        return array_map(function ($item) {
            return [
                'path'  => $item ? $item : null,
                'url' => $item ? config('app.url_image') . $item : null,
            ];
        }, json_decode($this->images));
    }

    public function getIsWishlistAttribute()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return $user->wishlists()->where('product_id', $this->id)->exists();
    }
}
