<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontOrder extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','order_id','wo_id','invad_id','delad_id','delivery_id','status'];
}
 