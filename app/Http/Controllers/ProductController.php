<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
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
    //
    public function index()
    {
        //
        $func = "product_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        

        $active_menu="pro_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Hàng hóa </li>';
        $products = DB::table('products') ->orderBy('id','desc')
            ->paginate($this->pagesize)->withQueryString();;
        $cats = \App\Models\Category::where('status','active')->get();
        $cat_id = 0;
        return view('backend.products.index',compact('products','breadcrumb','active_menu','cats','cat_id'));
    }

    public function productJmodsearch(Request $request)
    {
         
        if($request->data  )
        { 
             

            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);
            $products = DB::table('products')                 
            ->select('id','title','photo','price','expired')
            
            ->where('title','LIKE','%'.$searchdata.'%')
            ->where('type','normal')
            ->where('status','active')
            ->orderBy('products.title','asc')
            ->get();
            return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }

    public function productPrint (Request $request)
    {
        $func = "product_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(isset($request->cat_id))
        {
            $cat_id = $request->cat_id;
        }
        else
        {
            $cat_id = 0;
        }
    
        $active_menu="pro_list";
        if($cat_id != 0)
        {
            $products = DB::table('products')->select('products.photo','products.title','categories.title as cattitle')
            ->leftJoin('categories','products.cat_id','=','categories.id')
            ->where('products.cat_id',$cat_id)
            ->orderBy('title','asc')
            ->get();
        }
        else
        {
            $products = DB::table('products')->select('products.photo','products.title','categories.title as cattitle')
            ->leftJoin('categories','products.cat_id','=','categories.id')
            ->orderBy('title','asc')
            ->get();
        }
        $cats = \App\Models\Category::where('status','active')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page"> In </li>';
        return view('backend.products.print',compact('products','breadcrumb','active_menu','cats','cat_id'));
    }
    public function productSort(Request $request)
    {
        $func = "product_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
        if(isset($request->cat_id))
        {
            $cat_id = $request->cat_id;
        }
        else
        {
            $cat_id = 0;
        }
        $active_menu="pro_list";
        $searchdata =$request->datasearch;
        if($cat_id ==0)
        {
            $products = DB::table('products')->orderBy($request->field_name, $request->type_sort)
            ->paginate($this->pagesize)->withQueryString();;
        }
        else
        {
            $products = DB::table('products')->where('cat_id',$cat_id)
                ->orderBy($request->field_name, $request->type_sort)
                ->paginate($this->pagesize)->withQueryString();;
        }
       
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">hàng hóa</a></li>
        ';
        $cats = \App\Models\Category::where('status','active')->get();
        return view('backend.products.index',compact('products','breadcrumb','searchdata','active_menu','cats','cat_id'));
        
        

    }
    public function productSearch(Request $request)
    {
        $func = "product_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
           
            $searchdata =$request->datasearch;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $products = DB::table('products')->where('title','LIKE','%'.$searchdata.'%')
            ->paginate($this->pagesize)->withQueryString();;;
            $active_menu="pro_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">hàng hóa</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.products.search',compact('products','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('product.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }

    public function productMsearch(Request $request)
    {
        if($request->data  )
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $products = DB::table('products')->where('title','LIKE','%'.$searchdata.'%')
            ->where('status','active')->get();
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }

    public function productJsearch(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            // $binventorys = DB::table('b_inventories')
            // ->select('b_inventories.*' )
            // ->join(\DB::raw($query),
            // 'b_inventories.product_id', '=', 'np.idpro') 
            // ->paginate($this->pagesize)->withQueryString();
            $query = "(select product_id ,quantity from b_inventories where wh_id = ".$wh_id.") as np";
            $products = DB::table('products')
             ->select ('products.id','products.title','products.photo','np.quantity','products.expired')
             ->where('title','LIKE','%'.$searchdata.'%')
             ->where('type','<>','service')
             ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
             ->orderBy('products.title','asc')
             ->get();
             
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }

    }

    public function productTsearch(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            // $binventorys = DB::table('b_inventories')
            // ->select('b_inventories.*' )
            // ->join(\DB::raw($query),
            // 'b_inventories.product_id', '=', 'np.idpro') 
            // ->paginate($this->pagesize)->withQueryString();
            $query = "(select product_id ,quantity from  inventories where wh_id = ".$wh_id.") as np";
            $products = DB::table('products')
             ->select ('products.id','products.title','products.photo','products.price_avg as price','np.quantity','products.expired')
             ->where('title','LIKE','%'.$searchdata.'%')
             ->where('np.quantity','>',0)
             ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
             ->orderBy('products.title','asc')
             ->get();
             
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }

    }
    public function productGPriceSearch(Request $request)
    {
        $this->validate($request,[
            'product_id'=>'numeric|required',
        ]);
        $query = "(select id, price, ugroup_id from group_prices where product_id = ".$request->product_id.") as np";
        $groupprices = DB::table('u_groups')
        ->select ('u_groups.id','u_groups.title', 'np.price','np.id as gpid')
        ->where('status','active')
        ->leftJoin(\DB::raw($query),'u_groups.id','=','np.ugroup_id')
        ->orderBy('id','ASC')->get();
        foreach($groupprices as $grouppice)
        {
            if( $grouppice->gpid == null)
            {
                $data['ugroup_id'] = $grouppice->id;
                $data['price'] = 0;
                $data['product_id'] = $request->product_id;
                \App\Models\GroupPrice::create($data);
            }
        }
        $groupprices = DB::table('u_groups')
        ->select ('u_groups.id','u_groups.title', 'np.price','np.id as gpid')
        ->where('status','active')
        ->leftJoin(\DB::raw($query),'u_groups.id','=','np.ugroup_id')
        ->orderBy('id','ASC')->get();

         
        $productseris = \App\Models\WarehouseinDetailSeries::where('product_id',$request->product_id)
            ->where('is_sold',0)->get();
        $i = 0;
        $series = "";
        foreach ($productseris as $productseri)
        {
            if ($i > 0)
                $series .= ',';
            $series .= $productseri->seri;
            $i ++;
        }

        return response()->json(['msg'=>$groupprices,'series'=>$series,'status'=>true]);
    }
    
    public function productJsearchwf(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            
            $query = "(select product_id ,quantity from inventories where wh_id = ".$wh_id."  ) as np";
            $products = DB::table('products')
             ->select ('products.id','products.title','products.photo','products.price_avg as price','np.quantity','products.expired','products.type')
             ->where('title','LIKE','%'.$searchdata.'%') 
             ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
             ->where('np.quantity','>',0)
             ->where('stock','>',0)
             ->orderBy('products.title','asc')
             ->get();
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productJsearchic(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            
            $query = "(select product_id ,quantity from inventories where wh_id = ".$wh_id."  ) as np";
            $products = DB::table('products')
             ->select ('products.id','products.title','products.photo','products.price_avg as price','np.quantity','products.expired')
             ->where('title','LIKE','%'.$searchdata.'%') 
             ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
             
             ->orderBy('products.title','asc')
             ->get();
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productJsearchwi(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            
            $query = "(select product_id ,quantity from inventories where wh_id = ".$wh_id.") as np";
            $query2 = "(select seri  from   where product_id = ".$wh_id.") as np";
            $products = DB::table('products')
             ->select ('products.id','products.title','products.photo','products.price_in as price','np.quantity','products.expired')
             ->where('title','LIKE','%'.$searchdata.'%')->where('type','normal')
             ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
             ->orderBy('products.title','asc')
             ->get();
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productJsearchco(Request $request)
    {
        if($request->data  )
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            
            
           
            $products = DB::table('products')
             ->select ('products.*' )
             ->where('title','LIKE','%'.$searchdata.'%')->where('type','normal')
             
             ->orderBy('products.title','asc')
             ->get();
             return response()->json(['msg'=>$products,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }

    public function productJsearchwo(Request $request)
    {
        if($request->data && $request->warehouse_id)
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $wh_id = $request->warehouse_id;
            $customer_id = $request->customer_id;
            $customer = \App\Models\User::find($customer_id);
            // return $customer;
            if(  $customer == null || $customer->ugroup_id ==null)
            {
                //  return 'b';
                $query = "(select product_id ,quantity from inventories where wh_id = ".$wh_id.") as np";
                // $query1 = "(select product_id ,price from group_prices where wh_id = ".$wh_id.") as np";
                $products = DB::table('products')
                 ->select ('products.id','products.title','products.photo','products.price_in as price_in','products.price_out as price_out','products.price as price','products.price_avg as price_avg','products.sold','products.type','np.quantity','products.expired')
                 ->where('title','LIKE','%'.$searchdata.'%')
                
                 ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
                 ->where(function($query_sub)  
                 {
                    $query_sub->where('np.quantity','>',0)
                           ->orwhere('products.type', 'service');
                 })
                 ->orderBy('products.sold','desc')
                 ->get();
                 return response()->json(['msg'=>$products,'status'=>true]);
            }
            else
            {
                // return 'a';
                $query = "(select product_id ,quantity from inventories where wh_id = ".$wh_id.") as np";
                $query1 = "(select product_id ,price from group_prices where ugroup_id = ".$customer->ugroup_id.") as up";
                $products = DB::table('products')
                 ->select ('products.id','products.title','products.photo','up.price as price','products.type','np.quantity','products.expired')
                 ->where('title','LIKE','%'.$searchdata.'%')
                 ->leftJoin(\DB::raw($query),'products.id','=','np.product_id')
                 ->leftJoin(\DB::raw($query1),'products.id','=','up.product_id')
                 ->where(function($query_sub)  
                 {
                    $query_sub->where('np.quantity','>',0)
                           ->orwhere('products.type', 'service');
                 })
                 ->orderBy('products.title','asc')
                 ->get();
                 return response()->json(['msg'=>$products,'status'=>true]);
            }
            
            
           
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }

    public function productJsearchms(Request $request)
    {
        if($request->data )
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);
 
                $query = "select a.id, a.title , a.photo, a.price_out as price, b.quantity from (select * from products where title like '%".
                            $searchdata."%' and status = 'active') as a left join (select product_id ,quantity from inventory_maintenances ) as b on a.id = b.product_id where b.quantity > 0 order by a.title asc;";
                $products = DB::select($query);
                 return response()->json(['msg'=>$products,'status'=>true]);
            
           
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productJsearchmtw(Request $request)
    {
        if($request->data )
        { 
            $searchdata =$request->data;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

                $query = "select a.id, a.title , a.photo, a.price_avg as price, b.quantity from (select * from products where title like '%".
                            $searchdata."%' and status = 'active') as a left join (select product_id ,quantity from inventory_maintenances ) as b on a.id = b.product_id where b.quantity > 0 order by a.title asc;";
                $products = DB::select($query);
                 return response()->json(['msg'=>$products,'status'=>true]);
            
           
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productJsearchptw(Request $request)
    {
        if($request->data )
        { 
            $searchdata =$request->data;
             $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

                $query = "select a.id, a.title , a.photo, a.price_avg as price, b.quantity from (select * from products where title like '%".
                            $searchdata."%' and status = 'active') as a left join (select product_id ,quantity from inventory_properties ) as b on a.id = b.product_id where b.quantity > 0 order by a.title asc;";
                $products = DB::select($query);
                 return response()->json(['msg'=>$products,'status'=>true]);
            
           
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function itcctv_jsearch(Request $request)
    {
        $this->validate($request,[
            'searchdata'=>'string|required',
        ]);
        $helpController = new \App\Http\Controllers\HelpController();
        return $helpController->get_products($request->searchdata);
        
    }
    public function itcctv_productdetail(Request $request)
    {
        $this->validate($request,[
            'id'=>'numeric|required',
        ]);
        $helpController = new \App\Http\Controllers\HelpController();
        return $helpController->get_detailproduct($request->id);
        
    }
    public function productStock_quantity(Request $request)
    {
        if($request->product_id && $request->warehouse_id)
        { 
            $pro_id =$request->product_id;
            $wh_id = $request->warehouse_id;
         
            $binventory = DB::table('b_inventories')->where('product_id',$pro_id)->where('wh_id',$wh_id)->first();
            if($binventory)
                return response()->json(['msg'=>$binventory->quantity,'status'=>true]);
            else
                {
                    return response()->json(['msg'=>'','status'=>false]);
                }
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }
    }
    public function productStatus(Request $request)
    {
        $func = "product_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('products')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('products')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="pro_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">hàng hóa</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo hàng hóa </li>';
        $categories = Category::where('is_parent',0)
            ->where('status','active')->orderBy('title','ASC')->get();
        $brands = Brand::where('status','active')
            ->orderBy('title','ASC')->get();

        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
       
        return view('backend.products.create',compact('breadcrumb','active_menu','categories','brands','tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'description'=>'string|nullable',
            'photo'=>'string|nullable',
            'brand_id'=>'numeric|nullable',
            'cat_id'=>'numeric|nullable',
            'size'=>'string|nullable',
            'weight'=>'numeric|nullable',
           
            'status'=>'nullable|in:active,inactive',
        ]);
        $data = $request->all();
        /// ------end replace --///
        $helpController = new \App\Http\Controllers\HelpController();
        $data['description'] = $helpController->uploadImageInContent( $data['description'] );
        // ------end replace --///
        $parent_cat = Category::find($data['cat_id']) ;
        if($parent_cat != null)
            $data['parent_cat_id'] = $parent_cat->parent_id;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['stock'] = 0;
        // return $data;
        $slug = Str::slug($request->input('title'));
        $slug_count = Product::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        $pro = Product::where('title',$data['title'])->first();
        if($pro != null)
        {
            return back()->with('error','Sản phẩm đã có');
        }
        $status = Product::c_create($data);
        ////store tag
        $tag_ids = $request->tag_ids;
        if($tag_ids && count($tag_ids)> 0)
        {
            $tagservice = new \App\Http\Controllers\TagController();
            $tagservice->store_product_tag($status->id,$tag_ids);
        }
       
        if($status){
            return redirect()->route('product.index')->with('success','Tạo hàng hóa thành công!');
        }
        else
        {
            return back()->with('error','Something went wrong!');
        }    
    }
    public function productAdd(Request $request)
    {
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'expired'=>'numeric|nullable',
            'cat_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $parent_cat = Category::find($data['cat_id'])->value('parent_id');
        if($parent_cat != null)
            $data['parent_cat_id'] = $parent_cat;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['stock'] = 0;
        $data['summary']="-";
        // return $data;
        $slug = Str::slug($request->input('title'));
        $slug_count = Product::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        $status = Product::c_create($data);
        if($status){
            return response()->json(['msg'=>"Đã thêm sản phẩm!",'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'Lỗi trong quá trình lưu!','status'=>false]);
        }    
    }
    public function productAddm(Request $request)
    {
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'expired'=>'numeric|nullable',
            'cat_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $parent_cat = Category::find($data['cat_id'])->value('parent_id');
        if($parent_cat != null)
            $data['parent_cat_id'] = $parent_cat;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['stock'] = 0;
        $data['summary']="-";
        // return $data;
        $slug = Str::slug($request->input('title'));
        $slug_count = Product::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        $data['is_sold'] = 0; 
        $status = Product::c_create($data);
        if($status){
            return response()->json(['msg'=>"Đã thêm sản phẩm!",'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'Lỗi trong quá trình lưu!','status'=>false]);
        }    
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "product_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $product = Product::find($id);
        if($product)
        {
            $active_menu="pro_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">products</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh products </li>';
            $categories = Category::where('is_parent',0)->orderBy('title','ASC')->get();
            $brands = Brand::orderBy('title','ASC')->get();
            return view('backend.products.show',compact('breadcrumb','product','active_menu','categories','brands'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $func = "product_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
            $product = Product::find($id);
            if($product)
            {
                $active_menu="pro_list";
                $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">products</a></li>
                <li class="breadcrumb-item active" aria-current="page"> điều chỉnh products </li>';
                $categories = Category::where('is_parent',0)->orderBy('title','ASC')->get();
                $brands = Brand::orderBy('title','ASC')->get();
                $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
                $tag_ids =DB::select("select tag_id from tag_products where product_id = ".$product->id)  ;  
       
                return view('backend.products.edit',compact('breadcrumb','product','active_menu','categories','brands','tags','tag_ids'));
            }
            else
            {
                return back()->with('error','Không tìm thấy dữ liệu');
            }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "product_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $product = Product::find($id);
        if($product)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'summary'=>'string|nullable',
                'description'=>'string|nullable',
                'photo'=>'string|nullable',
                'brand_id'=>'numeric|nullable',
                'cat_id'=>'numeric|nullable',
                'size'=>'string|nullable',
                'weight'=>'numeric|nullable',
                'photo_old'=>'string|nullable',
                'status'=>'nullable|in:active,inactive',
            ]);
    
            $data = $request->all();
            // dd($data);
             /// ------end replace --///
          $helpController = new \App\Http\Controllers\HelpController();
          $data['description'] = $helpController->uploadImageInContent( $data['description'] );
          // ------end replace --///
            if($request->photo_old == null)
            {
                $data['photo_old'] =   $product->photo;
            }
                
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ; 
            else
                $data['photo'] = $data['photo'].",". $data['photo_old']   ;
            $parent_cat = Category::find($data['cat_id']) ;
            if($parent_cat != null)
                $data['parent_cat_id'] = $parent_cat->parent_id;

            $status = $product->fill($data)->save();
            // $product->update_kiot();
            //update tags
            $tag_ids = $request->tag_ids;
            if($tag_ids && count($tag_ids)> 0)
            {
                $tagservice = new \App\Http\Controllers\TagController();
                $tagservice->update_product_tag($product->id,$tag_ids);
            }
           
            if($status){
                return redirect()->route('product.index')->with('success','Cập nhật thành công');
            }
            else
            {
                return back()->with('error','Something went wrong!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "product_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $product = Product::find($id);
        
        if($product)
        {
            $status = Product::deleteProduct($id);
            if($status){
                return redirect()->route('product.index')->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','Vẫn còn hàng trong kho hoặc hàng liên quan đến các phiếu nhập xuất không thể xóa!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function productPriceView($id)
    {
       
        $product = Product::find($id);
        if(!$product)
        {
            return  back()->with('error','Không tìm thấy dữ liệu');
        }
        $active_menu="pro_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">hàng hóa</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thiết lập giá '. $product->title.'</li>';
     
        $sql1 = "select a.* , b.price from (select * from u_groups where status = 'active') as a"
        ." left join ( select * from group_prices where product_id = ".$id.") as b on a.id = b.ugroup_id";
        // dd($sql1);
        $group_prices = \DB::select($sql1);
        foreach ($group_prices as $gprice)
        {
            
            if( $gprice->price ===null   )
            {
                $data['ugroup_id'] = $gprice->id;
                $data['price'] = 0;
                $data['product_id'] = $id;
                \App\Models\GroupPrice::create($data);
            }
        }
        $productextend =  \App\Models\Productextend::where('product_id',$id)->first();
        if(! $productextend  )
        {
            $data['old_price'] = 0;
            $data['product_id'] = $id;
            $productextend = \App\Models\Productextend::create($data);
        }
        return view('backend.products.viewprice',compact('product','breadcrumb','active_menu','group_prices','productextend'));
      
    }
    public function productPriceUpdate(Request $request)
    {
        $this->validate($request,[
            'id'=>'numeric|required',
            'old_price'=>'numeric|required',
        ]); 
        $data= $request->all();
        $productextend =  \App\Models\Productextend::where('product_id',$data['id'])->first();
        $productextend->old_price = $data['old_price'];
        $productextend->save();
        $product = Product::find($data['id']);
        $product->price = $data['price'];
        $product->save();
        
        $gprices =  \App\Models\GroupPrice::where('product_id',$data['id'])->get();
        // dd($data);
        foreach($gprices as $gprice)
        {
            $gprice->price = isset($data['gp'.$gprice->ugroup_id])?$data['gp'.$gprice->ugroup_id]:0;
            $gprice->save();
        }
        // return back()->with('success','Đã lưu thông tin');
        return redirect()->route('product.priceview',$data['id'])->with('success','cập nhật thành công');
    }
}
