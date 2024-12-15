<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontProModDetail extends Model
{
    use HasFactory;
    protected $fillable = ['mod_id','pro_id','order_id','status'];
}
