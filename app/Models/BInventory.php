<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BInventory extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','wh_id','quantity','price' ];
}
