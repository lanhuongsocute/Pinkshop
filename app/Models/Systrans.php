<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systrans extends Model
{
    use HasFactory;
    protected $fillable = ['account_id','operation','total','scount','prebalance','doc_id','doc_type'  ];
    public static function add_warehousein($id,$total,$scount)
    {
        \App\Models\Systrans::add_account($id,$total,$scount,1,'wi');
    }
    public static function remove_warehousein($id,$total,$scount)
    {
        \App\Models\Systrans::remove_account($id,$total,$scount,1,'wi');
    }
    public static function add_warehouseout($id,$total,$scount)
    {
        \App\Models\Systrans::add_account($id,$total,$scount,2,'wo');
    }
    public static function remove_warehouseout($id,$total,$scount)
    {
        \App\Models\Systrans::remove_account($id,$total,$scount,2,'wo');
    }

    public static function add_bank($id,$total,$scount)
    {
        \App\Models\Systrans::add_account($id,$total,$scount,3,'bt');
    }
    
    public static function remove_bank($id,$total,$scount)
    {
        \App\Models\Systrans::remove_account($id,$total,$scount,3,'bt');
    }
    
    public static function add_debt($id,$total,$scount,$doc_type)
    {
        \App\Models\Systrans::add_account($id,$total,$scount,4,$doc_type);
    }
    
    public static function remove_debt($id,$total,$scount,$doc_type)
    {
        \App\Models\Systrans::remove_account($id,$total,$scount,4,$doc_type);
         
    }
    public static function add_service($id,$total,$scount,$doc_type)
    {
        \App\Models\Systrans::add_account($id,$total,$scount,5,$doc_type);
    }
    
    public static function remove_service($id,$total,$scount,$doc_type)
    {
        \App\Models\Systrans::remove_account($id,$total,$scount,5,$doc_type);
         
    }

    public static function add_account($id,$total,$scount,$idsys,$doc_type)
    {
        
        // $wh = \App\Models\Systemaccount::find($idsys);
        // $why = \App\Models\Systemaccountyear::where('account_id',$idsys)
        //     ->where('year', date('Y'))
        //     ->where('month', date('m'))
        //     ->orderBy('id','desc')->first();
             
        // if(! $why)
        // {
        //     $data['account_id'] = $idsys;
        //     $data['year'] = date('Y');
        //     $data['month'] = date('m');
        //     $data['total'] = $wh->total;
        //     $data['scount'] = $wh->scount;
        //     \App\Models\Systemaccountyear::create($data);
        // }
        // $sql1 = "insert into systrans values (null,". $idsys.",1,".$total.",".$scount.",".$wh->total.",".$wh->scount.",".$id.",'".$doc_type."',now(),now());";
        // $sql2 = "update systemaccounts set total = total + ".$total.", scount = scount + ".$scount." where id = ". $idsys." ; ";
        // $sql3 = "update systemaccountyears set total = total + ".$total.", scount = scount + ".$scount." where account_id =". $idsys." and month = ".date('m')." and year = ".date('Y') ." ;";
        // \DB::select ($sql1 . $sql2 .$sql3);
    }
    public static function remove_account($id,$total,$scount,$idsys,$doc_type)
    {
        // $wh = \App\Models\Systemaccount::find($idsys);
        // $warehousein = \App\Models\Systemaccountyear::where('account_id',$idsys)
        //     ->where('year', date('Y'))
        //     ->where('month', date('m'))
        //     ->orderBy('id','desc')->first();
        // if(!$warehousein)
        // {
        //     $data['account_id'] = $idsys;
        //     $data['year'] = date('Y');
        //     $data['month'] = date('m');
        //     $data['total'] = $wh->total;
        //     $data['scount'] = $wh->scount;
        //     \App\Models\Systemaccountyear::create($data);
        // }
        // $sql1 = "insert into systrans values (null,".$idsys.",-1,".$total.",".$scount.",".$wh->total.",".$wh->scount.",".$id.",'".$doc_type."',now(),now());";
        // $sql2 = "update systemaccounts set total = total - ".$total.", scount = scount - ".$scount." where id =". $idsys ."; ";
        // $sql3 = "update systemaccountyears set total = total - ".$total.", scount = scount - ".$scount." where account_id =". $idsys." and month = ".date('m')." and year = ".date('Y') ." ;";
        // \DB::select ($sql1 . $sql2 .$sql3);
    }
    
}