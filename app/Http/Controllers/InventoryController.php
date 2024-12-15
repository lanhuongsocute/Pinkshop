<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
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
    public function inventoryViewProduct($id)
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $product = \App\Models\Product::find($id);
        if($product)
        {
            $data['product'] =$product ;
            $data['inventories'] = \App\Models\Inventory::where('product_id',$id)->get();
            $data['series']=\DB::select(  ' select a.*, b.title as wh_title from (select * from  warehousein_detail_series where is_sold = 0 and   product_id ='.$id
                .' ) as a left join (select * from warehouses where status ="active" ) as b'
                .' on a.wh_id = b.id ');
             
            $data['detail_ins']=\DB::select(  ' select a.* , b.* from (select * from  warehouse_in_details  where   product_id ='.$id
            .'  ) as a  left join (select id, code, supplier_id as user_id from warehouse_ins where status="active" ) as b'
            .' on a.doc_id = b.id ');

            $data['detail_outs']=\DB::select(  ' select a.*, b.*  from (select id,wo_id as doc_id,product_id,price,quantity,created_at, prebalance,doc_type from  warehouseout_details  where  product_id ='.$id
            .'  ) as a  left join (select id, code, customer_id as user_id from warehouseouts where status = "active" ) as b'
            .' on a.doc_id = b.id ');

            foreach($data['detail_outs'] as $detail_out)
            {
                $detail_out->quantity *= -1;
            }

            $data['detail_ins'] = array_merge($data['detail_ins'], $data['detail_outs']);
           
            usort( $data['detail_ins'], [$this, 'compareColumn']);
            // dd($data['detail_ins']);
        }
      
      
        $data['active_menu']="i_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> <a href="'.route('inventory.index').'">ds hàng hóa tồn kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds chi tiết nhập xuất sản phẩm '.$product->title.' </li>';
        return view('backend.inventories.product',$data);

    }
    public function inventoryView($id)
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $inventory = \App\Models\Inventory::find($id);
        if($inventory)
        {
            $data['product'] = \App\Models\Product::find($inventory->product_id);
            $data['series']=\DB::select(  ' select a.* from (select * from  warehousein_detail_series where is_sold = 0 and wh_id = '.$inventory->wh_id.' and product_id ='.$inventory->product_id
                .' ) as a left join (select * from warehouse_ins where wh_id ='.$inventory->wh_id.' ) as b'
                .' on a.wi_id = b.id ');
             
            $data['detail_ins']=\DB::select(  ' select a.* , b.* from (select * from  warehouse_in_details  where   product_id ='.$inventory->product_id
            .' and wh_id = '. $inventory->wh_id.' ) as a  left join (select id, code, supplier_id as user_id from warehouse_ins where wh_id ='.$inventory->wh_id.' ) as b'
            .' on a.doc_id = b.id ');

            $data['detail_outs']=\DB::select(  ' select a.*, b.*  from (select id,wo_id as doc_id,product_id,price,quantity,created_at, prebalance,doc_type from  warehouseout_details  where  product_id ='.$inventory->product_id
            .' and wh_id = '. $inventory->wh_id.' ) as a  left join (select id, code, customer_id as user_id from warehouseouts where wh_id ='.$inventory->wh_id.' ) as b'
            .' on a.doc_id = b.id ');

            foreach($data['detail_outs'] as $detail_out)
            {
                $detail_out->quantity *= -1;
            }

            $data['detail_ins'] = array_merge($data['detail_ins'], $data['detail_outs']);
            usort( $data['detail_ins'], [$this, 'compareColumn']);
        }
        
        $data['inventory'] = $inventory;
        $data['active_menu']="i_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> <a href="'.route('inventory.index').'">ds hàng hóa tồn kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds chi tiết series</li>';
        return view('backend.inventories.series',$data);

    }
    public function compareColumn($a, $b) {
        return $b->created_at <=> $a->created_at;
    }
    public function index()
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="i_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa tồn kho</li>';
        $inventorys=Inventory::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.inventories.index',compact('inventorys','breadcrumb','active_menu'));

    }
    public function inventoryPrint(Request $request)
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(isset($request->wh_id))
        {
            $wh_id = $request->wh_id;
        }
        else
        {
            $wh_id = 0;
        }
    
        $active_menu="i_list";
        if($wh_id != 0)
        {
            $inventorys = DB::table('inventories')->select('inventories.quantity','products.title','warehouses.title as whtitle')
            ->leftJoin('products','products.id','=','inventories.product_id')
            ->leftJoin('warehouses','warehouses.id','=','inventories.wh_id')
            ->where('products.type','normal')  ->where('inventories.wh_id',$wh_id)
            ->orderBy('title','asc')
            ->get();
        }
        else
        {
            $inventorys = DB::table('inventories')->select('inventories.quantity','products.title','warehouses.title as whtitle')
            ->leftJoin('products','products.id','=','inventories.product_id')
            ->leftJoin('warehouses','warehouses.id','=','inventories.wh_id')
            ->where('products.type','normal')  
            ->orderBy('title','asc')
            ->get();
        }
        $whs = \App\Models\Warehouse::where('status','active')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventory.index').'">tồn kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> In </li>';
        return view('backend.inventories.print',compact('inventorys','breadcrumb','active_menu','whs','wh_id'));
    }
    public function inventorySort(Request $request)
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="i_list";
        $searchdata =$request->datasearch;
        $inventorys = DB::table('inventories')->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventory.index').'">tồn kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
        return view('backend.inventories.index',compact('inventorys','breadcrumb','searchdata','active_menu'));
    }
    public function inventorySearch(Request $request)
    {
        $func = "inv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        if($request->datasearch)
        {
            $searchdata =$request->datasearch;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $active_menu="i_list";
            
            $query = "(select id as idpro, title from products where title like'%".$searchdata."%') as np";
            // dd($query);
            $inventorys = DB::table('inventories')
            ->select('inventories.*' )
            ->join(\DB::raw($query),
            'inventories.product_id', '=', 'np.idpro') 
            ->paginate($this->pagesize)->withQueryString();
            
            // $inventorys = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventory.index').'">Tồn kho </a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.inventories.search',compact('inventorys','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('inventory.index')->with('success','Không có thông tin tìm kiếm!');
        }

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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
