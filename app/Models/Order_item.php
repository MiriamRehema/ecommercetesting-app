<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order_item extends Model
{
   use HasFactory;

   protected $fillable=[
    'order_id',
    'product_id',
    'quantity',
    'amount',
    'total_amount',
   ];

   public function order(){
    return $this->belongsTo(Order::class);
   }
   public function products(){
    return $this->belongsTo(Products::class);
   }
}
