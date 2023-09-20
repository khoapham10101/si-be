<?php

namespace Modules\Wishlist\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = 'wishlists';

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
