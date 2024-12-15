<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreetransType extends Model
{
    use HasFactory;
    protected $fillable = ['id','title','status'];
}
