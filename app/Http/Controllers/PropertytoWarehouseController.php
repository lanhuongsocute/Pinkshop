<?php

namespace App\Http\Controllers;

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
use App\Models\PropertytoWarehouse;


class PropertytoWarehouseController extends Controller
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
        $func = "pw_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ptw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho bán hàng </li>';
        $ptws=PropertytoWarehouse::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.propertytowarehouses.index',compact('ptws','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "pw_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ptw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('propertytowarehouse.index').'">Ds chuyển kho bán hàng</a></li>
        <li class="breadcrumb-item">Thêm chuyển bán hàng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.propertytowarehouses.create',compact('user', 'warehouses', 'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "pw_add";
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
        // return $data;
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $minventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
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
                $counts_n = \DB::select ("select count(id) as tong from property_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
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
                      $query ='select * from property_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
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
            
             //save propertytowarehouse doc
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $ptw = PropertytoWarehouse::c_create($data);
            //create warehousein detail
            $product_detail['doc_id'] = $ptw->id;
            $product_detail['doc_type'] = 'pi';
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
            
            //create expired day
              //save expired days
            $start_date = date('Y-m-d H:i:s');
            if($data['time'])
            {
                $strday = '+' . $data['time']*30 .' days';
                $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $product_detail['is_seri'] = $count_n> 0?1:0;
            WarehouseInDetail::create($product_detail);
            \App\Models\InventoryProperties::addPropertytoWarehouse($data['product_id'],$data['wh_id'],$data['quantity'],$data['price'] );
           
            //tao inv_property_detail out
            $ipd = \App\Models\InvPropertyDetail::c_create($ptw,'pw',-1,$count_n>0?1:0); //1 la nhap
            $in_ids = \App\Models\InvPropertyDetail::sold_product($ptw->product_id,$sold_noseri);

            //them series cho warehousein và cập nhật is_sold cho property_series
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                $data_seri['wi_id'] = $ptw->id;
                $data_seri['seri'] = $seri;
                $data_seri['product_id'] = $data['product_id'];
                $data_seri['is_sold'] = 0;
                $data_seri['doc_type'] = 'pi';
                $data_seri['wh_id'] = $data['wh_id'];
                $wi_seri = \App\Models\WarehouseinDetailSeries::create($data_seri);
                $wp_seri = \App\Models\PropertySeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
  
                $wp_seri->is_sold = 1;
                $wp_seri->out_id = $wi_seri->id;
                $wp_seri->save();
                $in_id = \App\Models\InvPropertyDetail::sold_property_id($wp_seri->wp_id,$wp_seri->doc_type ) ;
                array_push($in_ids,$in_id);

            }
            $ipd->in_ids = json_encode($in_ids);
            $ipd->save();
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho bán hàng' ;
            \App\Models\Log::insertLogNew($content,$ptw->id,'ptw',$user->id);
            return redirect()->route('propertytowarehouse.index')->with('success','Tạo chuyển kho bán hàng thành công!');
     
        }
        else
        {
            return back()->with('error','Không tìm thấy tồn kho hoặc tồn kho không đủ!');
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
        //
        $func = "pw_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $ptw = PropertytoWarehouse::find($id);
        if(!$ptw)
            return back()->with('error','Không tìm thấy dữ liệu!');
         
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$ptw->id)->where('doc_type','pi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','aSản phẩm đã xuất không thể xóa!');
            }
        }
        $series = "";
        $i = 0;
        $wp_series = \DB::select('select * from warehousein_detail_series where doc_type="pi" and wi_id = '.$ptw->id);
        foreach($wp_series as $wp_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wp_seri->seri;
            $i ++;
        }

        $active_menu="ptw_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('propertytowarehouse.index').'">Ds chuyển bán hàng</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho bán hàng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        
        return view('backend.propertytowarehouses.edit',compact('ptw', 'warehouses', 'breadcrumb','active_menu','series'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "pw_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'wh_id'=>'numeric|required',
            'quantity'=>'numeric|required|gt:0',
            'price'=>'numeric|required|gt:0',
            'time'=>'numeric|nullable|gt:0',
        ]);
        $data = $request->all();
        // return $data;
        $ptw = PropertytoWarehouse::find($id);
        if(!$ptw)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $minventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
            ->first();
        //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
        $wi_series = \DB::select("select * from warehousein_detail_series where doc_type='pi' and wi_id=".$ptw->id.' and is_sold = 0');
        
        $series = "";
        $i = 0;
        foreach($wi_series as $wi_seri)
        {
           $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
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
            $counts_n = \DB::select ("select count(id) as tong from property_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
           //so hang khong co seri ton kho
            $n_noseri = $minventory->quantity - $counts_n  + $ptw->quantity;
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
                $query ='select * from property_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
                $rows = \DB::select($query);
                if(count($rows) == 0)
                {
                    foreach($wi_series as $wi_seri)
                    {
                        $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
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
        if($minventory && $ptw->product_id == $data['product_id'] && $data['quantity'] > $ptw->quantity + $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
        
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$ptw->id)->where('doc_type','pi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','Sản phẩm đã xuất không thể xóa!');
            }
        }
        //remove old transfer
        //

        \DB::select('delete from warehousein_detail_series where doc_type="pi" and wi_id ='.$ptw->id); //xoa các seri của phieu cu
        \App\Models\WarehouseInDetail::deleteWI($wi_details,'pi');
        \App\Models\InventoryProperties::deletePropertytoWarehouse($ptw->product_id,$ptw->wh_id,$ptw->quantity,$ptw->price);
        //xoa them detail invp 
       
        \App\Models\InvPropertyDetail::remove($ptw->id,'pw');
        
            //save propertytowarehouse doc
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $ptw->fill($data)->save();
        //create warehousein detail
        $product_detail['doc_id'] = $ptw->id;
        $product_detail['doc_type'] = 'pi';
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
        //create expired day
            //save expired days
        $start_date = date('Y-m-d H:i:s');
        if($data['time'])
        {
            $strday = '+' . $data['time']*30 .' days';
            $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
            $product_detail['expired_at'] = $end_date;
        }
        $product_detail['is_seri'] = $count_n> 0?1:0;
        $ptw_detail = WarehouseInDetail::create($product_detail)   ;
        //save propertytowarehouse doc
        \App\Models\InventoryProperties::addPropertytoWarehouse($data['product_id'],$data['wh_id'],$data['quantity'],$data['price'] );
     
        //tao inv_property_detail out
        $ipd = \App\Models\InvPropertyDetail::c_create($ptw,'pw',-1,$count_n>0?1:0); //1 la nhap
        $in_ids = \App\Models\InvPropertyDetail::sold_product($ptw->product_id,$sold_noseri);

        ///create log /////////////
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            $data_seri['wi_id'] = $ptw->id;
            $data_seri['seri'] = $seri;
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['is_sold'] = 0;
            $data_seri['doc_type'] = 'pi';
            $data_seri['wh_id'] = $data['wh_id'];
            $wi_seri = \App\Models\WarehouseinDetailSeries::create($data_seri);
            $wp_seri = \App\Models\PropertySeries::where('seri',$seri)
            ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wp_seri->is_sold = 1;
            $wp_seri->out_id = $wi_seri->id;
            $wp_seri->save();
            $in_id = \App\Models\InvPropertyDetail::sold_property_id($wp_seri->wp_id,$wp_seri->doc_type ) ;
            array_push($in_ids,$in_id);
        }
        //cap nhat gia tri id vao detail out
        $ipd->in_ids = json_encode($in_ids);
        $ipd->save();

        $content = 'cập nhật phiếu chuyển kho bán hàng' ;
        \App\Models\Log::insertLogNew($content,$ptw->id,'ptw',$user->id);
        return redirect()->route('propertytowarehouse.index')->with('success','Cập nhật chuyển kho bán hàng thành công!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "pw_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $ptw = PropertytoWarehouse::find($id);
        if(!$ptw)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
        //tim het các warehouse in moi tao, kiem tra da xuat chua
        $wi_details = \App\Models\WarehouseInDetail::where('doc_id',$ptw->id)->where('doc_type','pi')->get();
        foreach ($wi_details as $wi_detail)
        {
            if ($wi_detail->qty_sold > 0)
            {
                return back()->with('error','Sản phẩm đã xuất không thể xóa!');
            }
        }
        //tim các properties series chuyển qua qua warehouse series, cap nhat chua chuyen
        $wi_series = \DB::select("select * from warehousein_detail_series where doc_type='pi' and wi_id=".$ptw->id.' and is_sold = 0');
        foreach($wi_series as $wi_seri)
        {
           $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('out_id',$wi_seri->id)->first();
           $p_seri->is_sold = 0;
           $p_seri->save();
        }
         //xao series warehousein
        \DB::select('delete from warehousein_detail_series where doc_type="pi" and wi_id ='.$ptw->id); //xoa các seri của phieu cu
       //xoa warehouseindetail
        \App\Models\WarehouseInDetail::deleteWI($wi_details,'pi');
        //capnhat properties
        \App\Models\InventoryProperties::deletePropertytoWarehouse($ptw->product_id,$ptw->wh_id,$ptw->quantity,$ptw->price);
       
        \App\Models\InvPropertyDetail::remove($ptw->id,'pw');
        ///create log /////////////
        $user = auth()->user();
        $content = 'xóa phiếu chuyển kho bán hàng' ;
        \App\Models\Log::insertLogNew($content,$ptw->id,'ptw',$user->id);
        $ptw->delete();
        return redirect()->route('propertytowarehouse.index')->with('success','Cập nhật chuyển kho bán hàng thành công!');
   
    }
}
