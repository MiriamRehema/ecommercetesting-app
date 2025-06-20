<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'slug',
        'image',
        'description',
        'price',
        'is_active',
        'category_id',
        'stock',
        'is_featured',
        'is_new',
        'is_on_sale',
        
        


    ];

    protected $casts=[
        'image'=> 'array'

    ];

     public function category(){
        return $this->belongsTo(Category::class);
    }
     public function items(){
        return $this->hasMany(Order_item::class);
    }
   
     public function reviews(){
        return $this->HasMany(Review::class);
    }
   
    
}
