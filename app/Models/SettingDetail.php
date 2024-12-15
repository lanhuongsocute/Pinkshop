<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingDetail extends Model
{
    use HasFactory;
    protected $fillable = ['web_title','company_name','address','logo','short_name','site_url','phone','icon','map','memory','keyword','mst','email','facebook'
    ,'shopee','lazada','hotline','itcctv_email','itcctv_pass','paymentinfo','thoigiandk','nguoilienhe','logo_bct' ];
    
}
