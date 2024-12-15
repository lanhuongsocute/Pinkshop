<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class KiotSupplier extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'kiot_supplier_id','modifiedDate'];
    public static function create_kiot_supplier($kiot_item)
    {
        $kiot_cus = Kiotsupplier::where('kiot_supplier_id', $kiot_item['supplierId'])->first();
        $kq = 0;
        $dc = 0;
        
        if(!$kiot_cus)
        {
            $data_shop['code'] = $kiot_item['supplierCode'];
            $data_shop['full_name'] = $kiot_item['supplierName'];
             
            $data_shop['phone'] = $kiot_item['supplierCode'];
            $data_shop['username'] = $kiot_item['supplierCode'];
            
           
            $data_shop['address'] = "";
            $data_shop['email'] = $data_shop['phone'].'@gmail.com';
            $data_shop['role'] = 'supplier';
            $data_shop['status'] = 'inactive';
            $data_shop['password']=$data_shop['phone'];
            $data_shop['password'] = Hash::make($data_shop['password']);
            
            $data_shop['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
            $cus_shop = \App\Models\User::create($data_shop);
            $data['supplier_id'] = $cus_shop->id;
            $data['kiot_supplier_id'] = $kiot_item['supplierId'];

            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\Kiotsupplier::create($data);
            $kq += 1;
        }
        else
        {
            
                // echo 'co cn';
                // $shop_cus = \App\Models\User::find($kiot_cus->supplier_id);
               
                // $data_shop['full_name'] = $kiot_item['name'];
                 
                // if(array_key_exists("address",$kiot_item))
                // {
                //     $data_shop['address'] =  $kiot_item['address'];
                // }
                // if(array_key_exists("locationName",$kiot_item))
                // {
                //     $data_shop['address'] .= ' '.  $kiot_item['locationName'];
                // }
                // if(array_key_exists("taxCode",$kiot_item))
                // {
                //     $data_shop['taxCode'] = $kiot_item['taxCode'];
                // }
                // if(array_key_exists("email",$kiot_item))
                // {
                //     $data_shop['email'] = $kiot_item['email'];
                // } 
                // if(array_key_exists("contactNumber",$kiot_item))
                // {
                //     $data_shop['phone'] = $kiot_item['contactNumber'];
                //     $data_shop['username'] = $kiot_item['contactNumber'];
                // }
                // $shop_cus->fill($data_shop)->save();
                // $kq += 1;
            
        }
        return $kq;
    }
}
