<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class KiotCustomer extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'kiot_customer_id','modifiedDate'];
    public static function create_kiot_customer($kiot_item)
    {
        $kiot_cus = KiotCustomer::where('kiot_customer_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_cus)
        {
            $data_shop['code'] = $kiot_item['code'];
            $data_shop['full_name'] = $kiot_item['name'];
            if(array_key_exists("contactNumber",$kiot_item))
            {
                $data_shop['phone'] = $kiot_item['contactNumber'];
                $data_shop['username'] = $kiot_item['contactNumber'];
            }
            else
            {
                $data_shop['phone'] =  time();
            }
           
            $data_shop['address'] = "";
            $data_shop['email'] = $data_shop['phone'].'@gmail.com';
            $data_shop['role'] = 'customer';
            $data_shop['status'] = 'inactive';
            $data_shop['password']=$data_shop['phone'];
            $data_shop['password'] = Hash::make($data_shop['password']);
            if(array_key_exists("address",$kiot_item))
            {
                $data_shop['address'] =  $kiot_item['address'];
            }
            if(array_key_exists("locationName",$kiot_item))
            {
                $data_shop['address'] .= ' '.  $kiot_item['locationName'];
            }
            if(array_key_exists("taxCode",$kiot_item))
            {
                $data_shop['taxCode'] = $kiot_item['taxCode'];
            }
            
            if(array_key_exists("email",$kiot_item) &&  $data_shop['email'] != '')
            {
                $data_shop['email'] =  $data_shop['email'];
            } 
            $data_shop['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
            $cus_shop = \App\Models\User::create($data_shop);
            $data['customer_id'] = $cus_shop->id;
            $data['kiot_customer_id'] = $kiot_item['id'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\KiotCustomer::create($data);
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
                $shop_cus = \App\Models\User::find($kiot_cus->customer_id);
               
                $data_shop['full_name'] = $kiot_item['name'];
                 
                if(array_key_exists("address",$kiot_item))
                {
                    $data_shop['address'] =  $kiot_item['address'];
                }
                if(array_key_exists("locationName",$kiot_item))
                {
                    $data_shop['address'] .= ' '.  $kiot_item['locationName'];
                }
                if(array_key_exists("taxCode",$kiot_item))
                {
                    $data_shop['taxCode'] = $kiot_item['taxCode'];
                }
                if(array_key_exists("email",$kiot_item))
                {
                    $data_shop['email'] = $kiot_item['email'];
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
