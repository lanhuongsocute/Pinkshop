<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DIndetail extends Model
{
    use HasFactory;
    protected $fillable = ['doc_id','doc_type','wh_id', 'product_id', 'quantity','price','qty_sold','expired_at'];
}
