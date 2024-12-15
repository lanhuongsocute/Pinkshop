<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProperties extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity' ];
    public static function addPropertytoWarehouse($pro_id, $wh_id,$qty,$price )
    {
        $pinventory = \App\Models\InventoryProperties::where("product_id",$pro_id)->first();
        if($pinventory && $pinventory->quantity >= $qty)
        {
            $pinventory->quantity -= $qty;
            $pinventory->save();
            $product = Product::where("id",$pro_id)->first();
            if($product)
            {
                $product->stock += $qty;
                $product->save();
                $inventory = \App\Models\Inventory::where("product_id",$pro_id)->where("wh_id",$wh_id)->first();
                if($inventory)
                {
                    $inventory->quantity += $qty;
                    $inventory->save();
                }
                else
                {
                    $data_inv['product_id'] = $pro_id;
                    $data_inv['wh_id'] = $wh_id;
                    $data_inv['quantity']= $qty;
                    \App\Models\Inventory::create($data_inv);
                }
            }
        }
        //create warehousein detail
    }
    public static function deletePropertytoWarehouse($pro_id, $wh_id,$qty,$price )
    {
        $pinventory = \App\Models\InventoryProperties::where("product_id",$pro_id)->first();
        if($pinventory)
        {
            $product = Product::where("id",$pro_id)->first();
            if($product)
            {
                $product->stock -= $qty;
                $product->save();
                $pinventory->quantity += $qty;
                $pinventory->save();
                $inventory = \App\Models\Inventory::where("product_id",$pro_id)->where("wh_id",$wh_id)->first();
                if($inventory)
                {
                    $inventory->quantity -= $qty;
                    $inventory->save();
                }
            }
        }
        //update or delete warehousein detail
         
    }
    public static function addPropertyToDestroy($product_id,$quantity)
    {
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($pinventory)
        {
            $pinventory->quantity -= $quantity;
            $pinventory->save();
        }
        $dinventory = \App\Models\InventoryDestroy::where('product_id',$product_id)
        ->first();
        if ($dinventory)
        {
            $dinventory->quantity += $quantity;
            $dinventory->save();
        }
        else
        {
            $des_data['product_id'] = $product_id;
            $des_data['quantity'] = $quantity;
            \App\Models\InventoryDestroy::create($des_data);
        }
    }
    public static function deletePropertyToDestroy($product_id,$quantity)
    {
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($pinventory)
        {
            $pinventory->quantity += $quantity;
            $pinventory->save();
        }
        $dinventory = \App\Models\InventoryDestroy::where('product_id',$product_id)
        ->first();
        if ($dinventory)
        {
            $dinventory->quantity -= $quantity;
            $dinventory->save();
        }
        
    }
    public static function addPropertyToMaintain($product_id,$quantity)
    {
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($pinventory)
        {
            $pinventory->quantity -= $quantity;
            $pinventory->save();
        }
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $quantity;
            $minventory->save();
        }
        else
        {
            $des_data['product_id'] = $product_id;
            $des_data['quantity'] = $quantity;
            \App\Models\InventoryMaintenance::create($des_data);
        }
    }
    public static function deletePropertyToMaintain($product_id,$quantity)
    {
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($pinventory)
        {
            $pinventory->quantity += $quantity;
            $pinventory->save();
        }
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $quantity;
            $minventory->save();
        }
        
    }
}
