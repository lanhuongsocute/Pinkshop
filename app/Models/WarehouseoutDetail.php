<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseoutDetail extends Model
{
    use HasFactory;
    protected $fillable = ['wo_id','wh_id', 'wto_id','product_id', 'quantity','price','benefit','expired_at','in_ids','prebalance','doc_type','qty_returned'];
    public static function c_create($product_detail)
    {
       
        $in_ids = json_decode($product_detail['in_ids']);
        $tong = 0;
        if ( $in_ids != null)
        {
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                $tong +=($product_detail['price'] -$detail_in->price)  * $in_id->qty;
                $detail_in->benefit += ($product_detail['price'] - $detail_in->price)*$in_id->qty;
                $detail_in->save();
            } 
            $product_detail['benefit'] = $tong;
        }
        else
        {
            $product_detail['benefit'] = $product_detail['price']  * $product_detail['quantity'];
        }
        WarehouseoutDetail::create($product_detail);
    }
    public static function deleteWO($wo_details ,$doc_type) //dung cho xoa warehouseoutdetail trong w to p, w to d ...
    {
        foreach($wo_details as $wo_detail)
        {
            $inv = \App\Models\Inventory::where('product_id',$wo_detail->product_id)
                ->where('wh_id',$wo_detail->wh_id)
                ->first();
            if($inv)
                $data['prebalance'] =$inv->quantity;
            else
                $data['prebalance'] = 0;
            $data['doc_id']= 0;
            $data['doc_type'] =  $doc_type;
            $data['wh_id'] =   $wo_detail->wh_id;
            $data['product_id'] = $wo_detail->product_id;
            $data['quantity'] = $wo_detail->quantity;
            $data['qty_sold'] = 0;
            $data['price'] =$wo_detail->price;
            \App\Models\WarehouseInDetail::create($data);
            $wo_detail->wo_id = 0;
            $wo_detail->save();
        }
    }
    public static function deleteDetailPro($detailpro,$extraprice,$wh_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        if($product->type=='normal')
        {
            if($product->sold ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->sold * $product->price_out - ($detailpro->price -$extraprice )*$detailpro->quantity;
                $avg = $avg/($product->sold - $detailpro->quantity);
            }
            $product->sold -= $detailpro->quantity;
            $product->stock += $detailpro->quantity;
            $product->price_out = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();

            $prebalance = $inventory->quantity;

            $inventory->quantity += $detailpro->quantity;
            $product->save();
            $inventory->save();
            //return product to warehouseindetail
            $in_ids = json_decode($detailpro->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
        ///
        // $data['wo_id']=  $dout_id;
        // $data['wto_id'] =  $detailpro->wto_id;
        // $data['product_id'] = $detailpro->product_id;
        // $data['quantity'] = $detailpro->quantity;
        // $data['price'] =$detailpro->price;
        // $data['expired_at'] = $detailpro->expired_at;
        // $data['in_ids'] =  $detailpro->in_ids;
        // $dout = \App\Models\DOutdetail::create($data);
       
        //tao warehouse in detail tương ứng ko có phiếu quản lý để theo dõi balance
        if(isset($inventory))
            $datai['prebalance'] = $prebalance;
        else
            $datai['prebalance'] = 0;
        $datai['doc_id']= 0;
        $datai['doc_type'] =  'wi';
        $datai['wh_id'] =  $wh_id;
        $datai['product_id'] = $detailpro->product_id;
        $datai['quantity'] = $detailpro->quantity;
        $datai['qty_sold'] = 0;
        $datai['price'] =$detailpro->price;
        \App\Models\WarehouseInDetail::create($datai);
        //cap nhat warehouse out wo_id về không để ko dc phiếu nào quản lý
        // $detailpro->delete();
        $detailpro->wo_id = 0;
        $detailpro->save();
    }

    public static function deleteDetailProVersion($detailpro,$extraprice,$wh_id,$dout_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        if($product->type=='normal')
        {
            if($product->sold ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->sold * $product->price_out - ($detailpro->price -$extraprice )*$detailpro->quantity;
                $avg = $avg/($product->sold - $detailpro->quantity);
            }
            $product->sold -= $detailpro->quantity;
            $product->stock += $detailpro->quantity;
            $product->price_out = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();

            $prebalance = $inventory->quantity;

            $inventory->quantity += $detailpro->quantity;
            $product->save();
            $inventory->save();
            //return product to warehouseindetail
            $in_ids = json_decode($detailpro->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
        ///
       
        $data['wo_id']=  $dout_id;
        $data['wto_id'] =  $detailpro->wto_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] = $detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['expired_at'] = $detailpro->expired_at;
        $data['in_ids'] =  $detailpro->in_ids;
        $dout = \App\Models\DOutdetail::create($data);
        
        //tao warehouse in detail tương ứng ko có phiếu quản lý để theo dõi balance
        if(isset( $inventory))
            $datai['prebalance'] = $prebalance;
        else
            $datai['prebalance'] = 0;
        $datai['doc_id']= 0;
        $datai['doc_type'] =  'wi';
        $datai['wh_id'] =  $wh_id;
        $datai['product_id'] = $detailpro->product_id;
        $datai['quantity'] = $detailpro->quantity;
        $datai['qty_sold'] = 0;
        $datai['price'] =$detailpro->price;
        \App\Models\WarehouseInDetail::create($datai);
        //cap nhat warehouse out wo_id về không để ko dc phiếu nào quản lý
        // $detailpro->delete();
        $detailpro->wo_id = 0;
        $detailpro->save();
    }

    public static function returnDetailPro($detailpro,$extraprice,$wh_id,$dout_id )
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
       
        if($product->type=='normal')
        {
       
            if($product->sold ==$detailpro->quantity )
            {
                $avg = 0;
            }
            else
            {
                $avg =  $product->sold * $product->price_out - ($detailpro->price -$extraprice )*$detailpro->quantity;
                $avg = $avg/($product->sold - $detailpro->quantity);
            }
            $product->sold -= $detailpro->quantity;
            $product->stock += $detailpro->quantity;
            $product->price_out = $avg;
            
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();

            $prebalance = $inventory->quantity;

            $inventory->quantity += $detailpro->quantity;
            $product->save();
            $inventory->save();
            //return product to warehouseindetail
            $in_ids = json_decode($detailpro->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
        ///
        $data['wo_id']=  $dout_id;
        $data['wto_id'] =  $detailpro->wto_id;
        $data['product_id'] = $detailpro->product_id;
        $data['quantity'] = $detailpro->quantity;
        $data['price'] =$detailpro->price;
        $data['expired_at'] = $detailpro->expired_at;
        $data['in_ids'] =  $detailpro->in_ids;
        $dout = \App\Models\DOutdetail::create($data);
        //tao warehouse in detail tương ứng ko có phiếu quản lý để theo dõi balance
        if( isset($inventory))
            $datai['prebalance'] = $prebalance;
        else
            $datai['prebalance'] = 0;
        $datai['doc_id']= 0;
        $datai['doc_type'] =  'wi';
        $datai['wh_id'] =  $wh_id;
        $datai['product_id'] = $detailpro->product_id;
        $datai['quantity'] = $detailpro->quantity;
        $datai['qty_sold'] = 0;
        $datai['price'] =$detailpro->price;
        \App\Models\WarehouseInDetail::create($datai);
        //cap nhat warehouse out wo_id về không để ko dc phiếu nào quản lý
        // $detailpro->delete();
        $detailpro->wo_id = 0;
        $detailpro->save();
    }
}
