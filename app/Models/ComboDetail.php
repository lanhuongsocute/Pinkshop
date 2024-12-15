<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboDetail extends Model
{
    use HasFactory;
    protected $fillable = ['combo_id','product_id','price','quantity','status'  ,'is_deleted'];
}
