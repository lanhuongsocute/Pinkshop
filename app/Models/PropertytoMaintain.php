<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertytoMaintain extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id',  'quantity','price','total', 'vendor_id' ];
    public static function c_create($data)
    {
        $mw = PropertytoMaintain::create($data);
        $mw->code = "PTM" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
