<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BInventory;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
class BInventoryController extends Controller
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
    public function index()
    {
        //
        $func = "binv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="bi_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa đầu kỳ </li>';
        $binventorys=binventory::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.binventories.index',compact('binventorys','breadcrumb','active_menu'));

    }
    public function binventorySort(Request $request)
    {
        $func = "binv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="bi_list";
        $searchdata =$request->datasearch;
        $binventorys = DB::table('b_inventories')->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('binventory.index').'">tồn kho đầu kì</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
        return view('backend.binventories.index',compact('binventorys','breadcrumb','searchdata','active_menu'));
    }


    public function binventorySearch(Request $request)
    {
        $func = "binv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="bi_list";
            $searchdata =$request->datasearch;
            $query = "(select id as idpro, title from products where title like'%".$searchdata."%') as np";
            $binventorys = DB::table('b_inventories')
            ->select('b_inventories.*' )
            ->join(\DB::raw($query),
            'b_inventories.product_id', '=', 'np.idpro') 
            ->paginate($this->pagesize)->withQueryString();
            
            // $binventorys = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('binventory.index').'">Tồn kho đầu kỳ</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.binventories.search',compact('binventorys','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('binventory.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "binv_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="bi_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('binventory.index').'">Tồn kho đầu kì</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $products = Product::where('status','active')->orderBy('title','ASC')->get();
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        return view('backend.binventories.create',compact('breadcrumb','active_menu','products','warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "binv_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'wh_id'=>'numeric|required',
            'quantity'=>'numeric|required|gt:0',
            'price'=>'numeric|required|gt:0',
        ]);
        $data = $request->all();
        $count_n =0; //so series muốn xuất
        $series = array();
        if(isset($request->series))
        {
            $series =  explode(",",  $data['series']);
            if($data['series']!= '')
            {
                $count_n =count($series );
                
            }
        }
       
        if($count_n > 0 && $count_n != $data['quantity'])
        {
            return back()->with('error','Số seri khác số lượng nhập!');
        }
        foreach ($series as $seri)
        {
                $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                if ($seri == '')
                continue;
                $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and wh_id='.$data['wh_id'].' and product_id ='.$data['product_id'];
                $rows = \DB::select($query);
                if(count($rows) > 0)
                {
                return back()->with('error','Số serie' . $seri.' đã có trong kho!')->withInput();;
                }
                    
        } 
        $binventory = BInventory::where('product_id',$data['product_id'])
                        ->where('wh_id',$data['wh_id'])->first();
        $inventory = Inventory::where('product_id',$data['product_id'])
                        ->where('wh_id',$data['wh_id'])->first();
        $product = Product::find($data['product_id']);
        
        $product->price_avg =  ($product->price_avg *  $product->stock +   $data['price'] *   $data['quantity'])/( $product->stock +  $data['quantity']);
        $product->stock += $data['quantity'];
        $product->save();
        if($binventory != null)
        {
            $binventory->price = $data['price'];
            $binventory->quantity += $data['quantity'];
            $binventory->save();
            
            $status = true;
        }
        else
        {
            $binventory = BInventory::create($data);
            $status = true;
        }
        if($inventory != null)
        {
           
            $inventory->quantity += $data['quantity'];
            $inventory->save();
            
            $status = true;
        }
        else
        {
            $status = Inventory::create($data);
        }
        // return $data;
        //create Detailwarehouse in with wi_id = 0 and wti_id = 0
        $widetail = \App\Models\WarehouseInDetail::where('doc_id',$binventory->id)
            ->where('product_id',$data['product_id'])->first();
        
        $product_detail['doc_id'] =$binventory->id;
        $product_detail['doc_type'] ='bi';
        $product_detail['product_id']= $data['product_id'];
        $product_detail['quantity'] = $data['quantity'];
        $product_detail['price'] = $data['price'];
        $product_detail['is_seri'] = $count_n>0?1:0;
        //save expired days
        $product = Product::find($data['product_id']);
        $start_date = date('Y-m-d H:i:s');
        if($product->expired)
        {
            $strday = '+' . $product->expired*30 .' days';
            $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
            $product_detail['expired_at'] = $end_date;
        }

        //  return $product_detail;
        if($widetail == null)
            $widetail= \App\Models\WarehouseInDetail::create($product_detail);
        else
         {
            $product_detail['quantity'] = $product_detail['quantity'] + $widetail->quantity;
            $widetail->fill($product_detail)->save();
         }  
         foreach ($series as $seri)
         {
            $seri = trim ($seri);
            if($seri == '')
                    continue;
            \App\Models\WarehouseinDetailSeries::c_create($binventory->id,$seri, $data['product_id'],'bi',$data['wh_id']);
         }
        if($status){
            $user = auth()->user();
               
            $content = 'lưu tồn kho đầu kỳ' ;
            \App\Models\Log::insertLogNew($content,$binventory->id,'binven',$user->id);
        
            return redirect()->route('binventory.index')->with('success','Tạo hàng hóa thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
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
        $func = "binv_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $binventory = Binventory::find($id);
        if($binventory)
        {
            $active_menu="pro_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('binventory.index').'">Tồn kho đầu kỳ</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh tồn kho đầu kỳ</li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            
            $wm_series = \DB::select("select * from warehousein_detail_series where wi_id=".$binventory->id.' and wh_id = '.$binventory->wh_id.'  and doc_type = "bi"');
            
            $series = "";
            $i = 0;
            foreach($wm_series as $wm_seri)
            {
                if($i > 0)
                    $series .= ',';
                $series .= $wm_seri->seri;
                $i ++;
            }
            
            return view('backend.binventories.edit',compact('breadcrumb','binventory','active_menu','warehouses','series'));
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
        $func = "binv_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'wh_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'price'=>'numeric|required',
            
        ]);
        $data = $request->all();
        
        $binventory = BInventory::where('product_id',$data['product_id'])
                        ->where('wh_id',$data['wh_id'])->first();
        if(!$binventory)
            return back()->with('error','Không tìm thấy dữ liệu!');
        $inventory = Inventory::where('product_id',$data['product_id'])
                        ->where('wh_id',$data['wh_id'])->first();

        $count_n =0; //so series muốn xuất
        $series = array();
        if(isset($request->series))
        {
            $series =  explode(",",  $data['series']);
            if($data['series']!= '')
            {
                $count_n =count($series );
            }
        }
        
        if($count_n > 0 && $count_n != $data['quantity'])
        {
            return back()->with('error','Số seri  khác số lượng nhập!');
        }
        $res = \DB::select("select * from warehouse_in_details where doc_id=".$binventory->id." and product_id = ". $data['product_id'].' and doc_type="bi" and qty_sold > 0');
        if(count($res) > 0)
            return back()->with('error','sản phẩm đã được bán ra không thể điều chỉnh tồn kho đầu kì');
        
        $sql = "update warehousein_detail_series set is_sold = 1 where wi_id =".$binventory->id." and doc_type='bi' and product_id=".$data['product_id']. ' and wh_id = '.$data['wh_id'];
        \DB::select($sql);
        foreach ($series as $seri)
        {
            $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
            if ($seri == '')
                continue;
            $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'] . ' and wh_id = '.$data['wh_id'];
            $rows = \DB::select($query);
            if(count($rows) > 0)
            {
                $sql = "update warehousein_detail_series set is_sold = 0 where wi_id =".$binventory->id." and doc_type='bi' and product_id=".$data['product_id']. ' and wh_id = '.$data['wh_id'];
                \DB::select($sql);
                return back()->with('error','Số serie' . $seri.' đã có trong kho!')->withInput();;
            }
        } 

        $sql = "delete from warehousein_detail_series  where wi_id =".$binventory->id." and doc_type='bi' and product_id=".$data['product_id']. ' and wh_id = '.$data['wh_id'];
        \DB::select($sql);

        if($inventory != null)
        {
            $inventory->quantity = $inventory->quantity - $binventory->quantity + $data['quantity'];
            $inventory->save();
        }
        else
        {
            Inventory::create($data);
        }
        //save warehousein_detail
        $widetail = \App\Models\WarehouseInDetail::where('doc_id',$binventory->id)
        ->where('product_id',$data['product_id']) ->first();
    
        $product_detail['doc_id'] =$binventory->id;
        $product_detail['doc_type'] ='bi';
        $product_detail['product_id']= $data['product_id'];
        $product_detail['quantity'] = $data['quantity'];
        $product_detail['price'] = $data['price'];
        $product_detail['is_seri'] = $count_n>0?1:0;
        //save expired days
        $product = Product::find($data['product_id']);
        $start_date = date('Y-m-d H:i:s');
        if($product->expired)
        {
            $strday = '+' . $product->expired*30 .' days';
            $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
            $product_detail['expired_at'] = $end_date;
        }

        //  return $product_detail;
        if($widetail == null)
            \App\Models\WarehouseInDetail::create($product_detail);
        else
        {
             $widetail->fill($product_detail)->save();
        } 
        //save binventory
        if($binventory != null)
        {
            $product = Product::find($binventory->product_id);
            if($product->stock != $binventory->quantity)
            {
                $product->price_avg =  ($product->price_avg *  $product->stock -   $binventory->price *  $binventory->quantity)/( $product->stock -  $binventory->quantity);
                $product->price_avg =  ($product->price_avg *  $product->stock +   $data['price'] *   $data['quantity'])/( $product->stock +  $data['quantity']);
            } 
            else
            {
                $product->price_avg = $data['price'];
            }   
            $product->stock -= $binventory->quantity;
            // $product->save();
            $binventory->quantity = $data['quantity'];
            $binventory->price = $data['price'];

            $product->stock += $binventory->quantity;
            $product->save();
            $binventory->save();

            foreach ($series as $seri)
            {
               $seri = trim ($seri);
               if($seri == '')
                       continue;
               \App\Models\WarehouseinDetailSeries::c_create($binventory->id,$seri, $data['product_id'],'bi',$data['wh_id']);
            }
            ///cn ton kho dau ki
            $user = auth()->user();
            $content = 'cập nhật tồn kho đầu kỳ' ;
            \App\Models\Log::insertLogNew($content,$binventory->id,'binven',$user->id);
        

            return redirect()->route('binventory.index')->with('success','Cập nhật thành công!');
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
