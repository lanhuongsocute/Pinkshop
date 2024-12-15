<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertytoDestroy extends Model
{
    use HasFactory;
    protected $fillable = ['code','product_id',  'quantity','price','total', 'vendor_id' ];
    public static function c_create($data)
    {
        $mw = PropertytoDestroy::create($data);
        $mw->code = "PTD" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
