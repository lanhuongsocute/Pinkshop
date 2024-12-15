<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseIn extends Model
{
    use HasFactory;
    protected $fillable = ['code','version','wh_id', 'supplier_id', 'vendor_id','final_amount','discount_amount','paid_amount','is_paid','suptrans_id','paidtrans_id',
    'shiptrans_id','cost_extra','debtbefore','debtafter','bankpayment','status'];
    public static function c_create($data)
    {
        $mw = WarehouseIn::create($data);
        $mw->code = "WIN" . sprintf('%09d',$mw->id);
        $mw->save();
        \App\Models\Systrans::add_warehousein($mw->id,$mw->final_amount,1);
        return $mw;
    }
    public  function s_update_final_amount( $new_amount,$delete = false )
    {
         $delete?$scount = 0:$scount = 1;
        \App\Models\Systrans::remove_warehousein($this->id,$this->final_amount,1);
        \App\Models\Systrans::add_warehousein($this->id,$new_amount,$scount);
       
    }
    public static function log_change($warehousein)
    {
          
        $data['inid'] = $warehousein->id;
        $data['code'] = $warehousein->code;
        $data['version'] = $warehousein->version;
        $data['wh_id'] = $warehousein->wh_id;
        $data['supplier_id'] = $warehousein->supplier_id;
        $data['vendor_id'] = $warehousein->vendor_id;
        $data['final_amount'] = $warehousein->final_amount;
        $data['discount_amount'] = $warehousein->discount_amount;
        $data['paid_amount'] = $warehousein->paid_amount;
        $data['is_paid'] = $warehousein->is_paid;
        $data['suptrans_id'] = $warehousein->suptrans_id;
        $data['paidtrans_ids'] = $warehousein->paidtrans_ids;
        $data['shiptrans_id'] = $warehousein->shiptrans_id;
        $data['status'] = $warehousein->status;
        $data['cost_extra'] = $warehousein->cost_extra;
        $outd =  \App\Models\DIn::create($data);

        return $outd;
    }
}
