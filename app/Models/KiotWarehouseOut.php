<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotWarehouseOut extends Model
{
    use HasFactory;
    protected $fillable = ['warehouseout_id', 'kiot_warehouseout_code','kiot_warehouseout_id','modifiedDate'];
    public static function create_kiot_warehouseout($kiot_item)
    {
        $kiot_in = KiotWarehouseOut::where('warehouseout_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_in)
        {
            if(!array_key_exists("customerId",$kiot_item))
            {
                 $customer_id = 4;
            }
            else
            {
               
                $kiot_customer = \App\Models\KiotCustomer::where('kiot_customer_id',$kiot_item['customerId'])->first();
                if(!$kiot_customer)
                {
                    $kiotController = new   \App\Http\Controllers\KiotController();
                    $kiotcus = $kiotController->KiotGetCustomer($kiot_item['customerId']);
                    if($kiotcus)
                    {
                        \App\Models\KiotCustomer::create_kiot_customer( $kiotcus);
                        $kiot_customer = \App\Models\KiotCustomer::where('kiot_customer_id',$kiot_item['customerId'])->first();
                        $customer_id = $kiot_customer->customer_id;
                    }
                    else
                    {
                        $customer_id = 4;
                    }
                
                }
                else
                {
                    $customer_id = $kiot_customer->customer_id;
                }
               
            }
            $wh_kiot = \App\Models\KiotBranch::where('kiot_branch_id',$kiot_item['branchId'])->first();
            if($wh_kiot)
                $data['wh_id'] = $wh_kiot->branch_id;
            else
                // return;
                dd($kiot_item);
            $data['customer_id'] = $customer_id;
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
            $vendor = \App\Models\KiotUser::where('kiot_user_id',$kiot_item['soldById'])->first();
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

            $details = $kiot_item['invoiceDetails'];
            $count_item = 0;
            $data['shipcost'] = 0;
            foreach ($details as $detail)
            {
                $count_item += $detail['quantity'];
            }
            $cost_extra = ($data['discount_amount'])/ $count_item ;
            $data['cost_extra'] = $cost_extra ;
            $wo = Warehouseout::c_create($data);
            ///////////////////create detail /////////////////
            foreach ($details as $detail)
            {
                $kiot_product = \App\Models\KiotProduct::where('kiot_product_id', $detail['productId'])->first();
                if(!$kiot_product)
                    return;
                    // dd($kiot_item);

                $product_detail['wo_id'] = $wo->id;
                $product_detail['product_id']= $kiot_product->product_id;
                $product_detail['quantity'] = $detail['quantity'];
                $product_detail['price'] =$detail['price'] -$detail['discount'];
                 //save expired days
                $product = Product::find($kiot_product->product_id);
                $start_date = date('Y-m-d H:i:s');
                if($product->expired)
                {
                    $strday = '+' . $product->expired*30 .' days';
                    $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                    $product_detail['expired_at'] = $end_date;
                }
                $in_ids = Inventory::subProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,$cost_extra);
                // return ($in_ids);
                $product_detail['in_ids'] = json_encode($in_ids);
                WarehouseoutDetail::c_create($product_detail);
                         
            }

              ///create SupTransaction
        $sps = SupTransaction::createSubTrans($wo->id,'wo',-1,$data['final_amount'], $data['customer_id']);
        $wo->suptrans_id = $sps->id;
        ///create paid transaction
        if($data['paid_amount']> 0)
        {
            $in_ids=array();
            if(!array_key_exists("payments",$kiot_item))
            {
                $bank_doc = BankTransaction::insertBankTrans( $vendor_id,1,1,$wo->id,'wo',$data['paid_amount']);
                SupTransaction::createSubTrans(1,'fi',1, $data['paid_amount'], $data['customer_id']); 
                //create suptransaction for transfer money

                $in_id = new \App\Models\Number();
                $in_id->id = $bank_doc->id;
                array_push($in_ids,$in_id);
            
            }
            else
            {
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
                    $bank_doc = BankTransaction::insertBankTrans( $vendor_id,$bankaccount->id,1,$wo->id,'wo',$payment['amount']);
                    SupTransaction::createSubTrans($bank_doc->id,'fi',1, $payment['amount'], $data['customer_id']); 
                    //create suptransaction for transfer money

                    $in_id = new \App\Models\Number();
                    $in_id->id = $bank_doc->id;
                    array_push($in_ids,$in_id);
                }
            }
            $wo->paidtrans_ids = json_encode($in_ids);
 
        }
       ///create ship invocie ///////////
       if($data['shipcost'] > 0)
       {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],1,-1,'ship',$user->id);
            $wo->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,1,-1,$fts->id,'fi',$data['shipcost']);
       }
       
       $wo->save();

           
           $data_kiot['warehouseout_id'] = $wo->id;
           $data_kiot['kiot_warehouseout_id'] = $kiot_item['id'];
           $data_kiot['kiot_warehouseout_code'] = $kiot_item['code'];
           
           if( array_key_exists("modifiedDate",$kiot_item))
               $modifiedDate = $kiot_item['modifiedDate'];
           else
               $modifiedDate = $kiot_item['createdDate'];
           $dateTime = new \DateTime($modifiedDate);
           $data_kiot['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
           \App\Models\KiotWarehouseOut::create($data_kiot);

          
            $kq += 1;
        }
       
        return $kq;
    }
}
