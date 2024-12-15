<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleFunction extends Model
{
    use HasFactory;
    protected $fillable = ['role_id','cfunction_id','value' ];
}
