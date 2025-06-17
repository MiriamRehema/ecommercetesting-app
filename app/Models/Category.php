<?php

namespace App\Models;

use Illuminate\Database\Eloquent\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use Hasfactory;
    protected $fillable=[
        'name',
        'slug',
        'image',
        'is_active'

    ];
    public function products(){
        return $this->hasMany(Product::class);
    }

}
