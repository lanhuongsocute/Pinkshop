<?php

namespace App\Http\Controllers;
use App\Http\Controllers\HttpClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
    }
    public function testApi()
    {
        // $tokenResponse = Http::post('http://localhost:8000/oauth/token', [
        //     'grant_type' => 'client_credentials',
        //     'client_id' => '2',
        //     'client_secret' => 'RnUVcLgakfxZf4G5EQ0IeKl7hTUj3cjA17c1VRF9',
        //     'scope' => '*',
        // ]);
        $tokenResponse = Http::post('http://localhost:8000/api/v1/login', [
            'email' => 'itcctv@itcctv.vn' ,
            'password' =>'@Itcctv123',
        ]);
        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get token'], 500);
        }
    
        $accessToken = $tokenResponse->json()['access_token'];
        echo $accessToken;
        echo '<br/>';
        // $response = Http::withToken($accessToken)->get('http://localhost:8000/api/v1/get_brand');
        // echo $response->status();
        // echo $response->body();
        // if ($response->successful()) {
        //     return $response->json();
        // }
    
    }
    public function view_brand()
    {
       
        $tokenResponse = Http::post('https://itcctv.vn/api/v1/login', [
            'email' => env('USER_DATA') ,
            'password' =>env('PASS_DATA'),
        ]);
        
        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get token'], 500);
        }
        else
        {
        //    echo $tokenResponse->json()['access_token'];
        //    return;
        }
    
        $accessToken = $tokenResponse->json()['token']['token'];
        // echo $accessToken;
        // return;
         
        $response = Http::withToken($accessToken)
                    ->post('https://itcctv.vn/api/v1/get_brand', [
                        // Add other POST data as needed
                    ]);
        
        // Check response status and handle errors
        if ($response->failed()) {
            Log::error('Request failed:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            echo $response->status();
            echo $response->body();
            // Handle error accordingly
        }
        $responseData = "";
        if ($response->successful()) {
            // Request was successful, handle response
            $responseData = $response->json();
            // echo 'thành cong <br/>';
            // return $response->json();
        }
        if ($responseData != "")
        {
            $brands = $responseData['brands'];
            $i = 0;
            $data['brands'] = json_decode($brands);
        }

        $data['active_menu']="setting_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách brand </li>';
        return view('backend.setting.dsbrand',$data);
        
    }
    public function nhap_san_pham_brand(Request $request)
    {
        $brand_id = $request->brand_id;
        $brand_name = $request->brand_name;
        $brand_photo = $request->brand_photo;
        $tokenResponse = Http::post('https://itcctv.vn/api/v1/login', [
            'email' => env('USER_DATA') ,
            'password' =>env('PASS_DATA'),
        ]);
        
        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Failed to get token'], 500);
        }
        else
        {
        //    echo $tokenResponse->json()['access_token'];
        //    return;
        }
    
        $accessToken = $tokenResponse->json()['token']['token'];
        // echo $accessToken;
        // return;
         
        $response = Http::withToken($accessToken)
                    ->post('https://itcctv.vn/api/v1/get_product_brand', [
                        'brand_id' => $brand_id,
                        // Add other POST data as needed
                    ]);
        
        // Check response status and handle errors
        if ($response->failed()) {
            Log::error('Request failed:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            echo $response->status();
            echo $response->body();
            // Handle error accordingly
        }
        $responseData = "";
        if ($response->successful()) {
            // Request was successful, handle response
            $responseData = $response->json();
            // echo 'thành cong <br/>';
            // return $response->json();
        }
        if ($responseData != "")
        {
            $brand = \App\Models\Brand::where('title',$brand_name)->first();
            if(!$brand)
            {
                $datac['title'] = $brand_name;
                $datac['photo'] = $brand_photo;
                $slug = Str::slug($request->input('title'));
                $slug_count = \App\Models\Brand::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $slug .= time().'-'.$slug;
                }
                $datac['slug'] = $slug;
                $datac['status'] = 'active';
                $brand = \App\Models\Brand::create($datac);
            }
            $products = $responseData['products'];
            $i = 0;
            $products = json_decode($products);
        //    dd( $products);
            // return;
            foreach ($products as $product)
            {
                $data['id'] =   0;
                $data['code'] = $product->code   ;
                $data['barcode'] = $product->barcode  ;
                $data['title'] = $product->title   ;
                $data['slug'] = $product->slug   ;
                $data['summary'] = $product->summary  ;
                $data['description'] = $product->description  ;
                $data['stock'] = 0   ;
                $data['sold'] = 0 ;
                $data['price_in'] = $product->price_in  ;
                $data['price_avg'] = $product->price_avg   ;
                $data['price_out'] = $product->price_out  ;
                $data['price'] = $product->price   ;
                $data['hit'] = $product->hit   ;
                $data['brand_id'] = $brand->id  ;
                $data['cat_id'] = $product->cat_id   ;
                $data['parent_cat_id'] = $product->parent_cat_id  ;
                $data['photo'] = $product->photo  ;
                $data['size'] = $product->size   ;
                $data['weight'] = $product->weight   ;
                $data['expired'] = $product->expired  ;
                $data['is_sold'] = $product->is_sold  ;
                $data['type'] = $product->type   ;
                $data['feature'] = 0  ;
                $data['status'] = $product->status   ;

                $pro = \App\Models\Product::where('title',$data['title'])
                        ->orWhere('slug',$data['slug'])->first();
                if(!$pro)
                {
                    \App\Models\Product::create($data);
                    $i ++;
                    echo '<br/>THêm '.$data['title'];
                }
                else
                {
                    echo '<br/>Đã có '.$data['title'];
                }
            }
            
        }
        return redirect()->route('setting.update_data')->with('success',"Thêm " .$i." sản phẩm!");
     //   return response()->json(['error' => 'Failed to fetch data'], 500);
    }
    public function kiemtracongno()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $users = \App\Models\User::get();
        foreach($users as $user)
        {
            $sups= \App\Models\SupTransaction::where('supplier_id',$user->id)
            ->where('is_delete',0)->orderBy('id','desc')->get();
            $tongthu = 0;
            $tongchi = 0;
            $tong = 0;
            foreach ($sups as $sup )
            {
                if($sup->operation == -1)
                    $tongthu += $sup->amount;
                else
                    $tongchi += $sup->amount;

                $tong += $sup->operation*$sup->amount;
            }   
            $user->tong = $tong;                  
                                   
        }
        $data['users'] = $users;
        $data['active_menu']="setting_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
         
        ';
        return view('backend.reports.kiemtracongno',$data);
    }
    public function updateSitemap()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // set_time_limit(20000);
        $sitemap = \Spatie\Sitemap\Sitemap::create();
        $i = 0;
        // Static pages
        $sitemap->add('/');
      
        $sitemap->add(route('front.product.hot'));
        $sitemap->add(route('front.categories.view'));
        
        $i = 7;
        // Dynamic pages
        $products = \App\Models\Product::where('status','active')->get();
        $tags = \App\Models\Tag::all();
        $blogs = \App\Models\Blog::where('status','active')->get();
        $cats = \App\Models\Category::where('status','active')->get();
        foreach ($cats as $cat) {
            $sitemap->add(route('front.product.cat',$cat->slug));
            $i ++;
        }
        foreach ($products as $product) {
            $sitemap->add(route('front.product.view',$product->slug));
            $i ++;
        }
        
        foreach ($tags as $tag) {
            $sitemap->add(route('front.tag.view',$tag->slug));
            $i ++;
        }
        
        foreach ($blogs as $blog) {
            $sitemap->add(route('front.page.view',$blog->slug));
            $i ++;
        }
        
        // $sitemap->writeToFile(asset( 'public/sitemap.xml'));
        //   $sitemap->writeToFile('/home/rnojmetehosting/public_html/tinhocbanme.com/sitemap.xml');
        // $sitemapPath = public_path('sitemap.xml');
        // $sitemap->writeToFile($sitemapPath);
        $sitemapPath = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml';
        $sitemap->writeToFile($sitemapPath);
        return back()->with('success','Đã cập '.$i.' sitemap' );

    }
    public function capnhatanh()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $products = \App\Models\Product::orderBy('id','desc')->get();
        foreach($products as $product)
        {
            $product->photo = str_replace("tinhoctinhocbanme.com","tinhocbanme.com",$product->photo);
            // echo $product->photo;
            $product->save();
        }
    }
    public function capnhatis_delete_suptrans()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $sups = \App\Models\SupTransaction::where('doc_type','wor')->orWhere('doc_type','wir')
            ->orWhere('doc_type','fo')->get();
        foreach($sups as $sup)
        {
            $sup->is_delete= 1;
            $sup->save();
        }

        $fos = \App\Models\SupTransaction::where('doc_type','fo')->get();
        foreach($fos as $fo)
        {
            $fis = \App\Models\SupTransaction::where('doc_type','fi')->where('doc_id',$fo->doc_id)->get();
            if(count($fis) > 1)
            {
                echo '<br/> nhieu hơn 1 fi cho doc_id '.$fo->id;
            }
            else
            {
                foreach($fis as $fi)
                {
                    
                    $fi->is_delete = 1;
                    $fi->save();
                    $fo->is_delete = 1;
                    $fo->save();
                    echo '<br/>cn $fi '.$fi->id;
                }
               
            }
        }

        $wis =  \App\Models\WarehouseIn::where('status','active')->get();
        foreach($wis as $wi)
        {
            $tranwis = \App\Models\SupTransaction::where('doc_type','wi')->where('doc_id',$wi->id)->orderBy('id','desc')->get();
            $i = 0;
            foreach($tranwis as $tranwi)
            {
                if($i > 0)
                {
                    $tranwi->is_delete = 1;
                    $tranwi->save();
                    echo '<br/>cn is delete $tranwi '.$tranwi->id;
                }
                $i ++;
                
            }
        }

        $wis =  \App\Models\WarehouseIn::where('status','<>','active')->get();
        foreach($wis as $wi)
        {
            $tranwis = \App\Models\SupTransaction::where('doc_type','wi')->where('doc_id',$wi->id)->orderBy('id','desc')->get();
            foreach($tranwis as $tranwi)
            {
                
                $tranwi->is_delete = 1;
                $tranwi->save();
                echo '<br/>cn is delete $tranwi '.$tranwi->id;
            }
        }

        $wos =  \App\Models\Warehouseout::where('status', 'active')->get();
        foreach($wos as $wo)
        {
            $tranwos = \App\Models\SupTransaction::where('doc_type','wo')->where('doc_id',$wo->id)->orderBy('id','desc')->get();
            $i = 0;
            foreach($tranwos as $tranwo)
            {
                if($i > 0)
                {
                    $tranwo->is_delete = 1;
                    $tranwo->save();
                    echo '<br/>cn is delete $tranwo '.$tranwo->doc_id;
                }
                $i ++;
            }
        }

        $wos =  \App\Models\Warehouseout::where('status','<>','active')->get();
        foreach($wos as $wo)
        {
            $tranwos = \App\Models\SupTransaction::where('doc_type','wo')->where('doc_id',$wo->id)->orderBy('id','desc')->get();
           
            foreach($tranwos as $tranwo)
            {
                $tranwo->is_delete = 1;
                $tranwo->save();
                echo '<br/>cn is delete $tranwo '.$tranwo->id;
            }
        }

    }
    public function capnhat_loinhuan_wo()
    {
        $wis = \App\Models\WarehouseIn::where('status','<>','active')->get();
        foreach($wis as $wi)
        {
            $details = \App\Models\WarehouseInDetail::where('doc_id',$wi->id)->where('doc_type','wi')->get();
            foreach($details as $detail)
            {
                echo '<br/>wi_id: '.$wi->id;
                $detail->doc_id = 0;
                $detail->save();
            }
        }

        $wos = \App\Models\Warehouseout::where('status','<>','active')->get();
        foreach($wos as $wo)
        {
            $details = \App\Models\WarehouseoutDetail::where('wo_id',$wo->id)->where('doc_type','wo')->get();
            foreach($details as $detail)
            {
                echo '<br/>wo_id: '.$wo->id;
                $detail->wo_id = 0;
                $detail->save();
            }
        }
        \DB::select("update warehouse_in_details set benefit = 0");
        $details = \App\Models\WarehouseoutDetail::where('wo_id','<>',0)->where('doc_type','wo')->get();
        foreach($details as $detailpro)
        {
            $in_ids = array();
            if($detailpro->in_ids!= '')
                $in_ids = json_decode($detailpro->in_ids);
            
            $benefit = 0;
            if( $detailpro->in_ids != '""' && count($in_ids)> 0)
            {
                // dd($in_ids);
                foreach ($in_ids as $in_id)
                {
                    $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                    $benefit += ($detailpro->price - $detail_in->price)*$in_id->qty;
                    $detail_in->benefit += ($detailpro->price - $detail_in->price)*$in_id->qty;
                    $detail_in->save();
                } 
                if( $detailpro->benefit!= $benefit)
                {
                    echo '<br/> $detailpro->benefit '.$detailpro->benefit.' - benefit: '. $benefit;
                }
                $detailpro->benefit = $benefit;
                $detailpro->save();
            }
           
        }
    }
    public function updateInvPro()
    {
        $this->capnhat_loinhuan_wo();
        // $this->capnhatis_delete_suptrans();
       
       /* $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $sql = "delete from inv_property_details";
        \DB::select($sql);
        $sql = "select * from  warehouse_to_properties ";
        $wtps = \DB::select($sql);
        $sql = "select * from  propertyto_warehouses ";
        $ptws =  \DB::select($sql);
        foreach ($wtps as $wtp)
        {
            \App\Models\InvPropertyDetail::c_create($wtp,'wtp',1); //1 la nhap
        }
        foreach ($ptws as $ptw)
        {
            \App\Models\InvPropertyDetail::c_create($ptw,'ptw',-1); //-1 la xuat
        }
            */
        // return back()->with('success','cap nhat ');
    }
    public function viewUpdateData()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu='updatedata';
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
         ';
        return view('backend.setting.updatedata',compact('breadcrumb', 'active_menu' ));
        
    }
    public function KiotIndex()
    { 
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="kiot";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
         ';
        return view('backend.kiot.index',compact('breadcrumb', 'active_menu' ));
    }
    public function KiotCategoryUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchCategoryUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' danh mục' );
      
    }
    public function updateBenefit()
    {
        $sql1 = "select * from warehouseout_details  ";
        $detail_outs = \App\Models\WarehouseoutDetail::get();
        $i = 0;
        foreach ($detail_outs as $detailout)
        {
            $in_ids = json_decode($detailout->in_ids );
            $tong = 0;
            if($in_ids)
            {
                foreach ($in_ids as $in_id)
                {
                    $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
                    if($detail_in)
                    {
                        $tong +=($detailout->price - $detail_in->price)  * $detailout->quantity ;
                    }
                } 
                $detailout->benefit  = $tong;
                $detailout->save();
                $i += 1;
            }
            else
            {
                $tong =$detailout->price * $detailout->quantity; 
                $detailout->benefit  = $tong;
                $detailout->save();
                $i += 1;
            }
            
        }
        return back()->with('success','Đã cập nhật ' .$i. ' chi tiết xuất' );
    }
    public function KiotProductUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchProductUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' sản phẩm' );
      
    }
    public function KiotCustomerUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchCustomerUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' khách hàng' );
      
    }

    public function KiotBranchUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchBranchUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' kho' );
      
    }
    public function KiotuserUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchUserUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' ngươi dùng' );
      
    }
    public function KiotBankUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchBankUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' tài khoản ngân hàng' );
      
    }
    public function KiotCustomerGroupUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchCustomerGroupUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' nhóm khách hàng' );
      
    }
    public function KiotWarehouseinupdateUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchWarehouseinUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' đơn nhập hàng' );
      
    }
    public function KiotWarehouseoutupdateUpdate()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchWarehouseoutUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' đơn bán hàng' );
      
    }
    public function KiotFlowUpdate ()
    {
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $kiotController = new KiotController();
        $total = $kiotController->KiotBatchFlowUpdate();
        return back()->with('success','Đã cập nhật ' .$total. ' nhóm khách hàng' );
      
    }
    
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $setting = \App\Models\SettingDetail::find(1);
        if($setting!= null)
        {
            $active_menu="setting_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
             ';
            return view('backend.setting.edit',compact('breadcrumb','setting','active_menu' ));
    
        }
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "site_setting";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // return $request->all();
        $this->validate($request,[
            'company_name'=>'string|required',
            'phone'=>'string|required',
            'address'=>'string|required',
             
        ]);
        $setting = \App\Models\SettingDetail::find(1);
        // return $request->all();
        $data = $request->all();
        if(!$data['logo'])
        {
            $data['logo'] = asset('backend/assets/dist/images/profile-6.jpg');
        }
        $status = $setting->fill($data)->save();
        if($status){
            return redirect()->route('setting.edit',1);
        }
        else
        {
            return back()->with('error','Something went wrong!');
        }    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
