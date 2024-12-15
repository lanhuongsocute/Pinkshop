<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class KiotProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'kiot_product_id','modifiedDate'];
    public static function create_kiot_product($kiot_item,$save_image=0)
    {
        $kiot_pro = KiotProduct::where('kiot_product_id', $kiot_item['id'])->first();
        $kq = 0;
        if(!$kiot_pro)
        {
            $kiot_cat = \App\Models\KiotCat::where('kiotId',$kiot_item['categoryId'])->first();
            if(!$kiot_cat)
                return;
            $data_shop['code'] = $kiot_item['code'];
            $data_shop['title'] = $kiot_item['name'];
            $data_shop['cat_id'] = $kiot_cat->categoryId;
            if($kiot_item['type']==2)
            {
                $data_shop['type']= 'normal';
            }
            if($kiot_item['type']==3)
            {
                $data_shop['type']= 'service';
            }
            $data_shop['summary'] = '';
            if( array_key_exists("description",$kiot_item))
                    $data_shop['description'] = $kiot_item['description'];
            $data_shop['stock'] = 0;
            $data_shop['price_avg'] = $kiot_item['basePrice'];
            if($kiot_item['isActive'] == false)
                $data_shop['status'] = 'inactive';
            
            $data_shop['photo'] = asset('backend/assets/dist/images/no_image_pro.jpg');
            if( array_key_exists("images",$kiot_item))
            {
                $images = $kiot_item['images'];
                foreach($images as $image)
                {
                    if($save_image == 0)
                    {
                        $data_shop['photo'] .= $image .',';
                    }   
                    else
                    {
                        $data_shop['photo'] .= KiotProduct::upload_image($image ).',';
                    }
                }
            }    
            $slug = Str::slug($data_shop['title']);
            $slug_count = Product::where('slug',$slug)->count();
            if($slug_count > 0)
            {
                $slug .= time().'-'.$slug;
            }
            $data_shop['slug'] = $slug;
            $pro_shop = \App\Models\Product::create($data_shop);
            $data['product_id'] = $pro_shop->id;
            $data['kiot_product_id'] = $kiot_item['id'];
            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            $data['modifiedDate'] = $dateTime->format('Y-m-d H:i:s');
            \App\Models\KiotProduct::create($data);
            $kq += 1;
        }
        else
        {
            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $dateTime = new \DateTime($modifiedDate);
            // echo '<br/><br/><br/>$kiot_pro->modifiedDate'. $kiot_pro->modifiedDate;
            // echo '<br/>'.$dateTime->format('Y-m-d H:i:s');
            if( array_key_exists("modifiedDate",$kiot_item) && $kiot_pro->modifiedDate != $dateTime->format('Y-m-d H:i:s'))
            {
                // echo 'co cn';
                $shop_pro = \App\Models\Product::find($kiot_pro->product_id);
                $kiot_cat = \App\Models\KiotCat::where('kiotId',$kiot_item['categoryId'])->first();
          
                $data_shop['code'] = $kiot_item['code'];
                $data_shop['title'] = $kiot_item['name'];
                $data_shop['categoryId'] = $kiot_cat->categoryId;
                if($kiot_item['type']==2)
                {
                    $data_shop['type']= 'normal';
                }
                if($kiot_item['type']==3)
                {
                    $data_shop['type']= 'service';
                }
                $data_shop['summary'] = '';
               
                if( array_key_exists("description",$kiot_item))
                    $data_shop['description'] = $kiot_item['description'];
                $data_shop['stock'] = 0;
                $data_shop['price_avg'] = $kiot_item['basePrice'];
                if($kiot_item['isActive'] == false)
                    $data_shop['status'] = 'inactive';
               
                 
                if( array_key_exists("images",$kiot_item))
                {
                    $images = $kiot_item['images'];
                    foreach($images as $image)
                    {
                        if($save_image == 0)
                        {
                            $data_shop['photo'] .= $image .',';
                        }   
                        else
                        {
                            $data_shop['photo'] .= KiotProduct::upload_image($image ).',';
                        }
                    }
                }    
                $shop_pro->fill($data_shop)->save();

                $kiot_pro->modifiedDate = $dateTime->format('Y-m-d H:i:s');
                $kiot_pro->save();
                $kq += 1;
            }
        }
        return $kq;
    }
    public static function upload_image($url)
    {
        $gg_name = '';
        $storage_disk = 'gcs';
        $contents = file_get_contents($url);
        $file_name = 'kiot/'.basename($url);
       
        $gg_name =  Storage::disk('gcs')->put($file_name, $contents);
        // 
        // echo '<br/>'.Storage::disk('gcs')->url($file_name) ;
        return Storage::disk('gcs')->url($file_name)  ;
      
    }
    public static function update_product_images($images)
    {
        // echo basename($image_url);
    }
 
}
