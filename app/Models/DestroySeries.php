<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestroySeries extends Model
{
    use HasFactory;
    use HasFactory;protected $fillable = ['wd_id','doc_type','product_id','seri', 'is_sold' ,'in_id' ,'out_id' ];
    
    public static function check_seri_in_avaible($seri, $product_id)
    {
        $seri = trim ($seri);
        $query = "select * from destroy_series where product_id =". $product_id
            ." and seri = '".$seri."' and is_sold = 0";
        $old_series = \DB::select($query);
        if(count($old_series) > 0)
            return 1;
        else
            return 0;
    }
    public static function c_create($wp_id, $seri,$product_id,$doc_type="wd")
    {
        $data_seri['wd_id'] = $wp_id;
        $data_seri['seri'] = $seri;
        $doc_type['seri'] = $doc_type;
        $data_seri['product_id'] = $product_id;
        $data_seri['is_sold'] = 0;
        \App\Models\PropertySeries::create($data_seri);
    }
}
