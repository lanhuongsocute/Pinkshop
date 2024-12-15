<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory;
    protected $fillable = ['code','barcode','title','slug','summary','description','stock','sold','price_in','price_avg','price_out','price','hit','brand_id','cat_id','parent_cat_id','photo','size','weight','expired','is_sold','type','feature','status'];

    public static function deleteProduct($pro_id){
        $product = Product::find($pro_id);
        if($product != null && $product->stock > 0)
            return 0;
        else
        {
           //kiem tra cac rang buoc khac phieu nhap kho xuat kho 
           $product->delete();
           return 1;
        }
    }
    public function update_kiot()
    {
        if(env('KIOT_SYNC') == 1)
        {
            $kiotController = new \App\Http\Controllers\KiotController();
            $kiotController->kiotUpdateProduct($this);
        }
    }
    public static function c_create($data)
    {
        $slug = Str::slug($data['title']);
        $slug_count = Product::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        
        $pro = Product::create($data);
        $pro->code = "PRO" . sprintf('%09d',$pro->id);
        $pro->save();
       
        if(env('KIOT_SYNC') == 1)
        {
            $kiotController = new \App\Http\Controllers\KiotController();
            $kiotController->kiotAddProduct($pro);
        }
       
        return $pro;
    }

}
 