<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintainToWarehouse extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id', 'wh_id','quantity','price','total','time','vendor_id','in_ids' ];
    public static function c_create($data)
    {
        $mw = MaintainToWarehouse::create($data);
        $mw->code = "MTW" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
     
}
