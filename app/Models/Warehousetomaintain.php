<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehousetomaintain extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'wh_id','quantity','price','total','vendor_id','in_ids'];
    public static function c_create($data)
    {
        $mw = Warehousetomaintain::create($data);
        $mw->code = "WTM" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
    public static function deleteDetail ($detail)
    {
        $product = \App\Models\Product::where('id',$detail->product_id)->first();
        if($product->sold ==$detail->quantity )
        {
            $avg = 0;
        }
        else
        {
            $avg =  $product->sold * $product->price_out - ($detail->price   )*$detail->quantity;
            $avg = $avg/($product->sold - $detail->quantity);
        }
        $product->sold -= $detail->quantity;
        $product->stock += $detail->quantity;
        $product->price_out = $avg;
        
        $inventory = \App\Models\Inventory::where('product_id',$detail->product_id)
            ->where('wh_id',$detail->wh_id)->first();
        $inventory->quantity += $detail->quantity;
        $product->save();
        $inventory->save();
        //return product to warehouseindetail
        $in_ids = json_decode($detail->in_ids);
        foreach ($in_ids as $in_id)
        {
            $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
            $detail_in->qty_sold -= $in_id->qty;
            $detail_in->save();
        } 
        ///
        // $detail->delete();
        
    }
}
