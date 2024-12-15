<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\IDs;
class Inventory extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','wh_id','quantity' ];
    public static function addProduct($pro_id, $wh_id,$qty,$price,$cost_extra){
        $inventory = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id)->first();
        if($inventory)
        {
            $inventory->quantity += $qty;
            $inventory->save();
        }
        else
        {
            $data['product_id'] = $pro_id;
            $data['wh_id'] = $wh_id;
            $data['quantity'] = $qty;
            Inventory::create($data);
        }
        $product = Product::where("id",$pro_id)->first();
        if($product)
        {
           
            $product->price_in = $price;
            $avg = (int) (($product->price_avg * $product->stock + ( $product->price_in + $cost_extra)*$qty)
                            /($product->stock + $qty));
            $product->stock += $qty;
            $product->price_avg = $avg;
            $product->save();
        }
    }
    
    public static function subProduct($pro_id, $wh_id,$qty,$price,$cost_extra)
    {
        $product = \App\Models\Product::find($pro_id);
        if($product->type=='normal')
        {
            $inventory = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id)->first();
            if($inventory)
            {
                $inventory->quantity -= $qty;
                $inventory->save();
            }
           
            $product = Product::where("id",$pro_id)->first();
            if($product)
            {
                $avg = (int) (($product->price_out * $product->sold + ( $price - $cost_extra)*$qty)
                                /($product->sold + $qty));
                $product->stock -= $qty;
                $product->sold += $qty;
                $product->price_out = $avg;
                $product->hit += 10;
                $product->save();
            }
             $query= "select * from warehouse_in_details where product_id = ".$pro_id." and wh_id = ".$wh_id." and qty_sold < quantity and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
          
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $qty)
                {
                    $detail->qty_sold += $qty;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $qty;
                    array_push($in_ids, $in_id);
                    $qty = 0;
                }
                else
                {
                   
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $qty= $qty- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
               
               
                if($qty == 0)
                    break;
            }
            return $in_ids;
        }
        else
            return '';
        
         
    }
    public static function updateWarehouseLastIn($pro_id, $wh_id,$qty)
    {
        $product = \App\Models\Product::find($pro_id);
        if($product->type=='normal')
        {
            $query= "select * from warehouse_in_details where product_id = ".$pro_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $qty)
                {
                    $detail->qty_sold += $qty;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $qty;
                    array_push($in_ids, $in_id);
                    $qty = 0;
                }
                else
                {
                   
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $qty= $qty- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
               
               
                if($qty == 0)
                    break;
            }
            return $in_ids;

        }
        else
            return '';

    }
    public static function updateWarehouseLastIn_inventory_check($pro_id, $wh_id,$qty)
    {
        $product = \App\Models\Product::find($pro_id);
        if($product->type=='normal')
        {
            $query= "select * from warehouse_in_details where product_id = ".$pro_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $qty)
                {
                    $detail->qty_sold += $qty;
                    $in_id->id = $detail->id;
                    $in_id->qty  = -$qty;
                    array_push($in_ids, $in_id);
                    $qty = 0;
                }
                else
                {
                    $in_id->id = $detail->id;
                    $in_id->qty  = - ($detail->quantity - $detail->qty_sold);
                    array_push($in_ids, $in_id);
                    $qty= $qty- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
               
               
                if($qty == 0)
                    break;
            }
            return $in_ids;

        }
        else
            return '';

    }
    public static function updateWarehouseInDetails($pro_id, $wh_id,$detail)
    {
        $qty = 1;
        $in_id = new IDs();
        
        if($detail->quantity >= $detail->qty_sold+ $qty)
        {
            $detail->qty_sold += $qty;
            $in_id->id = $detail->id;
            $in_id->qty  = $qty;
        }
        $detail->save();
        return $in_id;
    }

    public static function subProductInv($pro_id, $wh_id,$qty,$price,$cost_extra)
    {
        $product = \App\Models\Product::find($pro_id);
        if($product->type=='normal')
        {
            $inventory = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id)->first();
            if($inventory)
            {
                $inventory->quantity -= $qty;
                $inventory->save();
            }
           
            $product = Product::where("id",$pro_id)->first();
            if($product)
            {
                $avg = (int) (($product->price_out * $product->sold + ( $price - $cost_extra)*$qty)
                                /($product->sold + $qty));
                $product->stock -= $qty;
                $product->sold += $qty;
                $product->price_out = $avg;
                $product->hit += 10;
                $product->save();
            }
         
        }
        else
            return '';
    }

    public static function subOneProduct($pro_id, $wh_id,$qty,$price,$cost_extra,$detail)
    {
        $product = \App\Models\Product::find($pro_id);
        if($product->type=='normal')
        {
            $inventory = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id)->first();
            if($inventory)
            {
                $inventory->quantity -= $qty;
                $inventory->save();
            }
           
            $product = Product::where("id",$pro_id)->first();
            if($product)
            {
                $avg = (int) (($product->price_out * $product->sold + ( $price - $cost_extra)*$qty)
                                /($product->sold + $qty));
                $product->stock -= $qty;
                $product->sold += $qty;
                $product->price_out = $avg;
                $product->hit += 10;
                $product->save();
            }
              $query= "select * from warehouse_in_details where product_id = ".$pro_id." and wh_id = ".$wh_id." and qty_sold < quantity and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
            
            $in_ids=array();
            
           
                $dt = $detail;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $qty)
                {
                    $detail->qty_sold += $qty;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $qty;
                    array_push($in_ids, $in_id);
                    $qty = 0;
                }
                else
                {
                   
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $qty= $qty- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
               
               
                // if($qty == 0)
                //     break;
            // }
            return $in_id;
        }
        else
            return '';
        
         
    }

