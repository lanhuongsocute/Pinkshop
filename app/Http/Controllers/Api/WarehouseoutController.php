<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
class WarehouseoutController extends Controller
{
    public function getInvoice(Request $request)
    {
        $func = "invoice_read";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập',
            ], 200);
        }
        
        $this->validate($request,[
            'uiid'=>'string|required',
        ]);
        $detail = \App\Models\SettingDetail::find(1);
        $wo = \App\Models\Warehouseout::where('uiid', $request->uiid)->first();
        if (!$wo)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hóa đơn',
            ], 200);
        }
       
        $query = "(select id,photo, title,type,summary,description from products ) as p";
       
               
        $products = DB::table('warehouseout_details')
        ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.quantity', 'p.title','p.photo','p.id','p.type','p.summary','p.description' )
        ->where('wo_id',$wo->id)->where('doc_type','wo')
        ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            
            $i = 0;
            $series = "";
            $iproductseris = \App\Models\WarehouseoutDetailSeries::where('wo_id',$wo->id)->where('product_id',$product->id)->where('doc_type','wo')->get();
            // $series = "";
            foreach ($iproductseris as $productseri)
            {
                if ($i > 0)
                    $series .= ',';
                $series .= $productseri->seri;
                $i ++;
            }
            $product->series=$series;

        }
        $wo->products = $products;

        $wo->supplier_name = $detail->company_name;
        $wo->supplier_phone = $detail->phone;
        $wo->supplier_address = $detail->address;
        $wo->supplier_email = $detail->email;
        
        $str_wo = json_encode($wo);
        // $str_wo="haha";
        // dd( $str_wo);
        
        return response()->json([
                'success' => true,
                'data' => $str_wo,
            ], 200);
        
       
    }
}