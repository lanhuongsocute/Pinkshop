<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehousetransfer extends Model
{
    use HasFactory;
    protected $fillable = ['wh_id1', 'wh_id2', 'vendor_id1','vendor_id2','author_id','shiptrans_id','delivery_id','cost_extra','total'];
    public static function c_create($data)
    {
        $mw = Warehousetransfer::create($data);
        $mw->code = "WTW" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
 