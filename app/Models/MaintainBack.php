<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainBack extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id','vendor_id','shipcost','cost_extra','final_amount','paid_amount','shiptrans_id','suptrans_id','paidtrans_ids'];
    
}
