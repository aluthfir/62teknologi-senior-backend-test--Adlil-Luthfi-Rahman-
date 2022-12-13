<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function businessesKey()
    {
//        return $this->hasMany(Categories::class, 'business', 'id');
//        return $this->hasMany(Coordinates::class, 'business', 'id');
//        return $this->hasMany(Location::class, 'business', 'id');
    }

    protected $hidden = [];
}
