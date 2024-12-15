<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseinDetailSeries extends Model
{
    use HasFactory;protected $fillable = ['wi_id','wh_id','product_id','seri', 'is_sold' ,'doc_type'  ];
    //doctype: wi; wo; ci ; ti;wo;co
    public static function create_from_in_seri($wi_seri,$doc_id,$doc_type,$wh_id)
    {
        $data_seri['wi_id'] = $doc_id;
        $data_seri['seri'] = $wi_seri->seri;
        $data_seri['product_id'] =$wi_seri->product_id;;
        $data_seri['is_sold'] = 0;
        $data_seri['doc_type'] = $doc_type;
        $data_seri['wh_id'] = $wh_id;
        $seri_in = \App\Models\WarehouseinDetailSeries::create($data_seri);
        return $seri_in;
    }
    public static function check_seri_in_avaible($seri, $product_id,$wh_id)
    {
        $seri = trim ($seri);
        $query = "select * from warehousein_detail_series where product_id =". $product_id
            ." and seri = '".$seri."' and wh_id = ".$wh_id." and is_sold = 0";
        $old_series = \DB::select($query);
        if(count($old_series) > 0)
            return 1;
        else
            return 0;
    }
    public static function c_create($wi_id, $seri,$product_id,$doc_type,$wh_id)
    {
        $data_seri['wi_id'] = $wi_id;
        $data_seri['wh_id'] = $wh_id;
        $data_seri['seri'] = $seri;
        $data_seri['doc_type'] = $doc_type;
        $data_seri['product_id'] = $product_id;
        $data_seri['is_sold'] = 0;
        \App\Models\WarehouseinDetailSeries::create($data_seri);
    }
}
