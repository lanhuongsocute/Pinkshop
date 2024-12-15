<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvMaintainDetail extends Model
{
    use HasFactory;
    protected $fillable = ['doc_id', 'doc_type','is_delete','product_id','quantity','price','qty_sold','operation' ,'balance','in_ids','is_seri'];
    public static function c_create($wtp,$doc_type, $operation,$is_seri)
    {
        $data['doc_id'] = $wtp->id;
        $data['doc_type'] =  $doc_type;
        $data['is_delete'] =  0;
        $data['product_id'] = $wtp->product_id;
        $data['quantity'] =   $wtp->quantity;
        if (isset($wtp->price))
            $data['price'] =  $wtp->price;
        else
            $data['price'] = 0;
        $data['qty_sold'] =   0;
        $data['operation'] =  $operation;
        $data['is_seri'] =  $is_seri;
      
        $inv = \App\Models\InventoryMaintenance::where('product_id',$wtp->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $inv->quantity;
        return \App\Models\InvMaintainDetail::create( $data);
    }
    
    public static function ms_create($ms_id,$detail,$doc_type, $operation,$is_seri)
    {
        $data['doc_id'] = $ms_id;
        $data['doc_type'] =  $doc_type;
        $data['is_delete'] =  0;
        $data['product_id'] = $detail->product_id;
        $data['quantity'] =   $detail->quantity;
        $data['price'] =  0;
        $data['qty_sold'] =   0;
        $data['operation'] =  $operation;
        $data['is_seri'] =  $is_seri;
      
        $inv = \App\Models\InventoryMaintenance::where('product_id',$detail->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $inv->quantity;
        return \App\Models\InvMaintainDetail::create( $data);
    }
    public static function mb_create($mb_id,$detail,$doc_type, $operation,$is_seri)
    {
        $data['doc_id'] = $mb_id;
        $data['doc_type'] =  $doc_type;
        $data['is_delete'] =  0;
        $data['product_id'] = $detail->product_id;
        $data['quantity'] =   $detail->quantity;
        $data['price'] =  $detail->price;
        $data['qty_sold'] =   0;
        $data['operation'] =  $operation;
        $data['is_seri'] =  $is_seri;
      
        $inv = \App\Models\InventoryMaintenance::where('product_id',$detail->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $inv->quantity;
        return \App\Models\InvMaintainDetail::create( $data);
    }
    public static function remove($doc_id,$doc_type)
    {
        $ipd = \App\Models\InvMaintainDetail::where('doc_id',$doc_id)->where('doc_type',$doc_type)
                ->where('is_delete',0)->first();
        $data['doc_id'] = $ipd->doc_id;
        $data['doc_type'] =  $ipd->doc_type;
        $data['is_delete'] =  1;
        $data['product_id'] = $ipd->product_id;
        $data['quantity'] =   $ipd->quantity;
        $data['price'] =  $ipd->price;
        $data['is_seri'] =  $ipd->is_seri;
        $data['qty_sold'] =   0;
        $data['operation'] =  $ipd->operation * (-1);
        $inv = \App\Models\InventoryMaintenance::where('product_id',$ipd->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $ipd->quantity;
        $ipd->is_delete = 1;
        $ipd->save();
        if($ipd->in_ids)
        {
            $in_ids = json_decode($ipd->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\InvMaintainDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
    }
    public static function remove_product($doc_id,$doc_type,$product_id)
    {
        $ipd = \App\Models\InvMaintainDetail::where('doc_id',$doc_id)
        ->where('doc_type',$doc_type)->where('product_id',$product_id)
                ->where('is_delete',0)->first();
        $data['doc_id'] = $ipd->doc_id;
        $data['doc_type'] =  $ipd->doc_type;
        $data['is_delete'] =  1;
        $data['product_id'] = $ipd->product_id;
        $data['quantity'] =   $ipd->quantity;
        $data['price'] =  $ipd->price;
        $data['is_seri'] =  $ipd->is_seri;
        $data['qty_sold'] =   0;
        $data['operation'] =  $ipd->operation * (-1);
        $inv = \App\Models\InventoryMaintenance::where('product_id',$ipd->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $ipd->quantity;
        $ipd->is_delete = 1;
        $ipd->save();
        if($ipd->in_ids)
        {
            $in_ids = json_decode($ipd->in_ids);
            foreach ($in_ids as $in_id)
            {
                $detail_in = \App\Models\InvMaintainDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
    }
   
    public static function check_sold($doc_id,$doc_type)
    {
        $sql = "select * from inv_maintain_details where doc_id =".$doc_id." and doc_type = '".$doc_type."' and   qty_sold > 0   and is_delete = 0";
        $results = \DB::select($sql);
        if(count($results) > 0)
            return 1;
        else
            return 0;
        
    }
    public static function sold_maintain_id($doc_id,$doc_type )
    {
        $in_id = new \App\Models\IDs();
        $ipd = \App\Models\InvMaintainDetail::where('doc_id',$doc_id)
            ->where('is_delete',0)->where('doc_type',$doc_type)->where('operation',1)->first();
        $ipd->qty_sold += 1;
        $ipd->save();
        $in_id->id = $ipd->id;
        $in_id->qty  = 1;
        return $in_id;

    }
    public static function get_product($product_id,$number)
    {
        $ipds = \App\Models\InvMaintainDetail::where('product_id',$product_id)
            ->where('operation',-1)->where('is_delete',0)->whereRaw('qty_sold > 0') 
        ->where('is_seri',0)->get();
        
        $in_ids=array();
        foreach ($ipds as $ipd)
        {
            $in_id = new \App\Models\IDs();
            $n_sold = $ipd->quantity - $ipd->qty_sold;
            if ($number > $n_sold)
            {
                $ipd->qty_sold = $ipd->quantity;
                $ipd->save();
                $number -=  $n_sold;
                $in_id->id = $ipd->id;
                $in_id->qty  = $n_sold;
                array_push($in_ids, $in_id);
            }
            else
            {
                $ipd->qty_sold += $number;
                $ipd->save();
                $in_id->id = $ipd->id;
                $in_id->qty  = $number;
                array_push($in_ids, $in_id);
                $number  =  0;
            }
            if ($number == 0)
                break;
        }
        return $in_ids;
    }
    public static function sold_product($product_id,$number)
    {
          $ipds = \App\Models\InvMaintainDetail::where('product_id',$product_id)
          ->where('operation',1)->where('is_delete',0)->whereRaw('qty_sold < quantity')->where('is_delete',0)
        ->where('is_seri',0)->get();
        
        $in_ids=array();
        foreach ($ipds as $ipd)
        {
            $in_id = new \App\Models\IDs();
            $n_sold = $ipd->quantity - $ipd->qty_sold;
            if ($number > $n_sold)
            {
                $ipd->qty_sold = $ipd->quantity;
                $ipd->save();
                $number -=  $n_sold;
                $in_id->id = $ipd->id;
                $in_id->qty  = $n_sold;
                array_push($in_ids, $in_id);
            }
            else
            {
                $ipd->qty_sold += $number;
                $ipd->save();
                $in_id->id = $ipd->id;
                $in_id->qty  = $number;
                array_push($in_ids, $in_id);
                $number  =  0;
            }
            if ($number == 0)
                break;
        }
        return $in_ids;

    }
}