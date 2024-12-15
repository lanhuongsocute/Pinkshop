<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DOutdetail extends Model
{
    use HasFactory;
    protected $fillable = ['wo_id', 'wto_id','product_id', 'quantity','price','expired_at','in_ids'];
  
}
