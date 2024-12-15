<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['code','title','address', 'description', 'status'];
    public static function deleteWarehouse($cid){
        $warehouse = Warehouse::find($cid);
        if(  0) //kiem tra cac rang buoc co nguoi dung nao dang thuoc nhom nay khong
        
            return 0;
        else
        {
           //kiem tra cac rang buoc khac phieu nhap kho xuat kho 
           $warehouse->delete();
           return 1;
        }
    }
    public static function c_create($data)
    {
        $mw = Warehouse::create($data);
        if(!mw->code)
            $mw->code = "WHO" . sprintf('%09d',$mw->id);
        $mw->save();
        return $mw;
    }
}
