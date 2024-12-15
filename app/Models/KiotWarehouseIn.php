<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotWarehouseIn extends Model
{
    use HasFactory;
    protected $fillable = ['warehousein_id', 'kiot_warehousein_id','modifiedDate'];
    public static function create_kiot_warehousein($kiot_item)
    {
        $kiot_in = KiotWarehouseIn::where('kiot_warehousein_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_in)
        {
            if(!array_key_exists("supplierId",$kiot_item))
            {
                 $supplier_id = 4;
            }
            else
            {
                $kiot_supplier = \App\Models\KiotSupplier::where('kiot_supplier_id',$kiot_item['supplierId'])->first();
                if(!$kiot_supplier)
                {
                    \App\Models\KiotSupplier::create_kiot_supplier($kiot_item);
                    $kiot_supplier = \App\Models\KiotSupplier::where('kiot_supplier_id',$kiot_item['supplierId'])->first();
            
                }
                $supplier_id = $kiot_supplier->supplier_id;
            }
            $wh_kiot = \App\Models\KiotBranch::where('kiot_branch_id',$kiot_item['branchId'])->first();
            if($wh_kiot)
                $data['wh_id'] = $wh_kiot->branch_id;
            else
                return;
            $data['supplier_id'] = $supplier_id;
            $data['final_amount'] = $kiot_item['total'];
            $data['paid_amount'] = $kiot_item['totalPayment'];

            if($data['paid_amount'] == $data['final_amount'])
                $data['is_paid'] = 1;
            else
                $data['is_paid'] = 0;
            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['created_at'] = $dateTime;
            $data['updated_at'] = $dateTime;
            // echo '<br/><br/> kiot_supllier)id'.$kiot_item['purchaseById'];
            $vendor = \App\Models\KiotUser::where('kiot_user_id',$kiot_item['purchaseById'])->first();
            if(!$vendor)
            {
                $vendor = auth()->user();
                $vendor_id = $vendor->id;
            }
            else
            {
                $vendor_id =$vendor->user_id;
            }
            $data['vendor_id'] = $vendor_id;
            if( array_key_exists("discount",$kiot_item))
            {
                $data['discount_amount']=$kiot_item['discount'];
            }
            else
                $data['discount_amount']=0;
            
            ///save product detail ////////////
            ////average price///////////////////

            $details = $kiot_item['purchaseOrderDetails'];
            $count_item = 0;
            $data['shipcost'] = 0;
            foreach ($details as $detail)
            {
                $count_item += $detail['quantity'];
            }
            $cost_extra = ($data['shipcost'] -  $data['discount_amount'])/ $count_item ;
            $data['cost_extra'] = $cost_extra ;
            $wi = Warehousein::c_create($data);
            // return $wi;
            ///////////////////create detail /////////////////
            foreach ($details as $detail)
            {
                $kiot_product = \App\Models\KiotProduct::where('kiot_product_id', $detail['productId'])->first();
                if(!$kiot_product)
                    return;
                $product_detail['doc_id'] =  $wi->id;
                $product_detail['doc_type'] = 'wi';
                $product_detail['product_id']= $kiot_product->product_id;
                $product_detail['quantity'] = $detail['quantity'];
                $product_detail['price'] = $detail['price'] -$detail['discount'];
                $product_detail['wh_id'] =  $wh_kiot->branch_id;
                $inv = \App\Models\Inventory::where('product_id',$product_detail['product_id'])
                ->where('wh_id', $product_detail['wh_id'])
                ->first();
                if($inv)
                {
                    $product_detail['prebalance'] =$inv->quantity;
                }
                else
                {
                    $product_detail['prebalance'] = 0;
                }
                
                //save expired days
                $product = Product::find( $kiot_product->product_id);
                $start_date = date('Y-m-d H:i:s');
                if($product->expired)
                {
                    $strday = '+' . $product->expired*30 .' days';
                    $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                    $product_detail['expired_at'] = $end_date;
                }
    
                //  return $product_detail;
                WarehouseInDetail::create($product_detail);
                //increase stock
                Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,$cost_extra);
               
            }
            ///create SupTransaction for delieving products
            $sps = SupTransaction::createSubTrans($wi->id,'wi',1,$data['final_amount'], $data['supplier_id']);
            $wi->suptrans_id = $sps->id;
            ///create paid transaction
            if($data['paid_amount']> 0)
            {
                $in_ids=array();
                $payments = $kiot_item['payments'];
                foreach ($payments as $payment)
                {
                    if($payment['method']=="Cash")
                    {
                        $account_id = 1;
                        $bankaccount = \App\Models\Bankaccount::find($account_id);
                    }    
                    else
                    {
                        if(!array_key_exists("accountId",$kiot_item))
                        {
                            // dd($kiot_item);
                            $account_id = 1;
                            $bankaccount = \App\Models\Bankaccount::find($account_id);
                        }
                        else
                        {
                            $kiot_account = \App\Models\KiotBank::where('kiot_bank_id',$payment['accountId'])->first();
                            $bankaccount = \App\Models\Bankaccount::find($kiot_account->bank_id);
                        }
                      
                    }
                    $bank_doc = BankTransaction::insertBankTrans( $vendor_id,$bankaccount->id,-1,$wi->id,'wi',$payment['amount']);
                    SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $payment['amount'], $data['supplier_id']); 
                    //create suptransaction for transfer money
                    $in_id = new \App\Models\Number();
                    $in_id->id = $bank_doc->id;
                    array_push($in_ids,$in_id);
                }
                $wi->paidtrans_ids = json_encode($in_ids);
               
                
            }
           ///create ship invocie ///////////
           if($data['shipcost'] > 0)
           {
                $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship', $vendor_id);
                $wi->shiptrans_id = $fts->id;
                BankTransaction::insertBankTrans( $vendor_id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
           }
           $wi->save();
           $data_kiot['warehousein_id'] = $wi->id;
           $data_kiot['kiot_warehousein_id'] = $kiot_item['id'];

           if( array_key_exists("modifiedDate",$kiot_item))
               $modifiedDate = $kiot_item['modifiedDate'];
           else
               $modifiedDate = $kiot_item['createdDate'];
           $dateTime = new \DateTime($modifiedDate);
           $data_kiot['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
           \App\Models\KiotWarehouseIn::create($data_kiot);

          
            $kq += 1;
        }
       
        return $kq;
    }
}
