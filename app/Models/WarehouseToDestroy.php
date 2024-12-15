<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseToDestroy extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id', 'wh_id','quantity','price','total', 'vendor_id','in_ids' ];
    public static function c_create($data)
    {
        $mw = WarehouseToDestroy::create($data);
        $mw->code = "WTD" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
