<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bankaccount extends Model
{
    use HasFactory;
    protected $fillable = ['title','banknumber','total','status' ];
    public static function deleteBankaccount($id){
        $freetrans =  \App\Models\FreeTransaction::where('bank_id',$id)->get();
        $banktrans =  \App\Models\BankTransaction::where('bank_id',$id)->get();
        $bbanktrans =  \App\Models\BBanktrans::where('bank_id',$id)->get();
        if( count($freetrans) >  0 || count($banktrans) > 0 || count($bbanktrans) > 0)
            return 0;
        else
        {
           //kiem tra cac rang buoc khac phieu nhap kho xuat kho 
            $bankaccount = \App\Models\Bankaccount::find($id);
            if($bankaccount)
                $bankaccount->delete();
           return 1;
        }
    }
}
