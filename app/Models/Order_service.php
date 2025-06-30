<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order_service extends Model
{
    use HasFactory;
     protected $fillable=[
      'service_request_id',
       'service_id',
       'quantity',
       'unit_amout',
       'total_amount'

    ];

    public function requests(){
    return $this->belongsTo(Service_requests::class,);
   }
   public function service(){
    return $this->belongsTo(Service::class);
   }
}
