<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheckDetail extends Model
{
    use HasFactory;
    protected $fillable = ['ic_id','product_id','quantity','error','operation','ids'];
}
 