<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected  $fillable=[
        'product_id',
        'user_id',
        'rating',
        'comment',
        'service_id',
        'service_rating'
        

    ];

    
    public function product(){
        return $this->belongsTo(Products::class);
    }
     public function user(){
    return $this->belongsTo(User::class);
   }
   public function service(){
    return $this->belongsTo(Service::class);
   }
  

   
    
    
}
