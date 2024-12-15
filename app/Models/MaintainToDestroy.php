<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainToDestroy extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id',  'quantity','price','total', 'vendor_id','in_ids' ];
    public static function c_create($data)
    {
        $mw = MaintainToDestroy::create($data);
        $mw->code = "MTD" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
 
 