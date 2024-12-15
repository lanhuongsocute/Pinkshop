<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['total','bank_id','operation','content','user_id','type_id'];
    public static function c_create($data)
    {
        $mw = FreeTransaction::create($data);
        $mw->code = "FTN" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
    public static function addFreeTrans_d($ft)
    {
         
        $fts=FreeTransaction::c_create($ft);
        return $fts;
    }
    public static function addFreeTrans($total ,$bank_id ,$operation ,$content , $user_id  )
    {
        $type_id = 0;
        $bt['total'] =$total;
        $bt['bank_id'] = $bank_id;
        $bt['operation'] =$operation;
        $bt['content'] =$content;
        $bt['user_id'] = $user_id;
        $bt['type_id'] = $type_id;
        $fts=FreeTransaction::c_create($bt);
        return $fts;
    }
}
