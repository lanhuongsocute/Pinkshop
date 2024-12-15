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
use App\Models\Warehousetransfer;

class WarehousetransferController extends Controller
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
        $func = "wht_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wi_trans";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho </li>';
        $warehousetrans=Warehousetransfer::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.warehousetransfers.index',compact('warehousetrans','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "wht_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wi_trans";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetransfer.index').'">Ds chuyển kho</a></li>
        <li class="breadcrumb-item">Thêm chuyển kho</li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
        $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
        
        $vendors = User:: where(function($query)  
        {
            $query->where('role', 'vendor')
                  ->orWhere('role', 'manager');
        })->where('status','active')->get();
         
        $user = auth()->user();
        return view('backend.warehousetransfers.create',compact('user','bankaccounts','warehouses','vendors','deliveries','breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "wht_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $user = auth()->user();
        $data = $request->importDoc;
        if($data['wh_id1'] == $data['wh_id2'])
        {
            return response()->json(['msg'=>'Hai kho phải khác nhau!','status'=>false]);
        }
        ////average price///////////////////
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select("select count(id) as tong from warehousein_detail_series where product_id = ".$detail['id'].' and is_sold = 0');
            $counts_n = $counts_n[0]->tong;
            if($count_n > $counts_n )
            {
                return response()->json(['msg'=>'Số seri lớn hơn có trong kho','status'=>false]);
            }
            if($count_n > 0 && $count_n !=  $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số seri khác số lượng xuất','status'=>false]);
            }
            if($count_n > 0)
            {
                foreach ($series as $seri)
                {
                    $seri = trim($seri);
                    if ($seri == '')
                        continue;
                    $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) == 0)
                    {
                        return response()->json(['msg'=>'Số seri không có trong kho!','status'=>false]);
                        
                    }
                } 
            }
       
            //so hang khong co seri ton kho
            $pro_inventory = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id1'])
                ->first();
            $n_noseri = $pro_inventory->quantity - $counts_n ;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
            if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
            {
                return response()->json(['msg'=>'Số lượng sp không seri lớn hơn số sp không seri trong kho!','status'=>false]);
            }
        }

        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        if($data['shipcost'])
        {
            $cost_extra = ($data['shipcost'])/ $count_item ;
            $data['cost_extra'] = $cost_extra ;
        }
        else
        {
            $data['cost_extra'] = 0;
        }
        $data['author_id'] = $user->id;
        $wf = Warehousetransfer::create($data);

        foreach ($details as $detail)
        {
            $product_detail['wo_id'] = $wf->id;
            $product_detail['wh_id'] = $data['wh_id1'];
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            //tim pre balance
            $inv = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id1'])
                ->first();
            if( $inv)
                $product_detail['prebalance'] =$inv->quantity;
            else
                $product_detail['prebalance'] = 0;
             //save expired days
            $product = Product::find($detail['id']);
            $start_date = date('Y-m-d H:i:s');
            if($product->expired)
            {
                $strday = '+' . $product->expired*30 .' days';
                $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $in_ids=array();

            // return ($in_ids);
            //decrease stock
            ////update series for each product
            $series =  explode(",",  $detail['seri']);
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            $counts_n = \DB::select ("select count(id) as tong from warehousein_detail_series where product_id = ".$detail['id'].' and wh_id='.$data['wh_id1'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
            // Inventory::subProductInv($product_detail['product_id'], $data['wh_id'], $detail['quantity'], $product_detail['price'], $cost_extra);
            Inventory::transfer($wf->id,$detail['id'], $data['wh_id1'],$data['wh_id2'],$detail['quantity'], $detail['price'],$data['cost_extra']);
            $in_ids = Inventory::transfer_noseri($product_detail['product_id'],$sold_noseri, $data['wh_id1']);
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                //tim seri trong kho 1
                $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                    ->where('product_id',$detail['id'])->where('wh_id',$data['wh_id1'])->where('is_sold',0)->first();
                //tao out seri trong kho 1
                \App\Models\WarehouseoutDetailSeries::create_from_in_seri($wi_seri,$wf->id,'ti');
                $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)->where('wh_id',$data['wh_id1'])
                    ->where('product_id',$wi_seri->product_id)->first();
                //cap nhat detail in của seri va lay id detail in  
                $in_id = Inventory::transferDetailInsSeries($product_detail['product_id'], $data['wh_id1'],$detail_in);
                array_push($in_ids, $in_id);
                //tao seri in cho kho 2
                \App\Models\WarehouseinDetailSeries::create_from_in_seri($wi_seri,$wf->id,'ti',$data['wh_id2']) ;
               
            }
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='ti'; //loai xuat la phieu xuat ban hang
            //tao detail xuat kho cho kho 1
            \App\Models\WarehouseoutDetail::c_create($product_detail);
            $product_detail['doc_id'] = $wf->id;
            $product_detail['wh_id'] = $data['wh_id2'];
            $product_detail['is_seri'] = $count_n>0?1:0  ;
            //tim prebalance cho kho 2 trước khi nhập
            $inv = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id2'])
                ->first();
            if($inv)
                $product_detail['prebalance'] =$inv->quantity - $detail['quantity'];
            else
                $product_detail['prebalance'] = 0;
            //tao phiếu nhập cho kho 2
            \App\Models\WarehouseInDetail::create($product_detail);
        }
 
         ///create ship invocie ///////////
       if($data['shipcost'] > 0)
       {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $wf->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
            $wf->save();
       }
        ///create log /////////////
        // $content = 'create warehouse transfer id: '.$wf->id.' warehouse 1: '.$data['wh_id1'].' warehouse 2: '.$data['wh_id2'];
        // \App\Models\Log::insertLog($content,$user->id);
        $kho1 = \App\Models\Warehouse::find($data['wh_id1'])->title;
        $kho2 = \App\Models\Warehouse::find($data['wh_id2'])->title;
        
        $content = 'tạo phiếu chuyển kho từ '.$kho1.' sang kho '.$kho2 ;
        \App\Models\Log::insertLogNew($content,$wf->id,'ti',$user->id);
     
        return response()->json(['msg'=>'Thêm thành công!','status'=>true]);
    }

    /**
     * Display the speci1800fied resource.
     */
    public function show(string $id)
    {
        $func = "wht_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $warehousetrans = Warehousetransfer::find($id);
        if($warehousetrans)
        {
            $active_menu="wi_trans";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetransfer.index').'">DS chuyển kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            $wi_details = WarehouseInDetail::where('doc_id',$id)->where('doc_type','ti')->get();
            foreach($wi_details as $ic_detail)
            {
                $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$ic_detail->product_id)
                    ->where('wi_id',$warehousetrans->id)->where('doc_type','ti')->where('wh_id',$ic_detail->wh_id)->get();
                
                $series = "";
                $i = 0;
                foreach ($iproductseris as $productseri)
                {
                    if($i > 0)
                        $series .=", ";
                    $series .= $productseri->seri;
                    $i ++;
                }
                $ic_detail->series=$series;
  
            }
            return view('backend.warehousetransfers.show',compact('breadcrumb','warehousetrans','active_menu','wi_details'));
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
        $func = "wht_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // if(!$this->checkRole(1))
        // {
        //     return redirect()->route('unauthorized');
        // }
        $warehousetrans = Warehousetransfer::find($id);
        if($warehousetrans  )
        {
            $wh1 = Warehouse::find( $warehousetrans->wh_id1);
            $wh2 = Warehouse::find( $warehousetrans->wh_id2);
            $vendor1 = User::find( $warehousetrans->vendor_id1);
            $vendor2 = User::find( $warehousetrans->vendor_id2);
            if($wh1 == null || $wh2 == null || $vendor1 == null || $vendor2 == null 
                ||$wh1->status =="inactive"||$wh2->status =="inactive"
                ||$vendor1->status =="inactive"||$vendor2->status =="inactive")
            {
                return back()->with('error','Đã có những dữ liệu liên quan không thể chỉnh sửa!');
            }
            $active_menu="wi_trans";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetransfer.index').'">Danh sách chuyển kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu chuyển kho </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
            $vendors = User:: where(function($query)  
            {
                $query->where('role', 'vendor')
                      ->orWhere('role', 'manager');
            })
            ->where(function($query1)  use($warehousetrans)
            {
                $query1->where('status','active')
                      ->orWhere('id',$warehousetrans->vendor_id1 )
                      ->orWhere('id',$warehousetrans->vendor_id2 )
                      ->orWhere('id',$warehousetrans->author_id )
                      ;
            })
            ->get();
            $ship_trans = null;
            $bank_id = 0;
            $ship_amount = 0;
            if($warehousetrans->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehousetrans->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }   
            $user = auth()->user();
            
            return view('backend.warehousetransfers.edit',compact('breadcrumb','warehousetrans','active_menu','warehouses','bankaccounts','user','bank_id','ship_amount','deliveries','vendors'));
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
        $func = "wht_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $warehousetrans = Warehousetransfer::find($id);
        $data = $request->importDoc;
         ////average price///////////////////
         $details = $request->products;
         $count_item = 0;
         foreach ($details as $detail)
         {
             $count_item += $detail['quantity'];
         }
         if($data['shipcost'])
         {
             $cost_extra = ($data['shipcost'])/ $count_item ;
             $data['cost_extra'] = $cost_extra ;
            
         }
         else
         {
             $data['cost_extra'] = 0;
         }
        if($warehousetrans)
        {
            $flag = 0;
            $detailpros = WarehouseInDetail::where('doc_id',$id)->where('doc_type','ti')->get();
            foreach($detailpros as $dtpro)
            {
                if($dtpro->qty_sold > 0)
                    $flag = 1;
            }
            if($flag == 1)
            {
                return response()->json(['msg'=>'Đã xuất kho hàng hóa trong phiếu nhập!','status'=>false]);
            }
            $user = auth()->user();
            $data['author_id'] = $user->id;
            foreach($detailpros as $dtpro)
            {
                WarehouseInDetail::deleteDetailTransfer($dtpro,$warehousetrans->cost_extra,$warehousetrans->wh_id1,$warehousetrans->wh_id2);
            }
            if($warehousetrans->shiptrans_id)
            {
                
                $fts = FreeTransaction::find($warehousetrans->shiptrans_id);
                if($fts)
                {
                    $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                    if($banktrans)
                        BankTransaction::removeBankTrans($banktrans);
                    $fts->delete();
                }
            }
            //save the new 
            $warehousetrans->fill($data)->save();
            foreach ($details as $detail)
            {
                Inventory::transfer($warehousetrans->id,$detail['id'], $data['wh_id1'],$data['wh_id2'],$detail['quantity'], $detail['price'],$data['cost_extra']);
                 
            }
             ///create ship invocie ///////////
           if($data['shipcost'] > 0)
           {
                $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
                $warehousetrans->shiptrans_id = $fts->id;
                BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
                $warehousetrans->save();
           }
            ///create log /////////////
            $content = 'update warehouse transfer id: '.$warehousetrans->id.' warehouse 1: '.$data['wh_id1'].' warehouse 2: '.$data['wh_id2'];
            \App\Models\Log::insertLog($content,$user->id);
            return response()->json(['msg'=>'Cập nhật thành công!','status'=>true]);
        }
        else
            return response()->json(['msg'=>'Không tìm thấy!','status'=>false]);
         
    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'wti_id'=>'numeric|required',
        ]);
        $wo = Warehousetransfer::find($request->wti_id);
        $query = "(select id,photo, title from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id1.") as np";
               
        $products = DB::table('warehouse_in_details')
        ->select ('warehouse_in_details.price','warehouse_in_details.product_id','warehouse_in_details.quantity', 'p.title','p.photo','p.id','np.quantity as stock_qty')
        ->where('doc_id',$request->wti_id)->where('doc_type','ti')
        ->leftJoin(\DB::raw($query),'warehouse_in_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'warehouse_in_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        
        return response()->json(['msg'=>$products,'status'=>true]);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "wht_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $warehousetrans = Warehousetransfer::find($id);
        
        if($warehousetrans)
        {
            $flag = 0;
            $detailpros = WarehouseInDetail::where('doc_id',$id)->where('doc_type','ti')->get();
            foreach($detailpros as $dtpro)
            {
                if($dtpro->qty_sold > 0)
                    $flag = 1;
            }
            if($flag == 1)
            {
                return back()->with('error','Đã có sản phẩm xuất kho, không thể xóa!');
            }
            $user = auth()->user();
            $data['author_id'] = $user->id;
            foreach($detailpros as $dtpro)
            {
                WarehouseInDetail::deleteDetailTransfer($dtpro,$warehousetrans->cost_extra,$warehousetrans->wh_id1,$warehousetrans->wh_id2);
            }
            if($warehousetrans->shiptrans_id)
            {
                
                $fts = FreeTransaction::find($warehousetrans->shiptrans_id);
                if($fts)
                {
                    $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                    if($banktrans)
                        BankTransaction::removeBankTrans($banktrans);
                    $fts->delete();
                }
            }
            $content = 'delete warehouse transfer stock: '.$warehousetrans->wh_id1 .' to stock: '.$warehousetrans->wh_id2;
            \App\Models\Log::insertLog($content,$user->id);
             $warehousetrans->delete();
             return redirect()->route('warehousetransfer.index')->with('success','Xóa thành công!');
  
           
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu!');
         
        }
        
    }
    public function deliveryPrint($id)
    {
        $warehousetrans = Warehousetransfer::find($id);
        if($warehousetrans  )
        {
            $active_menu="wi_trans";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousetransfer.index').'">Danh sách chuyển kho</a></li>
            <li class="breadcrumb-item active" aria-current="page">phiếu gửi hàng </li>';
           
            return view('backend.warehousetransfers.deprint',compact('breadcrumb','warehousetrans','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
