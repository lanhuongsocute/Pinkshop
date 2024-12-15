<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotBranch extends Model
{
    use HasFactory;
    protected $fillable = ['branch_id', 'kiot_branch_id','modifiedDate'];
    public static function create_kiot_branch($kiot_item)
    {
        $kiot_cus = KiotBranch::where('kiot_branch_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_cus)
        {
            if(array_key_exists("branchCode",$kiot_item))
            {
                $data_shop['code'] = $kiot_item['branchCode'];
               
            }
            
            $data_shop['title'] = $kiot_item['branchName'];
            if(array_key_exists("contactNumber",$kiot_item))
            {
                $data_shop['phone'] = $kiot_item['contactNumber'];
               
            }
            $data_shop['address'] =$kiot_item['address'];
           
           
            $bra_shop = \App\Models\Warehouse::create($data_shop);
            $data['branch_id'] = $bra_shop->id;
            $data['kiot_branch_id'] = $kiot_item['id'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\KiotBranch::create($data);
            $kq += 1;
        }
        else
        {
            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            // echo '<br/><br/><br/>$kiot_cus->modifiedDate'. $kiot_cus->modifiedDate;
            // echo '<br/>'.$dateTime->format('Y-m-d H:i:s');
            if( array_key_exists("modifiedDate",$kiot_item) && $kiot_cus->modifiedDate != $dateTime->format('Y-m-d H:i:s'))
            {
                // echo 'co cn';
                $shop_cus = \App\Models\Warehouse::find($kiot_cus->customer_id);
               
                $data_shop['title'] = $kiot_item['name'];
                 
                if(array_key_exists("address",$kiot_item))
                {
                    $data_shop['address'] =  $kiot_item['address'];
                }
               
                 
                if( array_key_exists("modifiedDate",$kiot_item))
                    $modifiedDate = $kiot_item['modifiedDate'];
                else
                    $modifiedDate = $kiot_item['createdDate'];
                $dateTime = new \DateTime($modifiedDate);
                $shop_cus->fill($data_shop)->save();

                $kiot_cus->modifiedDate = $dateTime->format('Y-m-d H:i:s');
                $kiot_cus->save();
                $kq += 1;
            }
        }
        return $kq;
    }
}
 