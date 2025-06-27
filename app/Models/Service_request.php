<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_request extends Model
{
    use HasFactory;
     protected $fillable=[
        'user_id',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',

    ];

    public function user(){
    return $this->belongsTo(User::class);
   }
   public function services(){
    return $this->HasMany(Order_service::class);
   }
}
