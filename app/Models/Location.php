<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'location';

    protected $fillableCoordinates =
    [
        'business',
        'address1',
        'address2',
        'address3',
        'city',
        'zip_code',
        'country',
        'state',
        'display_address',
    ];

    protected $hidden = [];
}
