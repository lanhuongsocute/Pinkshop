<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IDs;
class InvPropertyDetail extends Model
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
        $data['price'] =  $wtp->price;
        $data['qty_sold'] =   0;
        $data['operation'] =  $operation;
        $data['is_seri'] =  $is_seri;
       
        $inv = \App\Models\InventoryProperties::where('product_id',$wtp->product_id)->first();
        if(!$inv)
            $data['balance'] =  0;
        else
            $data['balance'] =  $inv->quantity;
        return \App\Models\InvPropertyDetail::create( $data);
    }
    public static function remove($doc_id,$doc_type)
    {
        $ipd = \App\Models\InvPropertyDetail::where('doc_id',$doc_id)->where('doc_type',$doc_type)
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
        $inv = \App\Models\InventoryProperties::where('product_id',$ipd->product_id)->first();
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
                $detail_in = \App\Models\InvPropertyDetail::find($in_id->id);
                $detail_in->qty_sold -= $in_id->qty;
                $detail_in->save();
            } 
        }
    }
    public static function check_sold($doc_id,$doc_type)
    {
        $sql = "select * from inv_property_details where doc_id =".$doc_id." and doc_type = '".$doc_type."' and   qty_sold > 0   and is_delete = 0";
        $results = \DB::select($sql);
        if(count($results) > 0)
            return 1;
        else
            return 0;
        
    }
    public static function sold_property_id($doc_id,$doc_type )
    {
        $in_id = new \App\Models\IDs();
        $ipd = \App\Models\InvPropertyDetail::where('doc_id',$doc_id)
            ->where('is_delete',0)->where('doc_type',$doc_type)->where('operation',1)->first();
        $ipd->qty_sold += 1;
        $ipd->save();
        $in_id->id = $ipd->id;
        $in_id->qty  = 1;
        return $in_id;

    }
    public static function sold_product($product_id,$number)
    {
        $ipds = \App\Models\InvPropertyDetail::where('product_id',$product_id)
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
