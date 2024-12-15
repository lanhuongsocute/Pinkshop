<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheck extends Model
{
    use HasFactory;
    protected $fillable=['code','wh_id','total','vendor_id'];
    public static function c_create($data)
    {
        $mw = InventoryCheck::create($data);
        $mw->code = "INC" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
    public static function deleteDetailIn($detailpro,$wh_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        $product->stock -= $detailpro->quantity;
        $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
            ->where('wh_id',$wh_id)->first();
        $inventory->quantity -= $detailpro->quantity;
        $product->save();
        $inventory->save();
        $detailpro->delete();
    }
    public static function deleteDetailOut($detailpro, $wh_id)
    {
        $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        if($product->type=='normal')
        {
            
           
            $product->stock += $detailpro->error;
            $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
                ->where('wh_id',$wh_id)->first();
            $inventory->quantity += $detailpro->error;
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
    
    }
}
