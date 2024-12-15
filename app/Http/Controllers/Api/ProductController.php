<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    //
    public function getProductBrand(Request $request)
    {
     
        $this->validate($request,[
            'brand_id'=>'numeric|required',
        ]);
        $brand = \App\Models\Brand::find($request->brand_id);
        if(!$brand)
        {
            return response()->json([
                'success' => false,
                'message' => 'Không có danh mục này!',
            ], 200);
        }
        $products = \App\Models\Product::where('brand_id',$brand->id)->where('status','active')->get();
        return response()->json([
            'success' => true,
            'products' => json_encode($products),
        ], 200);
         
    }
    public function getBrand(Request $request)
    {
        $brands = \App\Models\Brand::where('status','active')->get() ;
          return response()->json([
            'success' => true,
            'brands' => json_encode($brands),
        ], 200);
         
    }
   
}
