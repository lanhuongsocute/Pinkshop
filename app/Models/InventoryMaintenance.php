<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity' ];
    public static function addPro($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $quantity;
            $minventory->save();
        }
        else
        {
            $minven['product_id'] = $product_id;
            $minven['quantity'] =  $quantity;
            $minventory = \App\Models\InventoryMaintenance::create($minven);
        }
    }
    public static function removePro($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $quantity;
            $minventory->save();
        }
        
    }
    public static function backPro($product_id,$quantity,$extra_cost,$supplier_id)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $quantity;
            $minventory->save();
        }
        else
        {
            $minven['product_id'] = $product_id;
            $minven['quantity'] =  $quantity;
            $minventory = \App\Models\InventoryMaintenance::create($minven);
        }
        //update maintainin sent
        // $query= "select a.id from (select  * from maintain_sent_details where product_id = ".$product_id." and back < quantity ) as a left join  maintain_sents as b on a.ms_id = b.id  where b.supplier_id = ".$supplier_id." order by  id asc";
        // $details = \DB::select($query);
        // $in_ids=array();
        // $ms_id = 0;
        // foreach ($details as $dt)
        // {
        //     // return 'dt'.$dt->id;
        //     $in_id = new IDs();
        //     $detail_sent = \App\Models\MaintainSentDetail::find($dt->id);
        //     if($detail_sent->quantity -($detail_sent->back+ $quantity) > 0)
        //     {
        //         $detail_sent->back += $quantity;
        //         $in_id->id = $detail_sent->id;
        //         $in_id->qty  =$quantity;
        //         array_push($in_ids, $in_id);
        //         $quantity = 0;
        //     }
        //     else
        //     {
        //         $in_id->id = $detail_sent->id;
        //         $in_id->qty  = ($detail_sent->quantity - $detail_sent->back);
        //         array_push($in_ids, $in_id);
        //         $quantity= $quantity- ($detail_sent->quantity - $detail_sent->back);
        //         $detail_sent->back = $detail_sent->quantity;
                
        //     }
        //     $detail_sent->save();
        //     $ms_id = $detail_sent->ms_id;
        //     ///update ms record if all detail back
        //     $ms_details = \App\Models\MaintainSentDetail::where('ms_id',$ms_id);
        //     $flag = 0;
        //     foreach ($ms_details as $ms_detail)
        //     {
        //         if($ms_detail->back < $ms_detail->quantity)
        //         {
        //             $flag = 1;
        //             break;
        //         }
        //     }
        //     if($flag == 0)
        //     {
        //         $ms = \App\Models\MaintainSent::find($ms_id);
        //         $ms->status = "back";
        //         $ms->save();
        //     }
        //     ////////////////////////////////////////
        //     $in_back_ids = json_decode( $detail_sent->in_ids);
        //     $temp = $in_id->qty;
        //     foreach ($in_back_ids as $in_back_id)
        //     {
        //         $detail_in = \App\Models\MaintenanceIn::find($in_back_id->id);
                
        //         if($detail_in)
        //         {
        //             $detail_in->status = "back";
        //             if ( $temp  > $in_back_id->qty)
        //              {
        //                 $detail_in->final_amount += $extra_cost * $in_back_id->qty;
        //                 $temp -= $in_back_id->qty;
        //              }   
        //             else
        //             {
        //                 $detail_in->final_amount += $extra_cost * $temp   ;
        //                 $temp = 0;
        //             }   
        //             $detail_in->save();
        //             if($temp == 0)
        //                 break;
        //         }
        //     }
        //     if($quantity == 0)
        //         break;
        // }
        // return $in_ids;
        
    }
    public static function deletebackPro($back_detail,$extra_cost )
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$back_detail->product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $back_detail->quantity;
            $minventory->save();
        }
        // //update maintainin sent
        // $in_ids=json_decode($back_detail->in_ids);
        // $temp =$back_detail->quantity ;
        // foreach ($in_ids  as $in_id)
        // {
        //     // return 'dt'.$dt->id;
        //     $detail_sent = \App\Models\MaintainSentDetail::find($in_id->id);
        //     $detail_sent->back -= $in_id->qty;
        //     $detail_sent->save();
        //     $in_back_ids = json_decode( $detail_sent->in_ids);
            
        //     foreach ($in_back_ids as $in_back_id)
        //     {
        //         $detail_in = \App\Models\MaintenanceIn::find($in_back_id->id*1);
        //         if($detail_in)
        //         {
        //             $detail_in->status = "sent";
        //             if ( $temp> $in_back_id->qty)
        //             {
        //                 $detail_in->final_amount -= $extra_cost * $in_back_id->qty;
        //                 $temp -= $in_back_id->qty;
        //             }  
        //             else
        //             {
        //                 $detail_in->final_amount -= $extra_cost * $back_detail->quantity ;
        //                 $temp= 0;
        //             }   
                 
        //             $detail_in->save();
        //             if ($temp == 0)
        //                 break;
        //         }
        //         $ms_id = $detail_sent->ms_id;
        //     ///update ms record if all detail back
                
        //         $ms = \App\Models\MaintainSent::find($detail_sent->ms_id);
        //         $ms->status = "sent";
        //         $ms->save();
             
        //     ////////////////////////////////////////
        //     }
        // }
        $back_detail->delete();
    }
    public static function sendPro($product_id,$quantity,$extra_cost)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $quantity;
            $minventory->save();
        }
        //update maintainin sent
     
        
    }
    public static function getMainInSend($min_ids,$extra_cost)
    {
        $in_ids=array();
        foreach ($min_ids as $min_id)
        {
            if($min_id->qty > 0)
            {
                $sql = ' select * from inv_maintain_details where id = '.$min_id->id.' and doc_type ="mi" and is_delete = 0 ';
                $res = \DB::select($sql);
                if(count($res)> 0)
                {
                    $in_id = new IDs();
                    foreach($res as $re)
                    {
                        $main_in = \App\Models\MaintenanceIn::where('id',$re->doc_id)->first();
                        $main_in->status="sent";
                        $main_in->sent += $min_id->qty;
                        $main_in->final_amount += $extra_cost*$min_id->qty;
                        $main_in->save();
                        $in_id->id = $re->doc_id;
                        $in_id->qty  = $min_id->qty;
                        array_push($in_ids, $in_id);
                    }
                }
            }
        }
        return $in_ids;
    }
    public static function deletesendPro($detail,$extra_cost)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$detail->product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $detail->quantity;
            $minventory->save();
        }
        //update maintainin sent
       
        $in_ids=json_decode($detail->in_ids);
        
        foreach ($in_ids as $in_id)
        {
            $detail_in = \App\Models\MaintenanceIn::find($in_id->id);
            $detail_in->sent -= $in_id->qty;
            $detail_in->final_amount -= $extra_cost*$in_id->qty;
            if ($detail_in->sent == 0)
            {
                $detail_in->status = 'received';
            }
            $detail_in->save();
        } 
        $detail->delete();
    }
    public static function shopTransfer($pro_id, $wh_id,$qty,$price )
    {
        $minventory = \App\Models\InventoryMaintenance::where("product_id",$pro_id)->first();
        if($minventory)
        {
            $minventory->quantity -= $qty;
            $minventory->save();
        }
       
        $product = Product::where("id",$pro_id)->first();
        if($product)
        {
            // $avg = (int) (($product->price_out * $product->sold + ( $price  )*$qty)
            //                 /($product->sold + $qty));
            $product->stock += $qty;
            $product->save();
        }
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
        //create warehousein detail
         
    }
    public static function deleteShopTransfer($pro_id, $wh_id,$qty,$price )
    {
        $minventory = \App\Models\InventoryMaintenance::where("product_id",$pro_id)->first();
        if($minventory)
        {
            $minventory->quantity += $qty;
            $minventory->save();
        }
       
        $product = Product::where("id",$pro_id)->first();
        if($product)
        {
            // $avg = (int) (($product->price_out * $product->sold + ( $price  )*$qty)
            //                 /($product->sold + $qty));
            $product->stock -= $qty;
            $product->save();
        }
        $inventory = \App\Models\Inventory::where("product_id",$pro_id)->where("wh_id",$wh_id)->first();
        if($inventory)
        {
            $inventory->quantity -= $qty;
            $inventory->save();
        }
        else
        {
            $data_inv['product_id'] = $pro_id;
            $data_inv['wh_id'] = $wh_id;
            $data_inv['quantity']= $qty;
            \App\Models\Inventory::create($data_inv);
        }
        //update or delete warehousein detail
         
    }
    public static function addMaintainToWarehouse($pro_id, $wh_id,$qty,$price )
    {
        $pinventory = \App\Models\InventoryMaintenance::where("product_id",$pro_id)->first();
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
    public static function deleteMaintaintoWarehouse($pro_id, $wh_id,$qty,$price )
    {
        $pinventory = \App\Models\InventoryMaintenance::where("product_id",$pro_id)->first();
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
    public static function addMaintainToProperty($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $quantity;
            $minventory->save();
        }
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
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
    public static function deleteMaintainToProperty($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $quantity;
            $minventory->save();
        }
        $pinventory = \App\Models\InventoryProperties::where('product_id',$product_id)
        ->first();
        if ($pinventory)
        {
            $pinventory->quantity -= $quantity;
            $pinventory->save();
        }
        
    }
    public static function addMaintainToDestroy($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity -= $quantity;
            $minventory->save();
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
   
    public static function deleteMaintainToDestroy($product_id,$quantity)
    {
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$product_id)
        ->first();
        if ($minventory)
        {
            $minventory->quantity += $quantity;
            $minventory->save();
        }
        $dinventory = \App\Models\InventoryDestroy::where('product_id',$product_id)
        ->first();
        if ($dinventory)
        {
            $dinventory->quantity -= $quantity;
            $dinventory->save();
        }
        
    }
}
