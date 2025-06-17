<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasFactory;

class Review extends Model
{
    use HasFactory;

    private $fillable=[
        'product_id',
        'user_id',
        'rating',
        'comment',
        

    ];

    
     public function product(){
        return $this->belongsTo(Product::class);
    }
     public function user(){
    return $this->belongsTo(User::class);
   }
    
    
}
