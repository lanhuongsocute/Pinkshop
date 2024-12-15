<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotBank extends Model
{
    use HasFactory;
    protected $fillable = ['bank_id', 'kiot_bank_id','modifiedDate'];
    public static function create_kiot_bank($kiot_item)
    {
        $kiot_cus = KiotBank::where('kiot_bank_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_cus)
        {
             
            
            $data_shop['title'] = $kiot_item['bankName'];
            $data_shop['banknumber'] = $kiot_item['accountNumber'];
            $data_shop['total'] = 0;
            $data_shop['status'] = 'active';
           
            $bra_shop = \App\Models\Bankaccount::create($data_shop);
            $data['bank_id'] = $bra_shop->id;
            $data['kiot_bank_id'] = $kiot_item['id'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\KiotBank::create($data);
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
                $shop_cus = \App\Models\Bankaccount::find($kiot_cus->customer_id);
                $data_shop['title'] = $kiot_item['name'];
                $data_shop['banknumber'] = $kiot_item['accountNumber'];
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
