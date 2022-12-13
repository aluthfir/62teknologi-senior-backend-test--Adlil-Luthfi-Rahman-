<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'business',
        'alias',
        'title',
    ];

    protected $hidden = [];

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Business::class,
            table: 'business_category',
            foreignPivotKey: 'category_id',
            relatedPivotKey: 'business_id'
        );
    }
}
