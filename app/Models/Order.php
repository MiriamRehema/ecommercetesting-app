<?php

namespace App\Models;
use Illuminate\Database\Eloquent\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   use HasFactory;

   protected $fillable=[
    'user_id',
     'grand_total',
     'payment_method',
     'status',
     'payment_status',
     'currency',
     'shipping_amount',
     'shipping_method',
     'notes',
   
   ];

   public function user(){
    return $this->belongsTo(User::class);
   }
   public function items(){
    return $this->HasMany(Order_item::class);
   }
   public function adress(){
    return $this->HasOne(Address::class);
   }
   
}
