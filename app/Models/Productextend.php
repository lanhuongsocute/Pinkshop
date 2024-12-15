<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productextend extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','old_price'];
}
