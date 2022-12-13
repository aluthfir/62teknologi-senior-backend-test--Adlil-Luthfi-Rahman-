<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = [
        'key',
        'alias',
        'name',
        'image_url',
        'is_closed',
        'url',
        'review_count',
        'rating',
        'transactions',
        'price',
        'phone',
        'display_phone',
        'distance',
    ];

    protected $hidden = [];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Category::class,
            table: 'business_category',
            foreignPivotKey: 'business_id',
            relatedPivotKey: 'category_id'
        );
    }
}
