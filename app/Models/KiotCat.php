<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiotCat extends Model
{
    use HasFactory;
    protected $fillable = ['categoryId', 'kiotId','parentId','parentKiotId','modifiedDate'];
    
    public static function create_kiot_cat($kiot_item, $parentId,$parentKiotId)
    {
        $kiot_cat = KiotCat::where('kiotId', $kiot_item['categoryId'])->first();
        if(!$kiot_cat)
        {
            $data_shop['title'] = $kiot_item['categoryName'];
            $data_shop['summary'] = '';
            $data_shop['photo'] = '';
            $data_shop['is_parent'] = 0;
            $data_shop['parent_id'] = $parentId;
            if($kiot_item['hasChild'] == true)
            {
                $data_shop['is_parent'] = 1;
            }
            $cat_shop = \App\Models\Category::c_create($data_shop);
            $data['categoryId'] = $cat_shop->id;
            $data['parentKiotId'] = $parentKiotId;
            $data['parentId'] = $parentId;
            $data['kiotId'] = $kiot_item['categoryId'];
            // echo '<br/><br/><br/><br/>'. $kiot_item['categoryId'];
            if( array_key_exists("modifiedDate",$kiot_item))
                $modifiedDate = $kiot_item['modifiedDate'];
            else
                $modifiedDate = $kiot_item['createdDate'];
            $data['modifiedDate'] = $modifiedDate;
            $data['parentId'] = 0;
            $data['parentKiotId'] = 0;
            \App\Models\KiotCat::create($data);
            if($kiot_item['hasChild'] == true)
            {
                $childs = $kiot_item['children'];
                foreach($childs as $child)
                {
                    // dd($child);
                    KiotCat::create_kiot_cat($child,$cat_shop->id,$kiot_item['categoryId']);
                }
            }
        }
        else
        {
            $shop_cat = \App\Models\Category::find($kiot_cat->categoryId);
            if( $shop_cat &&array_key_exists("modifiedDate",$kiot_item) && $kiot_cat->modifiedDate != $kiot_item['modifiedDate'])
            {
                if($kiot_cat->parentId == 0 && $parentId != 0)
                {
                    $kiot_cat->parentId = $parentId;
                    $kiot_cat->parentKiotId = $parentKiotId;
                    $kiot_cat->modifiedDate = $kiot_item['modifiedDate'];
                    $kiot_cat->save();
                    $shop_cat->parent_id = $parentId;
                }
                if($kiot_item['hasChild'] == true)
                {
                    $shop_cat->is_parent  = 1;
                }
                else
                {
                    $shop_cat->is_parent  = 0;
                }   
                 
                $shop_cat->title = $kiot_item['categoryName'];
                $shop_cat->save();
            }
            if($kiot_cat->parentId == 0 && $parentId != 0)
            {
                $kiot_cat->parentId = $parentId;
                $kiot_cat->parentKiotId = $parentKiotId;
                $kiot_cat->save();
                $shop_cat->parent_id = $parentId;
                if($kiot_item['hasChild'] == true)
                {
                    $shop_cat->is_parent  = 1;
                }
                else
                {
                    $shop_cat->is_parent  = 0;
                } 
                $shop_cat->save();  
            }
        }
    }
    
     
}
 