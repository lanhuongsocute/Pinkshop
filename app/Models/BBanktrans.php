<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BBanktrans extends Model
{
    use HasFactory;
    protected $fillable = [ 'bank_id','amount','user_id'];
  
}
