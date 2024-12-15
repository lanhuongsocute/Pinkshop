<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseIn;
use App\Models\SupTransaction; 
use App\Models\WarehouseInDetail;
use App\Models\Bankaccount;
use App\Models\BankTransaction;
use App\Models\FreeTransaction;
use App\Models\MaintainSent;
use App\Models\MaintainSentDetail;

class MaintainSentController extends Controller
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
        $func = "ms_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ms_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh gửi nhận bảo hành </li>';
        $maintainsents=MaintainSent::orderBy('id','DESC')->paginate($this->pagesize);
        
        return view('backend.maintainsents.index',compact('maintainsents','breadcrumb','active_menu'));

    }
    public function deliveryPrint($id)
    {
        $ms = MaintainSent::find($id);
        if($ms && $ms->status == 'sent')
        {
            $active_menu="ms_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainsent.index').'">Danh sách gửi bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page">phiếu gửi hàng </li>';
           
            return view('backend.maintainsents.deprint',compact('breadcrumb','ms','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "ms_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ms_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainsent.index').'">Danh sách gửi bảo hành</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Thêm gửi bảo hành </li>';
        $deliveries = \App\Models\User::where('role','delivery')
            ->where('status','active')->orderBy('full_name','ASC')->get();
        $bankaccounts = Bankaccount::where('status','active')
            ->orderBy('id','ASC')->get();
        return view('backend.maintainsents.create',compact( 'breadcrumb','active_menu','deliveries','bankaccounts'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "ms_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data = $request->importDoc;
        // return $data;
        
        $details = $request->products;
        foreach ($details as $detail)
        {
            
            $pro_inventory = \App\Models\InventoryMaintenance::where('product_id',$detail['id'])->first();
            
            if(!$pro_inventory || $pro_inventory->quantity < $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số lượng tồn kho không đủ!','status'=>false]);
            }
              ////update series for each product
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select("select count(id) as tong from maintain_series where product_id = ".$detail['id'].' and is_sold = 0');
            $counts_n = $counts_n[0]->tong;
            if($count_n > $counts_n )
            {
                return response()->json(['msg'=>'Số lượng seri xuất lớn hơn tồn kho!','status'=>false]);
            }
            if($count_n !=0 && $count_n != $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số lượng series khác số số lượng nhập!','status'=>false]);
            }
            if($count_n > 0)
            {
                foreach ($series as $seri)
                {
                    $seri = trim($seri);
                    if ($seri == '')
                    continue;
                    $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) == 0)
                    {
                        return response()->json(['msg'=>'Số'.$seri.' không có trong kho!','status'=>false]);
                    }
                        
                } 
            }
            //so hang khong co seri ton kho
            $n_noseri = $pro_inventory->quantity - $counts_n ;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
          
            if($sold_noseri -  $n_noseri > 0) //neu so hang ban ko seri > so hàng tonkho thi false
            {
                // return $n_noseri;
                return response()->json(['msg'=>'Số hàng không có seri '.$sold_noseri .' xuất lớn hơn trong kho '.$counts_n .' !','status'=>false]);
            }

        }

        $user = auth()->user();
        $data['vendor_id'] = $user->id;
       
        ////average price///////////////////
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['shipcost'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        $ms = MaintainSent::create($data);
        //save detail
        foreach ($details as $detail)
        {
            $product_detail['ms_id'] = $ms->id;
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
             \App\Models\InventoryMaintenance::sendPro($product_detail['product_id'],$product_detail['quantity'],$data['cost_extra']);
            $product_detail['in_ids'] = '';
            $mdetail = MaintainSentDetail::create($product_detail);
            
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$detail['id'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
            $imd = \App\Models\InvMaintainDetail::ms_create($ms->id,$mdetail, 'ms',-1,$count_n>0?1:0); //1 la nhap
            $in_ids =\App\Models\InvMaintainDetail::sold_product($detail['id'],$sold_noseri );

            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
                    ->where('product_id',$detail['id'])->where('is_sold',0)->first();
               

                $data_seri['ws_id'] = $ms->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'ms';
                $data_seri['product_id'] = $detail['id'];
                $data_seri['in_id'] = $wi_seri->id;
                $main_sent = \App\Models\MaintainSentSeries::create($data_seri);
                $wi_seri->is_sold = 1;
                $wi_seri->out_id = $main_sent->id;
                $wi_seri->save();
                $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
                array_push($in_ids,$in_id);
                
            }
            $imd->in_ids  = json_encode($in_ids);
            $imd->save();
            $min_ids = \App\Models\InventoryMaintenance::getMainInSend($in_ids,$data['cost_extra']);
            $mdetail->in_ids  = json_encode($min_ids);
            $mdetail->save();
        }
        if($data['shipcost'] && $data['shipcost'] > 0)
        {
             $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
             $ms->shiptrans_id = $fts->id;
             BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
             $ms->save();
        }
        ///create log /////////////
        $content = 'tạo phiếu gửi bảo hành' ;
        \App\Models\Log::insertLogNew($content,$ms->id,'ms',$user->id);
        return response()->json(['msg'=>'Thêm gửi bảo hành thành công!','status'=>true]);
  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "ms_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ms = MaintainSent::find($id);
        if($ms)
        {
            $active_menu="ms_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainsent.index').'">Danh sách gửi bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu gửi bảo hành </li>';
            $ms_details = MaintainSentDetail::where('ms_id',$id)->get();   
            return view('backend.maintainsents.show',compact('breadcrumb','active_menu',  'ms' ,'ms_details' ));
    
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
        $func = "ms_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ms = MaintainSent::find($id);
        if($ms)
        {
            $active_menu="ms_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainsent.index').'">Danh sách gửi bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu gửi bảo hành </li>';
             $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
             $deliveries = \App\Models\User::where('role','delivery')
             ->where('status','active')->orderBy('full_name','ASC')->get();
             $bank_id = 0;
             $ship_amount = 0;
             if($ms->shiptrans_id)
             {
                 $shiptrans = FreeTransaction::where('id',$ms->shiptrans_id)->first();
                 $bank_id = $shiptrans->bank_id;
             }   
             return view('backend.maintainsents.edit',compact('breadcrumb','active_menu',  'bankaccounts' ,'deliveries','bank_id' ,'ms'));
    
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
        $func = "ms_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        $data = $request->importDoc;
        $details = $request->products;
        $ms = MaintainSent::find($id);
        if(!$ms)
        {
            return response()->json(['msg'=>'Không tìm thấy thông tin!','status'=>false]);
        }
        foreach ($details as $detail)
        {
            
            $pro_inventory = \App\Models\InventoryMaintenance::where('product_id',$detail['id'])->first();
                //lay chi tiet xuat kho cu de kiem tra cung ton kho va so xuat kho moi
            $wo_detail = \App\Models\MaintainSentDetail::where('ms_id',$ms->id)
                ->where('product_id',$detail['id'])->first();
            if(!$pro_inventory || $pro_inventory->quantity + $wo_detail->quantity < $detail['quantity'] ) //so sanh so luong ton kho hien tai va so luong phieu xuat kho cux nho hon so luong xuat kho moi
            {
                    return response()->json(['msg'=>'Số hàng xuất nhiều hơn trong kho a!' .$wo_detail->quantity ,'status'=>false]);
            }
            
            //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
            $wi_series = \DB::select("select * from maintain_sent_series where doc_type='ms' and ws_id=".$ms->id.' and is_sold = 0');
            
            $series = "";
            $i = 0;
            foreach($wi_series as $wi_seri)
            {
                $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
                $p_seri->is_sold = 0;
                $p_seri->save();
            }
              ////update series for each product
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select("select count(id) as tong from maintain_series where product_id = ".$detail['id'].' and is_sold = 0');
            $counts_n = $counts_n[0]->tong;
            if($count_n > $counts_n )
            {
                return response()->json(['msg'=>'Số lượng seri xuất lớn hơn tồn kho!','status'=>false]);
            }
            if($count_n !=0 && $count_n != $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số lượng series khác số số lượng nhập!','status'=>false]);
            }
            if($count_n > 0)
            {
                foreach ($series as $seri)
                {
                    $seri = trim($seri);
                    if ($seri == '')
                    continue;
                    $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) == 0)
                    {
                        foreach($wi_series as $wi_seri)
                        {
                            $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
                            $p_seri->is_sold = 1;
                            $p_seri->save();
                        }
                        return response()->json(['msg'=>'Số'.$seri.' không có trong kho!','status'=>false]);
                    }
                        
                } 
            }
            //so hang khong co seri ton kho
            $n_noseri = $pro_inventory->quantity + $wo_detail->quantity - $counts_n ;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
          
            if($sold_noseri -  $n_noseri > 0) //neu so hang ban ko seri > so hàng tonkho thi false
            {
                // return $n_noseri;
                return response()->json(['msg'=>'Số hàng không có seri '.$sold_noseri .' xuất lớn hơn trong kho '.$n_noseri .' !','status'=>false]);
            }

        }
        //
        $details = MaintainSentDetail::where('ms_id',$ms->id)->get();
        $flag = 0;
        foreach ($details as $detail)
        {
            if($detail->back > 0)
                $flag = 1;
        }
        if($flag == 1)
        {
            return response()->json(['msg'=>'Đã có sản phẩm trả về từ nhà cung cấp. Không thể điều chỉnh!','status'=>false]);
        }
        //remove detail
        $sql = "delete from maintain_sent_series where doc_type='ms' and ws_id=". $ms->id;
        \DB::select($sql);
        
        foreach ($details as $detail)
        {
            //xoa them detail invp 
            \App\Models\InvMaintainDetail::remove_product($ms->id,'ms',$detail->product_id);
            \App\Models\InventoryMaintenance::deletesendPro($detail,$ms->cost_extra);
        }
            ///delete ship invoice
        if($ms->shiptrans_id)
        {
            $fts = FreeTransaction::find($ms->shiptrans_id);
            if($fts)
            {
                $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                if($banktrans)
                    BankTransaction::removeBankTrans($banktrans);
                $fts->delete();
            }
        }
        //save new
        $data = $request->importDoc;
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
    
        ////average price///////////////////
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['shipcost'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        $ms->fill($data)->save();
        //save detail
        foreach ($details as $detail)
        {
            $product_detail['ms_id'] = $ms->id;
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            \App\Models\InventoryMaintenance::sendPro($product_detail['product_id'],$product_detail['quantity'],$data['cost_extra']);
            $product_detail['in_ids'] = '';
            $mdetail = MaintainSentDetail::create($product_detail);
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$detail['id'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
            $imd = \App\Models\InvMaintainDetail::ms_create($ms->id,$mdetail, 'ms',-1,$count_n>0?1:0); //1 la nhap
            $in_ids =\App\Models\InvMaintainDetail::sold_product($detail['id'],$sold_noseri );

            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
                    ->where('product_id',$detail['id'])->where('is_sold',0)->first();
               

                $data_seri['ws_id'] = $ms->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'ms';
                $data_seri['product_id'] = $detail['id'];
                $data_seri['in_id'] = $wi_seri->id;
                $main_sent = \App\Models\MaintainSentSeries::create($data_seri);
                $wi_seri->is_sold = 1;
                $wi_seri->out_id = $main_sent->id;
                $wi_seri->save();
                $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
                array_push($in_ids,$in_id);
                
            }
            $imd->in_ids  = json_encode($in_ids);
            $imd->save();
            $min_ids = \App\Models\InventoryMaintenance::getMainInSend($in_ids,$data['cost_extra']);
            $mdetail->in_ids  = json_encode($min_ids);
            $mdetail->save();


        }
        if($data['shipcost'] && $data['shipcost'] > 0)
        {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $ms->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
            $ms->save();
        }
        ///create log /////////////
       
        $content = 'Xóa phiếu gửi bảo hành' ;
        \App\Models\Log::insertLogNew($content,$ms->id,'ms',$user->id);

        return response()->json(['msg'=>'Thêm gửi bảo hành thành công!','status'=>true]);

         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "ms_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ms = MaintainSent::find($id);
        if($ms)
        {
            //remove detail
            $details = MaintainSentDetail::where('ms_id',$ms->id)->get();
            $flag = 0;
            foreach ($details as $detail)
            {
               if($detail->back > 0)
                $flag = 1;
            }
            if($flag == 1)
            {
                return back()->with('error','Đã có sản phẩm trả về từ nhà cung cấp. Không thể điều chỉnh!');
            }
             //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
            $wi_series = \DB::select("select * from maintain_sent_series where doc_type='ms' and ws_id=".$ms->id.' and is_sold = 0');
            $series = "";
            $i = 0;
            foreach($wi_series as $wi_seri)
            {
                $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
                $p_seri->is_sold = 0;
                $p_seri->save();
            }
             //remove detail
            $sql = "delete from maintain_sent_series where doc_type='ms' and ws_id=". $ms->id;
            \DB::select($sql);
        
            foreach ($details as $detail)
            {
                 //xoa them detail invp 
                \App\Models\InvMaintainDetail::remove_product($ms->id,'ms',$detail->product_id);
                \App\Models\InventoryMaintenance::deletesendPro($detail,$ms->cost_extra);

            }
              ///delete ship invoice
            if($ms->shiptrans_id)
            {
                $fts = FreeTransaction::find($ms->shiptrans_id);
                if($fts)
                {
                    $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                    if($banktrans)
                        BankTransaction::removeBankTrans($banktrans);
                    $fts->delete();
                }
            }


             ///create log /////////////
            $user = auth()->user();
              ///create log /////////////
            $content = 'Xóa phiếu gửi bảo hành' ;
            \App\Models\Log::insertLogNew($content,$ms->id,'ms',$user->id);
    
            $ms->delete(); 
            
            return redirect()->route('maintainsent.index')->with('success','Xóa thành công!'); 

        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'ms_id'=>'numeric|required',
        ]);
        $ms = MaintainSent::find($request->ms_id);
        $query = "(select id,photo, title,price_avg from products ) as p";
        $query1 = "(select product_id ,quantity from inventory_maintenances ) as np";
               
        $products = DB::table('maintain_sent_details')
        ->select ( 'maintain_sent_details.product_id','maintain_sent_details.quantity', 'p.title','p.photo','p.id','p.price_avg as price','np.quantity as stock_qty')
        ->where('ms_id',$request->ms_id)
        ->leftJoin(\DB::raw($query),'maintain_sent_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'maintain_sent_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            
            $oproductseris = \App\Models\MaintainSentSeries::where('product_id',$product->id)
             ->where('ws_id',$request->ms_id)->where('doc_type','ms')->get();
            $i = 0;
            $series = "";
            foreach ($oproductseris as $productseri)
            {
                if ($i > 0)
                    $series .= ',';
                $series .= $productseri->seri;
                $i ++;
            }
            $product->seri=$series;

            $iproductseris = \App\Models\MaintainSeries::where('product_id',$product->id)
              ->where('is_sold',0)->get();
            
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
        return response()->json(['msg'=>$products,'status'=>true]);

    }
}
