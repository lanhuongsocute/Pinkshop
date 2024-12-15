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
use App\Models\UGroup;
use App\Models\User;
use App\Models\WarehouseToProperty;


class WarehousetoPropertyController extends Controller
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
        $func = "wp_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wtp_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho sử dụng </li>';
        $wtps=WarehouseToProperty::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.warehousetoproperties.index',compact('wtps','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "wp_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="wtp_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetoproperty.index').'">Ds chuyển kho sử dụng</a></li>
        <li class="breadcrumb-item">Thêm chuyển sử dụng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.warehousetoproperties.create',compact('user', 'warehouses', 'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     * moi mot don hoac co seri hoặc ko, nên số lượng > 0 và seri = 0 hoặc só lương = so seri
     */
    public function store(Request $request)
    {
        //
        $func = "wp_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'wh_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'price'=>'numeric|required',
            'series'=>'string|nullable',
        ]);
        $data = $request->all();
        $n_count_series = 0;
        $pro_inventory = Inventory::where('product_id',$data['product_id'])->where('wh_id', $data['wh_id'])->first();
        if(!$pro_inventory || $pro_inventory->quantity < $data['quantity'] )
        {
            return back()->with('error','Tồn kho không đủ!')->withInput();;
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
        $counts_n = \DB::select ("select count(id) as tong from warehousein_detail_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
        $counts_n = $counts_n[0]->tong;
        //so hang khong co seri ton kho
        $n_noseri = $pro_inventory->quantity - $counts_n ;
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
                if($seri == '')
                    continue;
                $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
                $rows = \DB::select($query);
                if(count($rows) == 0)
                {
                return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
                }
                    
        } 
        //so hang khong co seri ton kho
        $n_noseri = $pro_inventory->quantity - $counts_n ;
        
        //so hang khong co seri xuat kho
        $sold_noseri =$data['quantity'] - $count_n;
        if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
        {
            return back()->with('error','Số hàng không có series trong phiếu xuất nhiều hơn trong kho!')->withInput();;
        }

        
         //TAO MOI
        //tim prebalance cua san pham truoc khi xuat
        $inv = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])
            ->first();
        if( $inv)
            $product_detail['prebalance'] =$inv->quantity;
        else
            $product_detail['prebalance'] = 0;
    
        //thu hien chuyen kho tai san
        \App\Models\Inventory::addWarehouseToProperty($data['product_id'],$data['quantity'],$data['wh_id']);
        
     
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $wtp = WarehouseToProperty::c_create($data);
        
        $in_ids =\App\Models\Inventory::addWarehouseToPropertyDetailInsNoSeries($data['product_id'],$sold_noseri,$data['wh_id']);
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            if($seri == '')
                    continue;
            $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $data_seri['wp_id'] = $wtp->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'wp';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            \App\Models\PropertySeries::create($data_seri);
            $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                ->where('product_id',$wi_seri->product_id)->first();
            $in_id = Inventory::updateWarehouseInDetails($data['product_id'], $data['wh_id'],$detail_in);
            array_push($in_ids, $in_id);
        }
        
        if($in_ids )
        {
            //tao warehouseoutdetail voi loai la wp
            $product_detail['wo_id'] =  $wtp->id;
            $product_detail['product_id']= $data['product_id'];
            $product_detail['quantity'] = $data['quantity'];
            $product_detail['price'] = $data['price'];
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='wp';
            $product_detail['wh_id']=$data['wh_id'];
            \App\Models\WarehouseoutDetail::c_create($product_detail);
            ///CAP NHAT WAREHOUSETOPROPERTIES
            $wtp->in_ids = json_encode($in_ids);
            $wtp->save();
            \App\Models\InvPropertyDetail::c_create($wtp,'wp',1,  $count_n>0?1:0); //1 la nhap
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtp->id,'wtp',$user->id);
            return redirect()->route('warehousetoproperty.index')->with('success','Tạo chuyển kho sử dụng thành công!');
        }
        else
        {
            return back()->withInput()->with('error','Không tìm thấy tồn kho!');
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
        $func = "wp_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $wtp = WarehouseToProperty::find($id);
        if(!$wtp)
            return back()->with('error','Không tìm thấy dữ liệu!');
         
        if (\App\Models\InvPropertyDetail::check_sold($id,'wp'))
        {
            return back()->with('error','sản phẩm đã xuất khỏi kho tài sản, không thể điều chỉnh!');
        }
        $wp_series = \DB::select("select * from property_series where wp_id=".$wtp->id.' and doc_type = "wp"');
        
        
        $series = "";
        $i = 0;
        foreach($wp_series as $wp_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wp_seri->seri;
            $i ++;
        }
        $active_menu="wtp_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('warehousetoproperty.index').'">Ds chuyển sử dụng</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho sử dụng</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        
        return view('backend.warehousetoproperties.edit',compact('wtp', 'warehouses', 'breadcrumb','active_menu','series'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "wp_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'product_id'=>'numeric|required|gt:0',
            'wh_id'=>'numeric|required|gt:0',
            'quantity'=>'numeric|required|gt:0',
            'price'=>'numeric|required|gt:0',
        ]);
        $data = $request->all();
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $pinventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
            ->first();
        $wtp = WarehouseToProperty::find($id);
        if (\App\Models\InvPropertyDetail::check_sold($id,'wp'))
        {
            return back()->with('error','sản phẩm đã xuất khỏi kho tài sản, không thể điều chỉnh!');
        }
        if(  !$inventory)
        {
            // return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if($inventory && $wtp->product_id == $data['product_id'] && $data['quantity'] > $wtp->quantity + $inventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
       
    //    check new series
    //update old series in to 0
        $wp_series = \App\Models\PropertySeries::where('wp_id',$wtp->id)->where('is_sold',1)->get();
        if (count($wp_series) > 0)
        {
            return back()->with('error','đã có sản phẩm được xuất khỏi kho nên không thể cập nhật!');
        }
        //cap nhat cac warehousein xuat qua properties nhu chua xuat de kiem tra thong tin moi
        $wp_series = \App\Models\PropertySeries::where('wp_id',$wtp->id)->where('is_sold',0)->get();
        foreach($wp_series as $wp_seri)
        {
            $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wp_seri->in_id ;
            \DB::select($query);
        }
        $series = array();
        if (isset($request->series))
            $series =  explode(",",  $data['series']);
       
        $count_n =0; //so series muốn xuất
        if($data['series']!= '')
        {
            $count_n =count($series );
        }
        $counts_n = \DB::select("select count(id) as tong from warehousein_detail_series where product_id = ".$data['product_id'].' and is_sold = 0');

        $counts_n = $counts_n[0]->tong; //so series có trong kho
        if($count_n > $counts_n )
        {
            return back()->with('error','số lượng series xuất nhiều hơn có trong kho!');
        }
        if($count_n !=0 && $count_n != $data['quantity'] )
        {
            return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
        }
        foreach ($series as $seri)
        {
              $seri = trim($seri);
              $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
              $rows = \DB::select($query);
              if(count($rows) == 0)
              {
                  foreach($wp_series as $wo_seri)
                  {
                          $query = 'update warehousein_detail_series set is_sold = 1 where id = '.$wo_seri->in_id ;
                          \DB::select($query);
                  }
                  return back()->with('error','số seri '.$seri.' không có trong kho!');
              }
        } 
        //so hang khong co seri ton kho
        $n_noseri = $inventory->quantity - $counts_n + $wtp->quantity ;
        //so hang khong co seri xuat kho
        $sold_noseri =$data['quantity'] - $count_n  ;
        if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
        {
            return back()->with('error','số lượng xuất không seri '.$sold_noseri.' nhiều hơn số tồn kho không seri '.$n_noseri.' trong kho!');
        }

       
        ////update each seri in to 0 mean not transfer to properties to delete old seri in property
        $wp_series = \App\Models\PropertySeries::where('wp_id',$wtp->id)->get();
        foreach($wp_series as $wo_seri)
        {
             $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id ;
             \DB::select($query);
        }
        $sql = "delete from property_series where doc_type='wp' and wp_id=". $wtp->id;
        \DB::select($sql);  //XOA so seri
        
        //xoa warehouse out detail
        // \DB::select("update from warehouseout_details set wo_id = 0 where doc_type='wp' and wo_id = ".$wtp->id);
        $detail_outs = \App\Models\WarehouseoutDetail::where('doc_type','wp')->where('wo_id',$wtp->id)->get();
        \App\Models\WarehouseoutDetail::deleteWO($detail_outs ,'wp');
        \App\Models\Inventory::deleteWarehouseToProperty($wtp);
        \App\Models\InvPropertyDetail::remove($wtp->id,'wp');
        //TAO MOI
        //tim prebalance cua san pham truoc khi xuat
        $inv = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])
            ->first();
        if( $inv)
            $product_detail['prebalance'] =$inv->quantity;
        else
            $product_detail['prebalance'] = 0;

        \App\Models\Inventory::addWarehouseToProperty($data['product_id'],$data['quantity'],$data['wh_id']);
        $in_ids =\App\Models\Inventory::addWarehouseToPropertyDetailInsNoSeries($data['product_id'],$sold_noseri,$data['wh_id']);
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $data_seri['wp_id'] = $wtp->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'wp';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            \App\Models\PropertySeries::create($data_seri);
           

            $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                ->where('product_id',$wi_seri->product_id)->first();
            $in_id = Inventory::updateWarehouseInDetails($data['product_id'], $data['wh_id'],$detail_in);
            array_push($in_ids, $in_id);
        }
        
        if($in_ids )
        {
            //tao warehouseoutdetail voi loai la wp
            $product_detail['wo_id'] =  $wtp->id;
            $product_detail['product_id']= $data['product_id'];
            $product_detail['quantity'] = $data['quantity'];
            $product_detail['price'] = $data['price'];
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='wp';
            $product_detail['wh_id']=$data['wh_id'];
            \App\Models\WarehouseoutDetail::c_create($product_detail);

            ////cap nhat warehousetoproperty
            $data['in_ids'] = json_encode($in_ids);
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $wtp->fill($data)->save();
            \App\Models\InvPropertyDetail::c_create($wtp,'wp',1,$count_n>0?1:0); //1 la nhap

           
            ///create log /////////////
            $content = 'cập nhật phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtp->id,'wtp',$user->id);
            return redirect()->route('warehousetoproperty.index')->with('success','Tạo chuyển kho sử dụng thành công!');
     
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "wp_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $wtp = WarehouseToProperty::find($id);
        if (\App\Models\InvPropertyDetail::check_sold($id,'wp'))
        {
            return back()->with('error','sản phẩm đã xuất khỏi kho tài sản, không thể điều chỉnh!');
        }
        if($wtp)
        {
           
            ////capnhat lai warehouse series chua xuat
            $wp_series = \App\Models\PropertySeries::where('wp_id',$wtp->id)->get();
            foreach($wp_series as $wo_seri)
            {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id ;
                \DB::select($query);
            }
            //xoa het seri properties
            $sql = "delete from property_series where doc_type='wp' and wp_id=". $wtp->id;
            \DB::select($sql);
            //xoa warehouse out detail
            // \DB::select("update from warehouseout_details set wo_id = 0 where doc_type='wp' and wo_id = ".$wtp->id);
            $detail_outs = \App\Models\WarehouseoutDetail::where('doc_type','wp')->where('wo_id',$wtp->id)->get();
            \App\Models\WarehouseoutDetail::deleteWO($detail_outs ,'wp');
            \App\Models\Inventory::deleteWarehouseToProperty($wtp);
            //xoa detail in của properties
            \App\Models\InvPropertyDetail::remove($wtp->id,'wp');
            
            ///create log /////////////
            $user = auth()->user();
            $content = 'xóa phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtp->id,'wtp',$user->id);
            $wtp->delete();
            return redirect()->route('warehousetoproperty.index')->with('success','Xóa chuyển kho sử dụng thành công!');
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu!');
        }
    }
}
