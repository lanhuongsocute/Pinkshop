<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainSentDetail extends Model
{
    use HasFactory;
    protected $fillable = ['ms_id', 'product_id', 'quantity','back','in_ids'];
}
 