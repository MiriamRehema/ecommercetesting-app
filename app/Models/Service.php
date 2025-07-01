<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_active',
        

    ];
    protected $casts=[
        'image'=> 'array'

    ];
    
    public function reviews(){
        return $this->belongsToMany(Review::class);
    }
    public function services(){
        return $this->belongsToMany(Order_service::class);
    }
    
   
}
