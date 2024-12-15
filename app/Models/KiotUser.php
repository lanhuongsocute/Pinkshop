<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class KiotUser extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'kiot_user_id','modifiedDate'];
    public static function create_kiot_user($kiot_item)
    {
        $kiot_cus = Kiotuser::where('kiot_user_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_cus)
        {
             
            $data_shop['full_name'] = $kiot_item['givenName'];
            $data_shop['username'] = $kiot_item['userName'];
            if(array_key_exists("mobilePhone",$kiot_item))
            {
                $data_shop['phone'] = $kiot_item['mobilePhone'];
            }
            else
            {
                $data_shop['phone'] =  time();
            }
           
            $data_shop['address'] = "";
            if(array_key_exists("email",$kiot_item))
            {
                $data_shop['email'] =  $kiot_item['email'];
            }
            else
            {
                $data_shop['email'] = $data_shop['phone'].'@gmail.com';
            }
            $data_shop['role'] = 'vendor';
            $data_shop['status'] = 'inactive';
            $data_shop['password']=$data_shop['phone'];
            $data_shop['password'] = Hash::make($data_shop['password']);
            if(array_key_exists("address",$kiot_item))
            {
                $data_shop['address'] =  $kiot_item['address'];
            }
            
            $data_shop['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
            $cus_shop = \App\Models\User::create($data_shop);
            $data['user_id'] = $cus_shop->id;
            $data['kiot_user_id'] = $kiot_item['id'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\Kiotuser::create($data);
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
                $shop_cus = \App\Models\User::find($kiot_cus->user_id);
               
                $data_shop['full_name'] = $kiot_item['givenName'];
                 
                if(array_key_exists("address",$kiot_item))
                {
                    $data_shop['address'] =  $kiot_item['address'];
                }
               
                if(array_key_exists("email",$kiot_item))
                {
                    $data_shop['email'] = $kiot_item['email'];
                } 
                 
               
                $shop_cus->fill($data_shop)->save();
                
                if( array_key_exists("modifiedDate",$kiot_item))
                    $modifiedDate = $kiot_item['modifiedDate'];
                else
                    $modifiedDate = $kiot_item['createdDate'];
                $dateTime = new \DateTime($modifiedDate);
               

                $kiot_cus->modifiedDate = $dateTime->format('Y-m-d H:i:s');
                $kiot_cus->save();
                $kq += 1;
            }
        }
        return $kq;
    }
}
