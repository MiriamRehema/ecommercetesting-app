<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        

    ];
    protected $casts=[
        'images'=> 'array'

    ];
}
