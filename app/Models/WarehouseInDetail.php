<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseInDetail extends Model
{
    use HasFactory;

    protected $fillable = ['doc_id','doc_type','wh_id', 'product_id', 'quantity','prebalance','price','qty_sold','expired_at','is_seri','benefit'];
   
   //doctype: wi : nhập từ phiếu nhập, co: nhập từ pheieus combo, wp: nhập từ property, bi: ic, mi,pi; wr: return hàng hóa, id sẽ là của warehoutout
   
    public static function deleteDetailPro($detailpro,$extraprice,$wh_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
      
        if($product->type=='normal')
        {
            if($product->stock ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->stock * $product->price_avg - ($extraprice + $detailpro->price)*$detailpro->quantity;
                $avg = $avg/($product->stock - $detailpro->quantity);
            }
            $product->stock -= $detailpro->quantity;
            $product->price_avg = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();

            $prebalance = $inventory->quantity;

            $inventory->quantity -= $detailpro->quantity;
            $product->save();
            $inventory->save();
        }
        // $detailpro->delete();
        if(isset($prebalance))
            $data['prebalance'] = $prebalance;
        else
            $data['prebalance'] = 0;
        $data['wo_id']= 0;
        $data['doc_type'] = 'wo';
        $data['wh_id'] =  $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] =$detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['in_ids']= $detailpro->id;
        \App\Models\WarehouseoutDetail::create($data);
        //cap nhat widetail ve 0 giong nhu xoa
        $detailpro->doc_id = 0;
        $detailpro->save();
    }

    public static function deleteDetailProVersion($detailpro,$extraprice,$wh_id,$in_id)
    {

        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        if($product->type=='normal')
        {
            if($product->stock ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->stock * $product->price_avg - ($extraprice + $detailpro->price)*$detailpro->quantity;
                $avg = $avg/($product->stock - $detailpro->quantity);
            }
            $product->stock -= $detailpro->quantity;
            $product->price_avg = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();
                
            $prebalance = $inventory->quantity;

            $inventory->quantity -= $detailpro->quantity;
            $product->save();
            $inventory->save();
        }
        $data['doc_id']= $in_id;
        $data['doc_type'] =  $detailpro->doc_type;
        $data['wh_id'] = $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] = $detailpro->quantity;
        $data['qty_sold'] = $detailpro->qty_sold;
        $data['price'] =$detailpro->price;
        $data['expired_at'] = $detailpro->expired_at;
        
        $dout = \App\Models\DIndetail::create($data);
        // $detailpro->delete();
        if(isset($prebalance))
             $data['prebalance'] = $prebalance;
        else
            $data['prebalance'] = 0;
        $data['wo_id']= 0;
        $data['doc_type'] = 'wo';
        $data['wh_id'] =  $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] =$detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['in_ids']= $detailpro->id;
        \App\Models\WarehouseoutDetail::create($data);
        //cap nhat widetail ve 0 giong nhu xoa
        $detailpro->doc_id = 0;
        $detailpro->save();
    }
   
    public static function returnDetailPro($detailpro,$extraprice,$wh_id,$in_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        if($product->type=='normal')
        {
            if($product->stock ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->stock * $product->price_avg - ($extraprice + $detailpro->price)*$detailpro->quantity;
                $avg = $avg/($product->stock - $detailpro->quantity);
            }
            $product->stock -= $detailpro->quantity;
            $product->price_avg = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();

            $prebalance = $inventory->quantity;

            $inventory->quantity -= $detailpro->quantity;
            $product->save();
            $inventory->save();
        }
        $data['doc_id']= $in_id;
        $data['doc_type'] =  $detailpro->doc_type;
        $data['wh_id'] = $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] = $detailpro->quantity;
        $data['qty_sold'] = $detailpro->qty_sold;
        $data['price'] =$detailpro->price;
        $data['expired_at'] = $detailpro->expired_at;
        $dout = \App\Models\DIndetail::create($data);
        
        // $detailpro->delete();
        if(isset($prebalance))
            $data['prebalance'] = $prebalance;
        else
            $data['prebalance'] = 0;
        $data['wo_id']= 0;
        $data['doc_type'] = 'wo';
        $data['wh_id'] =  $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] =$detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['in_ids']= $detailpro->id;
        \App\Models\WarehouseoutDetail::create($data);
        //cap nhat widetail ve 0 giong nhu xoa
        $detailpro->doc_id = 0;
        $detailpro->save();
    }
    public static function deleteDetailTransfer($detailpro,$extraprice,$wh_id1,$wh_id2)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        
        if($product->type=='normal')
        {
            if($product->stock ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->stock * $product->price_avg - ($extraprice )*$detailpro->quantity;
                $avg = $avg/($product->stock );
            }
            
            $product->price_avg = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id1)->first();
            $prebalance = $inventory->quantity;
            $inventory->quantity += $detailpro->quantity;
            $inventory2 = \App\Models\Inventory::where('product_id',$detailpro->product_id)
            ->where('wh_id',$wh_id2)->first();
            $inventory2->quantity -= $detailpro->quantity;
       

            $product->save();
            $inventory->save();
            $inventory2->save();
       
            $query= "select * from warehouse_in_details where doc_id != 0 and product_id = ".$detailpro->product_id." and wh_id = ".$wh_id1." and expired_at = '".$detailpro->expired_at."' order by warehouse_in_details.id asc";
            $details = \DB::select($query);
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->qty_sold - $detailpro->quantity >= 0)
                {
                    $detail->qty_sold -= $detailpro->quantity;
                    $detailpro->quantity = 0;
                }
                else
                {
                    
                    $detail->quantity -= $detail->qty_sold;
                    $detail->qty_sold = 0;
                }
                $detail->save();
            
            
                if($detailpro->quantity == 0)
                    break;
            }
        }
        // $detailpro->delete();
        if(isset($prebalance))
            $data['prebalance'] = $prebalance;
        else
            $data['prebalance'] = 0;
        $data['wo_id']= 0;
        $data['doc_type'] = 'wo';
        $data['wh_id'] =  $detailpro->wh_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] =$detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['in_ids']= $detailpro->id;
        \App\Models\WarehouseoutDetail::create($data);
        //cap nhat widetail ve 0 giong nhu xoa
        $detailpro->doc_id = 0;
        $detailpro->save();
    }

    public static function deleteWI($wi_details, $doc_type) //dung cho xoa warehouseoutdetail trong w to p, w to d ...
    {
        foreach($wi_details as $wi_detail)
        {
            //tao wareout tuong ung de bu vao cho xoa detail in
            $inv = \App\Models\Inventory::where('product_id',$wi_detail->product_id)
                ->where('wh_id',$wi_detail->wh_id)
                ->first();
            if($inv)
                $data['prebalance'] =$inv->quantity;
            else
                $data['prebalance'] = 0;
            $data['wo_id']= 0;
            $data['doc_type'] =  $doc_type;
            $data['wh_id'] =  $wi_detail->wh_id;
            $data['product_id'] = $wi_detail->product_id;
            $data['quantity'] =$wi_detail->quantity;
            $data['price'] =$wi_detail->price;
            $data['in_ids']= $wi_detail->id;
            \App\Models\WarehouseoutDetail::create($data);
            //cap nhat widetail ve 0 giong nhu xoa
            $wi_detail->doc_id = 0;
            $wi_detail->save();
        }
    }
}
