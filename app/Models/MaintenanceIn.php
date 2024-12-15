<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceIn extends Model
{
    use HasFactory;  
    protected $fillable = ['product_id', 'customer_id', 'quantity','sent','description','shipcost','shipback','final_amount','paid_amount','vendor_id','result','status','comment','maincost','suptrans_id','shiptrans_id','paidtrans_ids'];
   
}
