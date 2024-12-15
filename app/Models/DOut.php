<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DOut extends Model
{
    use HasFactory;
    protected $fillable = ['code','outid','version','wh_id', 'customer_id', 'vendor_id','final_amount','discount_amount','paid_amount','is_paid','suptrans_id','paidtrans_id','shiptrans_id','delivery_id','cost_extra','status'];
   
}
