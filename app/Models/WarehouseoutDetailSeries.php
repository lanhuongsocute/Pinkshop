<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseoutDetailSeries extends Model
{
    use HasFactory;
    protected $fillable = ['wo_id','product_id','seri' ,'in_id','doc_type'];
    public static function c_create($seri,$product_id,$wo_id,$wh_id)
    {
        $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                    ->where('product_id',$product_id)->where('is_sold',0)->where('wh_id',$wh_id)->first();
        $wi_seri->is_sold = 1;
        $wi_seri->save();
        $data_seri['wo_id'] =$wo_id;
        $data_seri['seri'] = $seri;
        $data_seri['product_id'] = $product_id;
        $wo_seri = \App\Models\WarehouseoutDetailSeries::create($data_seri);
        return $wo_seri;
    }
    public static function create_from_in_seri($wi_seri,$doc_id,$doc_type)
    {
        $data_seri['wo_id'] = $doc_id;
        $data_seri['seri'] = $wi_seri->seri;
        $data_seri['product_id'] =$wi_seri->product_id;;
        $data_seri['doc_type'] = $doc_type;
        $data_seri['wh_id'] = $wi_seri->wh_id;
        $data_seri['in_id'] = $wi_seri->id;
        $seri_out = \App\Models\WarehouseoutDetailSeries::create($data_seri);
        $wi_seri->is_sold = 1;
        $wi_seri->save();
        return $seri_out;
    }
    public static function check_valid_series_update($details, $wo_id,$wh_id )
    {
        foreach ($details as $detail)
        {
                ////delete old series
            ////add series for each product
            $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$wo_id)->get();
            foreach($wo_series as $wo_seri)
            {
                    $query = 'update warehousein_detail_series set is_sold = 0 where seri = "'.$wo_seri->seri.'" and product_id = '.$wo_seri->product_id;
                    \DB::select($query);
            }
            
            
            $pro_inventory = Inventory::where('product_id',$detail['id'])->where('wh_id', $wh_id)->first();
            if(!$pro_inventory || $pro_inventory->quantity < $detail['quantity'] )
            {
                return null;
            }
              ////update series for each product
              $series =  explode(",",  $detail['seri']);
              $count_n = count($series);
              $counts_n = \DB::select("select count(id) as tong from warehousein_detail_series where product_id = ".$detail['id'].' and is_sold = 0');

              $counts_n = $counts_n[0]->tong;
              if($count_n > $counts_n )
              {
                    return null;
              }
              foreach ($series as $seri)
              {
                    $seri = trim($seri);
                    $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) == 0)
                    {
                        foreach($wo_series as $wo_seri)
                        {
                                $query = 'update warehousein_detail_series set is_sold = 1 where seri = "'.$wo_seri->seri.'" and product_id = '.$wo_seri->product_id;
                                \DB::select($query);
                        }
                        return null;
                    }
                        
              } 
              //so hang khong co seri ton kho
              $n_noseri = $pro_inventory->quantity - $counts_n ;
              //so hang khong co seri xuat kho
              $sold_noseri =$detail['quantity'] - $count_n;
              if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
              {
                    return null;
              }

        }
       
    }
}
