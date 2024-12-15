<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertytoWarehouse extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id', 'wh_id','quantity','price','total', 'vendor_id','in_ids' ];
    public static function c_create($data)
    {
        $mw = PropertytoWarehouse::create($data);
        $mw->code = "PTW" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
