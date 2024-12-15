<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainSent extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'vendor_id', 'shipcost','cost_extra','shiptrans_id','delivery_id','status'  ];
     
}
