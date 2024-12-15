<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontProMod extends Model
{
    use HasFactory;
    protected $fillable = ['title','order_id','mod_type','op_type','status'];
}
