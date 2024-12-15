<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotCustomergroup extends Model
{
    use HasFactory;
    protected $fillable = ['group_id', 'kiot_group_id','modifiedDate'];
    public static function create_kiot_group($kiot_item)
    {
        $kiot_cus = KiotCustomergroup::where('kiot_group_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_cus)
        {
            
            $data_shop['title'] = $kiot_item['name'];
            if( array_key_exists("description",$kiot_item))
                $data_shop['description'] = $kiot_item['description'];
            $data_shop['status'] = 'active';
            $bra_shop = \App\Models\UGroup::create($data_shop);
            $data['group_id'] = $bra_shop->id;
            $data['kiot_group_id'] = $kiot_item['id'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\KiotCustomergroup::create($data);
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
                $shop_cus = \App\Models\UGroup::find($kiot_cus->customer_id);
                $data_shop['title'] = $kiot_item['name'];
               
                if(array_key_exists("description",$kiot_item))
                {
                    $data_shop['description'] = $kiot_item['description'];
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
