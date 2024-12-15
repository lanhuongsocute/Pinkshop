<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UGroup extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'status'];
    public static function deleteUgroup($cid){
        $ugroup = ugroup::find($cid);
        if(  0) //kiem tra cac rang buoc co nguoi dung nao dang thuoc nhom nay khong
        
            return 0;
        else
        {
           //kiem tra cac rang buoc khac phieu nhap kho xuat kho 
           $ugroup->delete();
           return 1;
        }
    }
}
