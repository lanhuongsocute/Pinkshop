<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPrice extends Model
{
    use HasFactory;
    protected $fillable = ['ugroup_id','price','product_id' ];
    public static function updateProductPrice($ugroup_id,$pro_id,$price)
    {
        $groupprice = GroupPrice::where('ugroup_id',$ugroup)->where('product_id',$pro_id)->first();
        if($groupprice)
        {
            $groupprice->price = $price;
            $groupprice->save();
        }
    }
    public static function updateProductPriceId($gp_id,$price)
    {
        $groupprice = GroupPrice::where('id',$gp_id)->first();
        if($groupprice)
        {
            $groupprice->price = $price;
            $groupprice->save();
        }
    }
}
