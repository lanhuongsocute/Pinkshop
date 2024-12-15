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
use App\Models\MaintainBack;
use App\Models\MaintainBackDetail;

class MaintainBackController extends Controller
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
        $func = "mba_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mb_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh trả bảo hành từ đối tác </li>';
        $maintainbacks=MaintainBack::orderBy('id','DESC')->paginate($this->pagesize);
        
        return view('backend.maintainbacks.index',compact('maintainbacks','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "mba_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mb_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
         <li class="breadcrumb-item active" aria-current="page"><a href="'.route("maintainback.index").'"> Danh trả bảo hành từ đối tác </a></li> 
        <li class="breadcrumb-item active" aria-current="page"> Thêm trả bảo hành từ đối tác</li>';
        $categories = \App\Models\Category::where('status','active')->orderBy('id','ASC')->get();
            
        $bankaccounts = Bankaccount::where('status','active')
            ->orderBy('id','ASC')->get();
        return view('backend.maintainbacks.create',compact( 'breadcrumb','active_menu','bankaccounts','categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "mba_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data = $request->importDoc;
        $details = $request->products;
       
        //
        if ($data['paid_amount'] < $data['shipcost'] )
        {
            return response()->json(['msg'=>'số tiền đã trả nhỏ hơn số tiền vận chuyển!','status'=>false]);
        }
        if ($data['final_amount'] < $data['shipcost'] )
        {
            return response()->json(['msg'=>'số tiền phải trả nhỏ hơn số tiền vận chuyển!','status'=>false]);
        }
        if($data['shipcost'] && $data['shipcost'] > 0)
        {
            $data['final_amount'] =  $data['final_amount']-$data['shipcost'];
            $data['paid_amount'] = $data['paid_amount'] - $data['shipcost'];
        }
      
        // return json_encode($data);
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        ////average price///////////////////
      
        // return  $details ;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
            $series = array();
            if(isset($detail['seri']) && $detail['seri']!= '')
            {
                $series =  explode(",",  $detail['seri']);
            }
          
            $count_n =0; //so series muốn xuất
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            if($count_n !=0 && $count_n != $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số lượng series khác số số lượng nhập!!','status'=>false]);
            }
            foreach ($series as $seri)
            {
                $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                if($seri == '')
                    continue;
                $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                
                $rows = \DB::select($query);
               
                if(count($rows) > 0)
                {
                    return response()->json(['msg'=>'Số serie' . $seri.' đã có trong kho!','status'=>false]);
                }
            } 
        }
        $cost_extra = ($data['shipcost'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        //tru tien cong no neu trong tai khoan cua doi tac con du tien
        $customer = \App\Models\User::find($data['supplier_id']);
        $totalbankpaid = $data['paid_amount'];
        $totalbudgetpaid = 0;
        if($customer->budget < 0 && $data['paid_amount'] < $data['final_amount'])
        {
            if($data['paid_amount']  - $customer->budget   >= $data['final_amount'])
            {
                $totalbudgetpaid = ( $data['final_amount'] - $data['paid_amount'] );
                $data['paid_amount']  =  $data['final_amount'];
                    
            }
            else
            {
                $data['paid_amount']  =  $data['paid_amount'] -  $customer->budget;
                $totalbudgetpaid  =  - $customer->budget;
            }
        }

        $mb = MaintainBack::create($data);
        //save detail
        foreach ($details as $detail)
        {
            $product_detail['mb_id'] = $mb->id;
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
             \App\Models\InventoryMaintenance::backPro($product_detail['product_id'],$product_detail['quantity'],$data['cost_extra'],$data['supplier_id']);
            $product_detail['in_ids'] = '';
            $mb_detail = MaintainBackDetail::create($product_detail);
            
            $count_n =0; //so series muốn xuất
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            \App\Models\InvMaintainDetail::mb_create($mb->id,$mb_detail,'mb', 1,$count_n>0?1:0) ;
          
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $data_seri['wm_id'] = $mb->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'mb';
                $data_seri['product_id'] = $detail['id'];
              
                // $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\MaintainSeries::create($data_seri);
               
            }
        }
        if($data['shipcost'] && $data['shipcost'] > 0)
        {
             $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
             $mb->shiptrans_id = $fts->id;
             BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
            
        }
        if($data['final_amount'] > 0 )
        {
              ///create SupTransaction
            $sps = SupTransaction::createSubTrans($mb->id,'mi',1,$data['final_amount'], $data['supplier_id']);
            $mb->suptrans_id = $sps->id;
            ///create paid transaction
            if($data['paid_amount']> 0)
            {
                $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$mb->id,'mi',$data['paid_amount']);
                SupTransaction::createSubTrans($bank_doc->id,'mi',-1, $data['paid_amount'], $data['supplier_id']); 
                $in_ids=array();
                $in_id = new \App\Models\Number();
                $in_id->id = $bank_doc->id;
                array_push($in_ids,$in_id);
                $mb->paidtrans_ids = json_encode($in_ids);
               
            }
           
        }
        $mb->save();
        ///create log /////////////
        $content = 'tạo phiếu nhận kết quả bảo hành từ đối tác' ;
        \App\Models\Log::insertLogNew($content,$mb->id,'mb',$user->id);
         
        return response()->json(['msg'=>'Thêm nhận bảo hành thành công!','status'=>true]);
  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "mba_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mb = MaintainBack::find($id);
        if($mb)
        {
            $active_menu="ms_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainback.index').'">Danh sách trả bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu trả bảo hành </li>';
            $mb_details = MaintainBackDetail::where('mb_id',$id)->get(); 
            foreach($mb_details as $mb_detail)
            {
                $series = "";
                $i = 0;
                $wo_seris = \DB::select("select seri from maintain_series where doc_type='mb' and wm_id =".$mb_detail->mb_id ." and product_id = ".$mb_detail->product_id );
                // dd("select seri from maintain_series where doc_type='mb' and wm_id =".$mb_detail->mb_id ." and product_id = ".$mb_detail->product_id );
                foreach($wo_seris as $wo_seri)
                {
                    if ($i > 0)
                        $series .= ",";
                    $series .= $wo_seri->seri;
                    $i ++;
                }
                $mb_detail->series = $series;
            }  
            return view('backend.maintainbacks.show',compact('breadcrumb','active_menu',  'mb' ,'mb_details' ));
    
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
        $func = "mba_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mb = MaintainBack::find($id);
        if($mb)
        {
            $active_menu="mb_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainback.index').'">Danh sách trả bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu trả bảo hành </li>';
            $categories = \App\Models\Category::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')
                ->orderBy('id','ASC')->get();
             $bank_id = 0;
             $ship_amount = 0;
             if($mb->shiptrans_id)
             {
                 $shiptrans = FreeTransaction::where('id',$mb->shiptrans_id)->first();
                 $bank_id = $shiptrans->bank_id;
             }  
             if($mb->paidtrans_ids)
             {
                 $id_ins = json_decode($mb->paidtrans_ids); 
                 $id_in = $id_ins[0];
                 $paidtrans = BankTransaction::where('id',$id_in->id)->first();
                 $bank_id = $paidtrans->bank_id;
                 
             }   
             return view('backend.maintainbacks.edit',compact('breadcrumb','active_menu',  'bankaccounts' ,'categories','bank_id' ,'mb'));
    
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
        $func = "mba_edit";
        if(!$this->check_function($func))
        {
            return response()->json(['msg'=>'Không đủ quyền!','status'=>false]);
          
        }
        $data = $request->importDoc;
        $details = $request->products;
        //
        if ($data['paid_amount'] < $data['shipcost'] )
        {
            return response()->json(['msg'=>'số tiền đã trả nhỏ hơn số tiền vận chuyển!','status'=>false]);
        }
        if ($data['final_amount'] < $data['shipcost'] )
        {
            return response()->json(['msg'=>'số tiền phải trả nhỏ hơn số tiền vận chuyển!','status'=>false]);
        }
        if($data['shipcost'] && $data['shipcost'] > 0)
        {
            $data['final_amount'] =  $data['final_amount']-$data['shipcost'];
            $data['paid_amount'] = $data['paid_amount'] - $data['shipcost'];
        }
        $mb = MaintainBack::find($id);
        if($mb)
        {
            $mb_series = \App\Models\MaintainSeries::where('wm_id',$mb->id)->where('doc_type','mb')
                ->where('is_sold',0)->get();
            foreach ($mb_series as $mb_seri)
            {
                $mb_seri->is_sold = 1;
                $mb_seri->save();
            }
            foreach ($details as $detail)
            {
                $series = array();
                if(isset($detail['seri']))
                {
                    $series =  explode(",",  $detail['seri']);
                }
                
                $count_n =0; //so series muốn xuất
                if($detail['seri']!= '')
                {
                    $count_n =count($series );
                }
                if($count_n !=0 && $count_n != $detail['quantity'] )
                {
                    return response()->json(['msg'=>'Số lượng series khác số số lượng nhập! !','status'=>false]);
                //    return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
                }
                foreach ($series as $seri)
                {
                    $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                    if($seri == '')
                        continue;
                    $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) > 0)
                    {
                        foreach ($mb_series as $mb_seri)
                        {
                            $mb_seri->is_sold = 0;
                            $mb_seri->save();
                        }
                        return response()->json(['msg'=>'Số serie' . $seri.' đã có trong kho!','status'=>false]);
             
                      //  return back()->with('error','Số serie' . $seri.' đã có trong kho!')->withInput();;
                    }
                } 
            }
            //remove detail
            $mb_details = MaintainBackDetail::where('mb_id',$id)->get();   
            foreach ($mb_details as $detail)
            {
                \App\Models\InventoryMaintenance::deletebackPro($detail,$mb->cost_extra);
                \App\Models\InvMaintainDetail::remove_product($mb->id,'mb',$detail->product_id);
            }
            $sql = "delete from maintain_series where doc_type='mb' and wm_id=". $mb->id;
            \DB::select($sql);
           
                ///delete sup trans 1 for importing
            if($mb->final_amount > 0)
                SupTransaction::removeSubTrans($mb->suptrans_id,'mir',$mb->id);
            ///
            ///delete paid transaction
            if($mb->paidtrans_ids)
            {
                $in_ids = json_decode($mb->paidtrans_ids);
                foreach ($in_ids as $in_id)
                {
                    $bank_doc = BankTransaction::find( $in_id->id );
                    if($bank_doc)
                    {
                        $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                        if($suptrans)
                            SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                        BankTransaction::removeBankTrans($bank_doc);
                    }
                }
            }
              ///delete ship invoice
            if($mb->shiptrans_id)
            {
                $fts = FreeTransaction::find($mb->shiptrans_id);
                if($fts)
                {
                    $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                    if($banktrans)
                        BankTransaction::removeBankTrans($banktrans);
                    $fts->delete();
                }
            }
            //save new
       
            // return $data;
            
            $user = auth()->user();
            $data['vendor_id'] = $user->id;
        
            ////average price///////////////////
        
            $count_item = 0;
            foreach ($details as $detail)
            {
                $count_item += $detail['quantity'];
            }
            $cost_extra = ($data['shipcost'])/ $count_item ;
            $data['cost_extra'] = $cost_extra ;
            //tru tien cong no neu trong tai khoan cua doi tac con du tien
            $customer = \App\Models\User::find($data['supplier_id']);
            $totalbankpaid = $data['paid_amount'];
            $totalbudgetpaid = 0;
            if($customer->budget < 0 && $data['paid_amount'] < $data['final_amount'])
            {
                if($data['paid_amount']  - $customer->budget   >= $data['final_amount'])
                {
                    $totalbudgetpaid = ( $data['final_amount'] - $data['paid_amount'] );
                    $data['paid_amount']  =  $data['final_amount'];
                        
                }
                else
                {
                    $data['paid_amount']  =  $data['paid_amount'] -  $customer->budget;
                    $totalbudgetpaid  =  - $customer->budget;
                }
            }

            $mb->fill($data)->save();
            //save detail
            foreach ($details as $detail)
            {
                $product_detail['mb_id'] = $mb->id;
                $product_detail['product_id']= $detail['id'];
                $product_detail['quantity'] = $detail['quantity'];
                $product_detail['price'] = $detail['price'];
                $in_ids = \App\Models\InventoryMaintenance::backPro($product_detail['product_id'],$product_detail['quantity'],$data['cost_extra'],$data['supplier_id']);
                $product_detail['in_ids'] = json_encode($in_ids);
                $mb_detail = MaintainBackDetail::create($product_detail);
                $count_n =0; //so series muốn xuất
                if($detail['seri']!= '')
                {
                    $count_n =count($series );
                }
                \App\Models\InvMaintainDetail::mb_create($mb->id, $mb_detail,'mb', 1,$count_n>0?1:0) ;
                foreach ($series as $seri)
                {
                    $seri = trim ($seri);
                    if($seri == '')
                            continue;
                    $data_seri['wm_id'] = $mb->id;
                    $data_seri['seri'] = $seri;
                    $data_seri['doc_type'] = 'mb';
                    $data_seri['product_id'] = $detail['id'];
                
                    // $data_seri['in_id'] = $wi_seri->id;
                    $wd_seri = \App\Models\MaintainSeries::create($data_seri);
                   
                }
            }
            if($data['shipcost'] && $data['shipcost'] > 0)
            {
                $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
                $mb->shiptrans_id = $fts->id;
                BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
              
            }
            if($data['final_amount'] > 0 )
            {
                ///create SupTransaction
                $sps = SupTransaction::createSubTrans($mb->id,'mi',1,$data['final_amount'] , $data['supplier_id']);
                $mb->suptrans_id = $sps->id;
                ///create paid transaction
                if($data['paid_amount']> 0)
                {
                    $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$mb->id,'mi',$data['paid_amount']);
                    SupTransaction::createSubTrans($bank_doc->id,'mi',-1, $data['paid_amount'], $data['supplier_id']); 
                    $in_ids=array();
                    $in_id = new \App\Models\Number();
                    $in_id->id = $bank_doc->id;
                    array_push($in_ids,$in_id);
                    $mb->paidtrans_ids = json_encode($in_ids);
                  
                }
               
            }
            $mb->save();
            ///create log /////////////
            $content = 'update MaintainBack id: '.$mb->id ;
            \App\Models\Log::insertLog($content,$user->id);
            return response()->json(['msg'=>'Thêm gửi bảo hành thành công!','status'=>true]);
  
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "mba_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mb = MaintainBack::find($id);
        if($mb)
        {
            $details = MaintainBackDetail::where('mb_id',$mb->id)->get();
            
            //remove detail
            $sql = "delete from maintain_series where doc_type='mb' and wm_id=". $mb->id;
            \DB::select($sql);
           
            foreach ($details as $detail)
            {
                \App\Models\InventoryMaintenance::deletebackPro($detail,$mb->cost_extra);
                \App\Models\InvMaintainDetail::remove_product($mb->id,'mb',$detail->product_id);
         
            }
                ///delete sup trans 1 for importing
            if($mb->final_amount > 0)
                SupTransaction::removeSubTrans($mb->suptrans_id,'mir',$mb->id);
            ///
            ///delete paid transaction
            if($mb->paidtrans_ids)
            {
                $in_ids = json_decode($mb->paidtrans_ids);
                foreach ($in_ids as $in_id)
                {
                    $bank_doc = BankTransaction::find( $in_id->id );
                    if($bank_doc)
                    {
                        $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                        if($suptrans)
                            SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                        BankTransaction::removeBankTrans($bank_doc);
                    }
                }
            }
              ///delete ship invoice
            if($mb->shiptrans_id)
            {
                $fts = FreeTransaction::find($mb->shiptrans_id);
                if($fts)
                {
                    $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                    if($banktrans)
                        BankTransaction::removeBankTrans($banktrans);
                    $fts->delete();
                }
            }
        
            ///create log /////////////
            $user= auth()->user();
            $content = 'delete MaintainBack id: '.$mb->id ;
            \App\Models\Log::insertLog($content,$user->id);
            $mb->delete();
            return redirect()->route('maintainback.index')->with('success','Xóa thành công!'); 

        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'mb_id'=>'numeric|required',
        ]);
        // $mb = MaintainBack::find($request->mb_id);
        $query = "(select id,photo, title,price_avg from products ) as p";
        $query1 = "(select product_id ,quantity from inventory_maintenances ) as np";
               
        $products = DB::table('maintain_back_details')
        ->select ( 'maintain_back_details.product_id','maintain_back_details.quantity', 'p.title','p.photo','p.id','maintain_back_details.price as price','np.quantity as stock_qty')
        ->where('mb_id',$request->mb_id)
        ->leftJoin(\DB::raw($query),'maintain_back_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'maintain_back_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            
            $oproductseris = \App\Models\MaintainSeries::where('product_id',$product->id)
             ->where('wm_id',$request->mb_id)->where('doc_type','mb')->get();
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

            

        }
        return response()->json(['msg'=>$products,'status'=>true]);

    }

    public function maintainbackPaid($id)
    {
        // return $id;
        $func = "mba_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $mb = MaintainBack::find($id);
         
        if( $mb)
        {
             $bankaccounts = Bankaccount::where('status','active')->get();
             $active_menu="wo_list";
             
             $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
             <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainback.index').'">Ds bán hàng</a></li>
             <li class="breadcrumb-item active" aria-current="page">  </li>';
             return view('backend.maintainbacks.paid',compact('mb','breadcrumb','bankaccounts','active_menu'));
             
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function maintainbackSavePaid(Request $request)
    {
        $func = "mba_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'numeric|required',
        ]);
        $data = $request->all();
        $mb = MaintainBack::find($data['id']);
        $user = auth()->user();
       
        if( $mb)
        {
             ///create paid transaction
             $bankaccount = Bankaccount::find($data['bank_id']);
            if($data['paid_amount'] <=0  )
            {
                return back()->with('error','Số tiền trả không hợp lệ!');
            }
            if($bankaccount->total < $data['paid_amount']  )
            {
                return back()->with('error','Số tiền trong tài khoản không đủ!');
            }
            $mb->paid_amount += $data['paid_amount'];
            if($mb->paid_amount > $mb->final_amount)
            {
                return back()->with('error','Số tiền trả nhiều hơn phải trả!');
            }
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'], -1,$mb->id,'mi',$data['paid_amount']);
            SupTransaction::createSubTrans($bank_doc->id,'mi', -1, $data['paid_amount'], $mb->supplier_id); 
            
            //save ids in paid_ids
            $in_ids = array();
            if($mb->paidtrans_ids )
            {
                $in_ids = json_decode($mb->paidtrans_ids);
            }
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $mb->paidtrans_ids = json_encode($in_ids);
              
            $mb->save();
            ///create log /////////////
            $user = auth()->user();
            $content = 'paid money for maintain back: '.$mb->id .' total: '.$data['paid_amount'];
            \App\Models\Log::insertLog($content,$user->id);
            
            return redirect()->route('maintainback.index')->with('success','Đã thêm thanh toán cho phiếu trả bảo hành!');
            
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
