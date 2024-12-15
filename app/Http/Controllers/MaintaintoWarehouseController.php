<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Warehouseout;
use App\Models\SupTransaction; 
use App\Models\WarehouseInDetail;
use App\Models\Bankaccount;
use App\Models\BankTransaction;
use App\Models\FreeTransaction;
use App\Models\User;
use App\Models\MaintaintoWarehouse;

class MaintaintoWarehouseController extends Controller
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
        $func = "mw_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mtw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho bán hàng </li>';
        $mtws=MaintaintoWarehouse::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.maintaintowarehouses.index',compact('mtws','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "mw_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="mtw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintaintowarehouse.index').'">Ds chuyển kho bán hàng</a></li>
        <li class="breadcrumb-item">Thêm chuyển bán hàng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.maintaintowarehouses.create',compact('user', 'warehouses', 'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "mw_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'wh_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'price'=>'numeric|required',
            'time'=>'numeric|nullable',
        ]);
        $data = $request->all();
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        if($minventory && $minventory->quantity >=  $data['quantity'])
        {


            $series = array();
            if(isset($request->series))
            {
                $series =  explode(",",  $data['series']);
            }
                
            $count_n =0; //so series muốn xuất
            if($data['series']!= '')
            {
                $count_n =count($series );
            }
                $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
                $counts_n = $counts_n[0]->tong;
               //so hang khong co seri ton kho
                $n_noseri = $minventory->quantity - $counts_n ;
               //so hang khong co seri xuat kho
                $sold_noseri =$data['quantity'] - $count_n;
                if($count_n > $counts_n )
                {
                    return back()->withInput()->with('error','Số series lơn hơn số hàng số hàng có series trong kho!');
                }
                if($count_n !=0 && $count_n != $data['quantity'] )
                {
                    return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
                }
                foreach ($series as $seri)
                {
                      $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                      if ($seri == '')
                        continue;
                      $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
                      $rows = \DB::select($query);
                      if(count($rows) == 0)
                      {
                        return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
                      }
                          
                } 
                //so hang khong co seri ton kho
                $n_noseri = $minventory->quantity - $counts_n ;
                //so hang khong co seri xuat kho
                $sold_noseri =$data['quantity'] - $count_n;
                if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
                {
                    return back()->with('error','Số hàng không có series trong phiếu xuất nhiều hơn trong kho!')->withInput();;
                }
    
            
            //($pro_id, $wh_id,$qty,$price )
            
             //save maintaintowarehouse doc
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $mtw = MaintainToWarehouse::c_create($data);
            //create warehousein detail
            $product_detail['doc_id'] = $mtw->id;
            $product_detail['doc_type'] = 'mi';
            $product_detail['product_id'] = $data['product_id'];
            $product_detail['price'] = $data['price'];
            $product_detail['quantity'] = $data['quantity'];
            $product_detail['wh_id'] = $data['wh_id'];

            $inv = \App\Models\Inventory::where('product_id',$product_detail['product_id'])
                ->where('wh_id', $product_detail['wh_id'])
                ->first();
            if($inv)
            {
                $product_detail['prebalance'] =$inv->quantity;
            }
            else
            {
                $product_detail['prebalance'] = 0;
            }
            
            //create exmired day
              //save exmired days
            $start_date = date('Y-m-d H:i:s');
            if($data['time'])
            {
                $strday = '+' . $data['time']*30 .' days';
                $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                $product_detail['exmired_at'] = $end_date;
            }
            $product_detail['is_seri'] = $count_n> 0?1:0;
            WarehouseInDetail::create($product_detail);
            \App\Models\InventoryMaintenance::addMaintainToWarehouse($data['product_id'],$data['wh_id'],$data['quantity'],$data['price'] );
           
            //tao inv_maintain_detail out
            $ipd = \App\Models\InvMaintainDetail::c_create($mtw,'mw',-1,$count_n>0?1:0); //1 la nhap
            $in_ids = \App\Models\InvMaintainDetail::sold_product($mtw->product_id,$sold_noseri);

            //them series cho warehousein và cập nhật is_sold cho maintain_series
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                $data_seri['wi_id'] = $mtw->id;
                $data_seri['seri'] = $seri;
                $data_seri['product_id'] = $data['product_id'];
                $data_seri['is_sold'] = 0;
                $data_seri['doc_type'] = 'mi';
                $data_seri['wh_id'] = $data['wh_id'];
                $wi_seri = \App\Models\WarehouseinDetailSeries::create($data_seri);
                $mw_seri = \App\Models\MaintainSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
  
                $mw_seri->is_sold = 1;
                $mw_seri->out_id = $wi_seri->id;
                $mw_seri->save();
                
                $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($mw_seri->wm_id,$mw_seri->doc_type ) ;
                array_push($in_ids,$in_id);

            }
            $ipd->in_ids = json_encode($in_ids);
            $ipd->save();
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho bảo hành sang kho bán hàng' ;
            \App\Models\Log::insertLogNew($content,$mtw->id,'mtw',$user->id);
            return redirect()->route('maintaintowarehouse.index')->with('success','Tạo chuyển kho bán hàng thành công!');
     
        }
        else
        {
            return back()->with('error','Không tìm thấy tồn kho!');
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
        $func = "mw_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mtw = MaintaintoWarehouse::find($id);
        if(!$mtw)
            return back()->with('error','Không tìm thấy dữ liệu!');

      
         
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$mtw->id)->where('doc_type','mi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','Sản phẩm đã xuất không thể xóa!');
            }
        }
        $series = "";
        $i = 0;
        $wp_series = \DB::select('select * from warehousein_detail_series where doc_type="mi" and wi_id = '.$mtw->id.' and wh_id = '.$mtw->wh_id);
        foreach($wp_series as $wp_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wp_seri->seri;
            $i ++;
        }


        $active_menu="mtw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('maintaintowarehouse.index').'">Ds chuyển bán hàng</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho bán hàng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        
        return view('backend.maintaintowarehouses.edit',compact('mtw','series', 'warehouses', 'breadcrumb','active_menu'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "mw_edit";
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
            'time'=>'numeric|nullable',
        ]);
        $data = $request->all();
        // return $data;
        $mtw = MaintainToWarehouse::find($id);
        if(!$mtw)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
        $wi_series = \DB::select("select * from warehousein_detail_series where doc_type='mi' and wi_id=".$mtw->id.' and is_sold = 0 and wh_id = '.$mtw->wh_id);
        
        $series = "";
        $i = 0;
        foreach($wi_series as $wi_seri)
        {
           $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
           $p_seri->is_sold = 0;
           $p_seri->save();
        }
        $series = array();
        if(isset($request->series))
        {
            $series =  explode(",",  $data['series']);
        }
        $count_n =0; //so series muốn xuất
        if($data['series']!= '')
        {
            $count_n =count($series );
        }
            $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
           //so hang khong co seri ton kho
            $n_noseri = $minventory->quantity - $counts_n  + $mtw->quantity;
           //so hang khong co seri xuat kho
            $sold_noseri =$data['quantity'] - $count_n;
            if($count_n > $counts_n )
            {
                return back()->withInput()->with('error','Số series lơn hơn số hàng số hàng có series trong kho!');
            }
            if($count_n !=0 && $count_n != $data['quantity'] )
            {
                return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
            }
            foreach ($series as $seri)
            {
                $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                if ($seri == '')
                        continue;
                $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
                $rows = \DB::select($query);
                if(count($rows) == 0)
                {
                    foreach($wi_series as $wi_seri)
                    {
                        $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
                        $p_seri->is_sold = 1;
                        $p_seri->save();
                    }
                    return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
                }
            } 
         
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if($minventory && $mtw->product_id == $data['product_id'] && $data['quantity'] > $mtw->quantity + $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
        
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$mtw->id)->where('doc_type','mi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','Sản phẩm đã xuất không thể xóa!');
            }
        }
        //remove old transfer
        //

        \DB::select('delete from warehousein_detail_series where doc_type="mi" and wi_id ='.$mtw->id ); //xoa các seri của phieu cu
        \App\Models\WarehouseInDetail::deleteWI($wi_details,'mi');
        \App\Models\InventoryMaintenance::deleteMaintaintoWarehouse($mtw->product_id,$mtw->wh_id,$mtw->quantity,$mtw->price);
        //xoa them detail invp 
       
        \App\Models\InvMaintainDetail::remove($mtw->id,'mw');
        
            //save maintaintowarehouse doc
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $mtw->fill($data)->save();
        //create warehousein detail
        $product_detail['doc_id'] = $mtw->id;
        $product_detail['doc_type'] = 'mi';
        $product_detail['product_id'] = $data['product_id'];
        $product_detail['price'] = $data['price'];
        $product_detail['quantity'] = $data['quantity'];
        $product_detail['wh_id'] = $data['wh_id'];
        $inv = \App\Models\Inventory::where('product_id',$product_detail['product_id'])
            ->where('wh_id', $product_detail['wh_id'])
            ->first();
        if($inv)
        {
            $product_detail['prebalance'] =$inv->quantity;
        }
        else
        {
            $product_detail['prebalance'] = 0;
        }
        //create exmired day
            //save exmired days
        $start_date = date('Y-m-d H:i:s');
        if($data['time'])
        {
            $strday = '+' . $data['time']*30 .' days';
            $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
            $product_detail['exmired_at'] = $end_date;
        }
        $product_detail['is_seri'] = $count_n> 0?1:0;
        $mtw_detail = WarehouseInDetail::create($product_detail)   ;
        //save maintaintowarehouse doc
        \App\Models\InventoryMaintenance::addMaintainToWarehouse($data['product_id'],$data['wh_id'],$data['quantity'],$data['price'] );
     
        //tao inv_property_detail out
        $ipd = \App\Models\InvMaintainDetail::c_create($mtw,'mw',-1,$count_n>0?1:0); //1 la nhap
        $in_ids = \App\Models\InvMaintainDetail::sold_product($mtw->product_id,$sold_noseri);

        ///create log /////////////
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            $data_seri['wi_id'] = $mtw->id;
            $data_seri['seri'] = $seri;
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['is_sold'] = 0;
            $data_seri['doc_type'] = 'mi';
            $data_seri['wh_id'] = $data['wh_id'];
            $wi_seri = \App\Models\WarehouseinDetailSeries::create($data_seri);
            $wp_seri = \App\Models\MaintainSeries::where('seri',$seri)
            ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wp_seri->is_sold = 1;
            $wp_seri->out_id = $wi_seri->id;
            $wp_seri->save();
            $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wp_seri->wm_id,$wp_seri->doc_type ) ;
            array_push($in_ids,$in_id);
        }
        //cap nhat gia tri id vao detail out
        $ipd->in_ids = json_encode($in_ids);
        $ipd->save();

        $content = 'cập nhật phiếu chuyển kho bán hàng' ;
        \App\Models\Log::insertLogNew($content,$mtw->id,'mtw',$user->id);
        return redirect()->route('maintaintowarehouse.index')->with('success','Cập nhật chuyển kho bán hàng thành công!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "mw_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mtw = MaintainToWarehouse::find($id);
        if(!$mtw)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
        //tim het các warehouse in moi tao, kiem tra da xuat chua
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$mtw->id)->where('doc_type','mi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','Sản phẩm đã xuất không thể xóa!');
            }
        }
        //tim các properties series chuyển qua qua warehouse series, cap nhat chua chuyen
        $wi_series = \DB::select("select * from warehousein_detail_series where doc_type='mi' and wi_id=".$mtw->id.' and is_sold = 0');
        foreach($wi_series as $wi_seri)
        {
           $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
           $p_seri->is_sold = 0;
           $p_seri->save();
        }
         //xao series warehousein
        \DB::select('delete from warehousein_detail_series where doc_type="mi" and wi_id ='.$mtw->id); //xoa các seri của phieu cu
       //xoa warehouseindetail
        \App\Models\WarehouseInDetail::deleteWI($wi_details,'mi');
        //capnhat properties
        \App\Models\InventoryMaintenance::deleteMaintaintoWarehouse($mtw->product_id,$mtw->wh_id,$mtw->quantity,$mtw->price);
       
        \App\Models\InvMaintainDetail::remove($mtw->id,'mw');
        ///create log /////////////
        $user = auth()->user();
        $content = 'xóa phiếu chuyển kho bảo hành đến bán hàng' ;
        \App\Models\Log::insertLogNew($content,$mtw->id,'mtw',$user->id);
        $mtw->delete();
        return redirect()->route('maintaintowarehouse.index')->with('success','Cập nhật chuyển kho bán hàng thành công!');
      
    }
}