public static function mainTransfer($pro_id, $wh_id,$qty,$price )
    {
        $inventory = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id)->first();
        if($inventory)
        {
            $inventory->quantity -= $qty;
            $inventory->save();
        }
       
        $product = Product::where("id",$pro_id)->first();
        if($product)
        {
            $avg = (int) (($product->price_out * $product->sold + ( $price  )*$qty)
                            /($product->sold + $qty));
            $product->stock -= $qty;
            $product->sold += $qty;
            // $product->price_out = $avg;
            $product->save();
        }
         $query= "select * from warehouse_in_details where product_id = ".$pro_id." and wh_id = ".$wh_id." and qty_sold < quantity and doc_id != 0 order by warehouse_in_details.id asc";
        $details = DB::select($query);
        $in_ids=array();
        
        foreach ($details as $dt)
        {
            // return 'dt'.$dt->id;
            $in_id = new IDs();
            $detail = \App\Models\WarehouseInDetail::find($dt->id);
            if($detail->quantity >= $detail->qty_sold+ $qty)
            {
                $detail->qty_sold += $qty;
                $in_id->id = $detail->id;
                $in_id->qty  = $qty;
                array_push($in_ids, $in_id);
                $qty = 0;
            }
            else
            {
               
                $in_id->id = $detail->id;
                $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                
                array_push($in_ids, $in_id);
                $qty= $qty- ($detail->quantity - $detail->qty_sold);
                $detail->qty_sold = $detail->quantity;
            }
            $detail->save();
           
           
            if($qty == 0)
                break;
        }
        return $in_ids;
         
    }
    public static function transfer($wti_id,$pro_id, $wh_id1,$wh_id2,$qty,$price,$cost_extra)
    {
        $inventory1 = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id1)->first();
        if($inventory1)
        {
            $inventory1->quantity -= $qty;
            $inventory1->save();
        }
        $inventory2 = Inventory::where("product_id",$pro_id)->where('wh_id',$wh_id2)->first();
        if($inventory2)
        {
            $inventory2->quantity += $qty;
            $inventory2->save();
        }
        else
        {
            $data_i['product_id'] = $pro_id;
            $data_i['wh_id'] = $wh_id2;
            $data_i['quantity'] = $qty;
            $inventory2=Inventory::create($data_i);
        }
        $product = Product::where("id",$pro_id)->first();
        if($product)
        {
            $product->price_avg = (int) (($product->price_avg * $product->stock +   $cost_extra *$qty)
                            /($product->stock));
            $product->save();
        }
    }
    public static function transfer_noseri($product_id,$quantity,$wh_id)
    {
        $query= "select * from warehouse_in_details where product_id = ".$product_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
        $details = DB::select($query);
        $in_ids=array();
        foreach ($details as $dt)
        {
            // return 'dt'.$dt->id;
            $in_id = new IDs();
            $detail = \App\Models\WarehouseInDetail::find($dt->id);
            if($detail->quantity >= $detail->qty_sold+ $quantity)
            {
                $detail->qty_sold += $quantity;
                $in_id->id = $detail->id;
                $in_id->qty  = $quantity;
                array_push($in_ids, $in_id);
                $quantity = 0;
            }
            else
            {
                $in_id->id = $detail->id;
                $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                
                array_push($in_ids, $in_id);
                $quantity= $quantity- ($detail->quantity - $detail->qty_sold);
                $detail->qty_sold = $detail->quantity;
            }
            $detail->save();
            if($quantity == 0)
                break;
        }
        return $in_ids;
    }
    public static function transferDetailInsSeries($product_id,$wh_id,$detail)
    {
        $qty = 1;
        $in_id = new IDs();
        if($detail->quantity >= $detail->qty_sold+ $qty)
        {
            $detail->qty_sold += $qty;
            $in_id->id = $detail->id;
            $in_id->qty  = $qty;
        }
        $detail->save();
        return $in_id;
    }
    public static function addWarehouseToMaintain($product_id,$quantity,$wh_id)
    {
        $inventory = \App\Models\Inventory::where('product_id',$product_id)
        ->where('wh_id',$wh_id)->first();
        $pinventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($inventory && $inventory->quantity >= $quantity)
        {
            $inventory->quantity -= $quantity;
            $inventory->save();
            if ($pinventory)
            {
                $pinventory->quantity += $quantity;
                $pinventory->save();
            }
            else
            {
                $des_data['product_id'] = $product_id;
                $des_data['quantity'] = $quantity;
                \App\Models\InventoryMaintenance::create($des_data);
            }
        }
        else
        {
            return 0;
        }
    }
    public static function addWarehouseToMaintainDetailInsNoSeries($product_id,$quantity,$wh_id)
    {
            $query= "select * from warehouse_in_details where product_id = ".$product_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $quantity)
                {
                    $detail->qty_sold += $quantity;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $quantity;
                    array_push($in_ids, $in_id);
                    $quantity = 0;
                }
                else
                {
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $quantity= $quantity- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
                if($quantity == 0)
                    break;
            }
            return $in_ids;
        
    }
    public static function deleteWarehouseToMaintain($detail)
    {
        $inventory = \App\Models\Inventory::where('product_id',$detail->product_id)
        ->where('wh_id',$detail->wh_id)->first();
        $pinventory = \App\Models\InventoryMaintenance::where('product_id',$detail->product_id)
        ->first();
        if( $inventory && $pinventory && $pinventory->quantity >=$detail->quantity)
        {
            $pinventory->quantity -= $detail->quantity;
            $pinventory->save();
            $inventory->quantity += $detail->quantity;
            $inventory->save();
            $in_ids = json_decode($detail->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                if($detail_in )
                {
                    $detail_in->qty_sold -= $in_id->qty;
                    $detail_in->save(); 
                }
            }
            return 1;
        }
        else
            return 0;
        
    }
    public static function addWarehouseToProperty($product_id,$quantity,$wh_id)
    {
        $inventory = \App\Models\Inventory::where('product_id',$product_id)
        ->where('wh_id',$wh_id)->first();
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($inventory && $inventory->quantity >= $quantity)
        {
            $inventory->quantity -= $quantity;
            $inventory->save();
            if ($pinventory)
            {
                $pinventory->quantity += $quantity;
                $pinventory->save();
            }
            else
            {
                $des_data['product_id'] = $product_id;
                $des_data['quantity'] = $quantity;
                \App\Models\InventoryProperties::create($des_data);
            }
           
            
        }
        else
        {
            return 0;
        }
    }

    public static function addWarehouseToPropertyDetailInsNoSeries($product_id,$quantity,$wh_id)
    {
        
            $query= "select * from warehouse_in_details where product_id = ".$product_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
           
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $quantity)
                {
                    $detail->qty_sold += $quantity;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $quantity;
                    array_push($in_ids, $in_id);
                    $quantity = 0;
                }
                else
                {
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $quantity= $quantity- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
                if($quantity == 0)
                    break;
            }
            return $in_ids;
        
    }
    public static function addWarehouseToPropertyDetailInsSeries($product_id,$wh_id,$detail)
    {
        $qty = 1;
        $in_id = new IDs();
        if($detail->quantity >= $detail->qty_sold+ $qty)
        {
            $detail->qty_sold += $qty;
            $in_id->id = $detail->id;
            $in_id->qty  = $qty;
        }
        $detail->save();
        return $in_id;
    }


    public static function deleteWarehouseToProperty($detail)
    {
        $inventory = \App\Models\Inventory::where('product_id',$detail->product_id)
        ->where('wh_id',$detail->wh_id)->first();
        $pinventory = \App\Models\InventoryProperties::where('product_id',$detail->product_id)
        ->first();
        if( $inventory && $pinventory && $pinventory->quantity >=$detail->quantity)
        {
            $pinventory->quantity -= $detail->quantity;
            $pinventory->save();
            $inventory->quantity += $detail->quantity;
            $inventory->save();
            $in_ids = json_decode($detail->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                if($detail_in )
                {
                    $detail_in->qty_sold -= $in_id->qty;
                    $detail_in->save(); 
                }
            }
            return 1;
        }
        else
            return 0;
        
    }
    public static function addWarehouseToDestroy($product_id,$quantity,$wh_id)
    {
        $inventory = \App\Models\Inventory::where('product_id',$product_id)
        ->where('wh_id',$wh_id)->first();
        $dinventroy = \App\Models\InventoryDestroy::where('product_id',$product_id)
        ->first();
        if ($inventory && $inventory->quantity >= $quantity)
        {
            $inventory->quantity -= $quantity;
            $inventory->save();
            if ($dinventroy)
            {
                $dinventroy->quantity += $quantity;
                $dinventroy->save();
            }
            else
            {
                $des_data['product_id'] = $product_id;
                $des_data['quantity'] = $quantity;
                \App\Models\InventoryDestroy::create($des_data);
            }
           
        }
        else
        {
            return 0;
        }
        
       
    }
    public static function addWarehouseToDestroyDetailInsNoSeries($product_id,$quantity,$wh_id)
    {
        
            $query= "select * from warehouse_in_details where product_id = ".$product_id." and wh_id = ".$wh_id." and qty_sold < quantity and is_seri = 0 and doc_id != 0 order by warehouse_in_details.id asc";
            $details = DB::select($query);
            // $details = WarehouseInDetail::where('wh_id',$wh_id)->where('product_id',$pro_id)
            // ->where('qty_sold','<','quantity')->orderBy('id','ASC')->get();
            $in_ids=array();
            
            foreach ($details as $dt)
            {
                // return 'dt'.$dt->id;
                $in_id = new IDs();
                $detail = \App\Models\WarehouseInDetail::find($dt->id);
                if($detail->quantity >= $detail->qty_sold+ $quantity)
                {
                    $detail->qty_sold += $quantity;
                    $in_id->id = $detail->id;
                    $in_id->qty  = $quantity;
                    array_push($in_ids, $in_id);
                    $quantity = 0;
                }
                else
                {
                    $in_id->id = $detail->id;
                    $in_id->qty  = ($detail->quantity - $detail->qty_sold);
                    
                    array_push($in_ids, $in_id);
                    $quantity= $quantity- ($detail->quantity - $detail->qty_sold);
                    $detail->qty_sold = $detail->quantity;
                }
                $detail->save();
                if($quantity == 0)
                    break;
            }
            return $in_ids;
        
    }
    public static function addWarehouseToDestroyDetailInsSeries($product_id,$wh_id,$detail)
    {
        $qty = 1;
        $in_id = new IDs();
        if($detail->quantity >= $detail->qty_sold+ $qty)
        {
            $detail->qty_sold += $qty;
            $in_id->id = $detail->id;
            $in_id->qty  = $qty;
        }
        $detail->save();
        return $in_id;
    }


    public static function deleteWarehouseToDestroy($detail)
    {
        $inventory = \App\Models\Inventory::where('product_id',$detail->product_id)
        ->where('wh_id',$detail->wh_id)->first();
        $dinventroy = \App\Models\InventoryDestroy::where('product_id',$detail->product_id)
        ->first();
        if( $inventory && $dinventroy && $dinventroy->quantity >=$detail->quantity)
        {
            $dinventroy->quantity -= $detail->quantity;
            $dinventroy->save();
            $inventory->quantity += $detail->quantity;
            $inventory->save();
            $in_ids = json_decode($detail->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                if($detail_in )
                {
                    $detail_in->qty_sold -= $in_id->qty;
                    $detail_in->save(); 
                }
            }
            return 1;
        }
        else
            return 0;
        
    }
}
