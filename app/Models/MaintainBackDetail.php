<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainBackDetail extends Model
{
    use HasFactory;
    protected $fillable=['mb_id','product_id','quantity','price','in_ids'];
}
 