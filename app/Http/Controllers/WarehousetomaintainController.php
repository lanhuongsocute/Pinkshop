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
use App\Models\Warehousetomaintain;
 

class WarehousetomaintainController extends Controller
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
        $func = "wm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wm_trans";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho bảo hành </li>';
        $warehousemains=Warehousetomaintain::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.warehousetomaintain.index',compact('warehousemains','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "wm_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wm_trans";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetomaintain.index').'">Ds chuyển bảo hành/a></li>
        <li class="breadcrumb-item">Thêm chuyển bảo hành</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.warehousetomaintain.create',compact('user', 'warehouses', 'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "wm_add";
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
        //thu hien chuyen kho bảo hành
        \App\Models\Inventory::addWarehouseToMaintain($data['product_id'],$data['quantity'],$data['wh_id']);
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $wtm = Warehousetomaintain::c_create($data);
        $in_ids =\App\Models\Inventory::addWarehouseToMaintainDetailInsNoSeries($data['product_id'],$sold_noseri,$data['wh_id']);
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            if($seri == '')
                    continue;
            $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $data_seri['wm_id'] = $wtm->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'wm';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            \App\Models\MaintainSeries::create($data_seri);
            $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                ->where('product_id',$wi_seri->product_id)->first();
            $in_id = Inventory::updateWarehouseInDetails($data['product_id'], $data['wh_id'],$detail_in);
            array_push($in_ids, $in_id);
        }
        
        if($in_ids )
        {
            //tao warehouseoutdetail voi loai la wm
            $product_detail['wo_id'] =  $wtm->id;
            $product_detail['product_id']= $data['product_id'];
            $product_detail['quantity'] = $data['quantity'];
            $product_detail['price'] = $data['price'];
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='wm';
            $product_detail['wh_id']=$data['wh_id'];
            \App\Models\WarehouseoutDetail::c_create($product_detail);
            ///CAP NHAT WAREHOUSETOPROPERTIES
            $wtm->in_ids = json_encode($in_ids);
            $wtm->save();
            \App\Models\InvMaintainDetail::c_create($wtm,'wm',1,  $count_n>0?1:0); //1 la nhap
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtm->id,'wtm',$user->id);
            return redirect()->route('warehousetomaintain.index')->with('success','Tạo chuyển kho bảo hành thành công!');
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
        $func = "wm_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $wtm = Warehousetomaintain::find($id);
        if(!$wtm)
            return back()->with('error','Không tìm thấy dữ liệu!');

        if (\App\Models\InvMaintainDetail::check_sold($id,'wm'))
        {
            return back()->with('error','sản phẩm đã xuất khỏi kho tài sản, không thể điều chỉnh!');
        }
        $wm_series = \DB::select("select * from maintain_series where wm_id=".$wtm->id.' and doc_type = "wm"');
        
        
        $series = "";
        $i = 0;
        foreach($wm_series as $wm_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wm_seri->seri;
            $i ++;
        }
        $active_menu="wm_trans";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('warehousetomaintain.index').'">Ds chuyển bảo hành</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho bảo hành</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        
        return view('backend.warehousetomaintain.edit',compact('wtm', 'warehouses', 'breadcrumb','active_menu','series'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "wm_edit";
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
            'series'=>'string|nullable',
        ]);
        $data = $request->all();
        $inventory = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])->first();
        $pinventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        $wtp = Warehousetomaintain::find($id);
        if (\App\Models\InvMaintainDetail::check_sold($id,'wm'))
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
        $wm_series = \App\Models\MaintainSeries::where('wm_id',$wtp->id)->where('is_sold',1)->get();
        if (count($wm_series) > 0)
        {
            return back()->with('error','đã có sản phẩm được xuất khỏi kho nên không thể cập nhật!');
        }
        //cap nhat cac warehousein xuat qua properties nhu chua xuat de kiem tra thong tin moi
        $wm_series = \App\Models\MaintainSeries::where('wm_id',$wtp->id)->where('is_sold',0)->get();
        foreach($wm_series as $wm_seri)
        {
            $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wm_seri->in_id ;
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
                foreach($wm_series as $wo_seri)
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
        $wm_series = \App\Models\MaintainSeries::where('wm_id',$wtp->id)->get();
        foreach($wm_series as $wo_seri)
        {
            $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id ;
            \DB::select($query);
        }
        $sql = "delete from maintain_series where doc_type='wm' and wm_id=". $wtp->id;
        \DB::select($sql);  //XOA so seri
        
        //xoa warehouse out detail
        // \DB::select("update from warehouseout_details set wo_id = 0 where doc_type='wm' and wo_id = ".$wtp->id);
        $detail_outs = \App\Models\WarehouseoutDetail::where('doc_type','wm')->where('wo_id',$wtp->id)->get();
        \App\Models\WarehouseoutDetail::deleteWO($detail_outs ,'wm');
        \App\Models\Inventory::deleteWarehouseToMaintain($wtp);
        \App\Models\InvMaintainDetail::remove($wtp->id,'wm');
        //TAO MOI
        //tim prebalance cua san pham truoc khi xuat
        $inv = \App\Models\Inventory::where('product_id',$data['product_id'])
            ->where('wh_id',$data['wh_id'])
            ->first();
        if( $inv)
            $product_detail['prebalance'] =$inv->quantity;
        else
            $product_detail['prebalance'] = 0;

        \App\Models\Inventory::addWarehousetomaintain($data['product_id'],$data['quantity'],$data['wh_id']);
        $in_ids =\App\Models\Inventory::addWarehousetomaintainDetailInsNoSeries($data['product_id'],$sold_noseri,$data['wh_id']);
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $data_seri['wm_id'] = $wtp->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'wm';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            \App\Models\MaintainSeries::create($data_seri);
        

            $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                ->where('product_id',$wi_seri->product_id)->first();
            $in_id = Inventory::updateWarehouseInDetails($data['product_id'], $data['wh_id'],$detail_in);
            array_push($in_ids, $in_id);
        }
        
        if($in_ids )
        {
            //tao warehouseoutdetail voi loai la wm
            $product_detail['wo_id'] =  $wtp->id;
            $product_detail['product_id']= $data['product_id'];
            $product_detail['quantity'] = $data['quantity'];
            $product_detail['price'] = $data['price'];
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='wm';
            $product_detail['wh_id']=$data['wh_id'];
            \App\Models\WarehouseoutDetail::c_create($product_detail);

            ////cap nhat warehousetoproperty
            $data['in_ids'] = json_encode($in_ids);
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $wtp->fill($data)->save();
            \App\Models\InvMaintainDetail::c_create($wtp,'wm',1,$count_n>0?1:0); //1 la nhap
            ///create log /////////////
            $content = 'cập nhật phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtp->id,'wtp',$user->id);
            return redirect()->route('warehousetomaintain.index')->with('success','Tạo chuyển kho sử dụng thành công!');
    
        }
        else
        {
            return back()->with('error','Không tìm thấy tồn kho!');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "wm_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $wtp = Warehousetomaintain::find($id);
        if (\App\Models\InvMaintainDetail::check_sold($id,'wm'))
        {
            return back()->with('error','sản phẩm đã xuất khỏi kho tài sản, không thể điều chỉnh!');
        }
        if($wtp)
        {
           
            ////capnhat lai warehouse series chua xuat
            $wm_series = \App\Models\MaintainSeries::where('wm_id',$wtp->id)->get();
            foreach($wm_series as $wo_seri)
            {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id ;
                \DB::select($query);
            }
            //xoa het seri properties
            $sql = "delete from maintain_series where doc_type='wm' and wm_id=". $wtp->id;
            \DB::select($sql);
            //xoa warehouse out detail
            // \DB::select("update from warehouseout_details set wo_id = 0 where doc_type='wm' and wo_id = ".$wtp->id);
            $detail_outs = \App\Models\WarehouseoutDetail::where('doc_type','wm')->where('wo_id',$wtp->id)->get();
            \App\Models\WarehouseoutDetail::deleteWO($detail_outs ,'wm');
            \App\Models\Inventory::deleteWarehouseToMaintain($wtp);
            //xoa detail in của properties
            \App\Models\InvMaintainDetail::remove($wtp->id,'wm');
            
            ///create log /////////////
            $user = auth()->user();
            $content = 'xóa phiếu chuyển kho sử dụng' ;
            \App\Models\Log::insertLogNew($content,$wtp->id,'wtp',$user->id);
            $wtp->delete();
            return redirect()->route('warehousetomaintain.index')->with('success','Xóa chuyển kho sử dụng thành công!');
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu!');
        }
    }
}
