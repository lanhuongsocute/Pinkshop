<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systemaccountyear extends Model
{
    use HasFactory;
    protected $fillable = ['account_id','year','month','total','scount'  ];
}
