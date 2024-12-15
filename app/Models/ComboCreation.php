<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboCreation extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','quantity','price','wh_id','user_id','is_deleted' ];
}
