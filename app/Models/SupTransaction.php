<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id','doc_id','doc_type',  'operation','amount','total','content','is_delete'];
    public static function c_create($data)
    {
        $mw = SupTransaction::create($data);
        $mw->code = "STN" . sprintf('%09d',$mw->id);
        $mw->save();
        
        return $mw;
    }
    
    public static function createSubTransContent($doc_id,$doc_type,$operation,$amount, $supplier_id,$content)
    {
         ///create SupTransaction
         $supplier = \App\Models\User::where('id',$supplier_id)->first();
         $sptran['doc_id'] = $doc_id;
         $sptran['doc_type'] = $doc_type;
         $sptran['operation']= $operation;
         $sptran['amount']= $amount;
         $sptran['total']= $supplier->budget + $sptran['operation']* $sptran['amount'];
         $sptran['supplier_id']=$supplier_id;
         $sptran['content']=$content;
         $sps = SupTransaction::c_create($sptran);
        //  $supplier->budget = $sptran['total'];
        //  $supplier->save();
         $supplier->update_budget( $sptran['operation'],$sptran['amount'], $sps->id,'st');
        
    
         return $sps;
    }
    public static function updatePaidAmount($operation,$amount, $supplier_id)
    {
        if($amount <= 0)
            return;
        if($operation < 0)
        {
            //list all wi not paid order by time
            $warehouseins = \App\Models\WarehouseIn::where('supplier_id',$supplier_id)->where('status','active')
            ->where('is_paid',false)->orderBy('id','ASC')->get();
            
            $paid_amount = $amount;
            foreach($warehouseins as $warehousein)
            {
                echo $warehousein->final_amount .'-'. $warehousein->paid_amount.': '.($warehousein->final_amount - $warehousein->paid_amount); 
                echo '<br/>$paid_amount: '.($paid_amount); 
                if($paid_amount >= ($warehousein->final_amount - $warehousein->paid_amount))
                {
                    $paid_amount -= ($warehousein->final_amount - $warehousein->paid_amount);
                    $warehousein->paid_amount = $warehousein->final_amount;
                    $warehousein->is_paid = true;
                    $warehousein->save();
                //   echo 'whpaid_amount :'.$warehousein->paid_amount;
                //   echo '<br/>paid_amount :'.$paid_amount ;
                    
                }
                else
                {
                    $warehousein->paid_amount+= $paid_amount;
                    $warehousein->save();

                    $paid_amount = 0;
                //   echo 'whpaid_amount :'.$warehousein->paid_amount;
                //   echo '<br/>paid_amount :'.$paid_amount ;
                }
            
                if($paid_amount == 0)
                    break;
            }
        }
        else
        {
            $warehouseouts = \App\Models\Warehouseout::where('customer_id',$supplier_id)->where('status','active')
            ->where('is_paid',false)->orderBy('id','ASC')->get();
            $paid_amount = $amount;
            foreach($warehouseouts as $warehouseout)
            {
                if($paid_amount >= ($warehouseout->final_amount - $warehouseout->paid_amount))
                {
                    $paid_amount -= ($warehouseout->final_amount - $warehouseout->paid_amount);
                    $warehouseout->paid_amount = $warehouseout->final_amount;
                    $warehouseout->is_paid = true;
                    $warehouseout->save();
                    
                }
                else
                {
                    $warehouseout->paid_amount+= $paid_amount;
                    $warehouseout->save();
                    $paid_amount = 0;
                }
                if($paid_amount == 0)
                    break;
            }
        }
        
    }
    public static function createSubTrans($doc_id,$doc_type,$operation,$amount, $supplier_id)
    {
         ///create SupTransaction
         $supplier = \App\Models\User::where('id',$supplier_id)->first();
         $sptran['doc_id'] = $doc_id;
         $sptran['doc_type'] = $doc_type;
         $sptran['operation']= $operation;
         $sptran['amount']= $amount;
         $sptran['total']= $supplier->budget + $sptran['operation']* $sptran['amount'];
         $sptran['supplier_id']=$supplier_id;
         $sps = SupTransaction::c_create($sptran);
        //  $supplier->budget =$sptran['total'];
        //  $supplier->save();
         $supplier->update_budget( $sptran['operation'],$sptran['amount'], $sps->id,'st');
         return $sps;
    }
    public static function removeSubTrans($suptrans_id ,$mode='fo',$doc_id=0)
    {
         ///create SupTransaction
        $suptrans = SupTransaction::find($suptrans_id);
        if($suptrans)
        {
            $suptrans->is_delete = 1;
            $suptrans->save();
            $sub = SupTransaction::createSubTrans($doc_id,$mode,-1* $suptrans->operation,$suptrans->amount, $suptrans->supplier_id);
            $sub->is_delete = 1;
            $sub->save();
        }
        return true;
    }
}
