<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['total','bank_id','operation','doc_id' ,'doc_type','user_id','pre_balance'];
    public static function c_create($data)
    {
        $mw = BankTransaction::create($data);
        $mw->code = "BTN" . sprintf('%09d',$mw->id);
        if($mw->operation > 0)
            \App\Models\Systrans::add_bank($mw->id,$mw->total,1);
        else
            \App\Models\Systrans::remove_bank($mw->id,$mw->total,1);
        $mw->save();
        return $mw;
    }
    public static function insertBankTrans($user_id,$bank_id,$operation,$doc_id,$doc_type,$total)
    {
        $bank = \App\Models\Bankaccount::where('id',$bank_id)->first();
        $bt['user_id'] =$user_id;
        $bt['bank_id'] = $bank_id;
        $bt['operation'] =$operation;
        $bt['doc_id'] =$doc_id;
        $bt['doc_type'] = $doc_type;
        $bt['total'] = $total;
        $bt['pre_balance'] = $bank->total;
        $bts = BankTransaction::c_create($bt);
        $bank->total += $bt['operation'] * $bt['total'];
        $bank->save();
        return $bts;
    }
    public static function removeBankTrans( $bank_trans )
    {
        $bank = \App\Models\Bankaccount::where('id',$bank_trans->bank_id)->first();
        // $bank->total -= $bank_trans->operation  * $bank_trans->total ;
        // $bank->save();
        // $bank_trans->delete();
        if($bank)
        {
            $user =  auth()->user();
            $fts= FreeTransaction::addFreeTrans($bank_trans->total ,$bank_trans->bank_id,-1*$bank_trans->operation,'huy giao dich:'.$bank_trans->id,$user->id);
            
            BankTransaction::insertBankTrans($user->id,$bank_trans->bank_id,-1*$bank_trans->operation,$fts->id,'fi',$bank_trans->total);
            $bank_trans->doc_type = 'fo';
            $bank_trans->save();
        }
      
        return $fts->id;
    }
}
