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
use App\Models\MaintenanceIn;
class MaintainInController extends Controller
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
        $func = "min_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mainin_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách nhận bảo hành </li>';
        $maintainins=MaintenanceIn::orderBy('id','DESC')->paginate($this->pagesize);
        $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
           
        return view('backend.maintainins.index',compact('maintainins','breadcrumb','active_menu','bankaccounts'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "min_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mainin_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainin.index').'">Danh sách nhận bảo hành</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm nhận bảo hành </li>';
         $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
         $categories = \App\Models\Category::where('status','active')->orderBy('id','ASC')->get();
        return view('backend.maintainins.create',compact('breadcrumb','active_menu',  'bankaccounts' ,'categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function maintaininViewFinish($id)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $maintainin = MaintenanceIn::find($id);
        if($maintainin && $maintainin->status == 'returned')
        {
            $active_menu="mainin_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainin.index').'">Danh sách nhận bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu nhận bảo hành </li>';
             $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
              $bank_id = 0;
             $ship_amount = 0;
             

            $wd_series = \DB::select("select * from maintain_series where wm_id=".$maintainin->id.' and doc_type = "mi"');
            $series = "";
            $i = 0;
            foreach($wd_series as $wd_seri)
            {
                if($i > 0)
                    $series .= ',';
                $series .= $wd_seri->seri;
                $i ++;
            }
             return view('backend.maintainins.return',compact('breadcrumb','active_menu', 'series',  'bankaccounts' ,'bank_id','ship_amount','maintainin'));
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu hoặc đã hoàn khách!');
        }
    }
    public function maintaininSaveFinish(Request $request)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        $this->validate($request,[
            'mi_id'=>'numeric|required',
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'final_amount'=>'numeric|required',
            'paid_amount'=>'string|nullable',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $mi = MaintenanceIn::find($request->mi_id);
        if(!$mi || $mi->status !='returned')
        {
            return back()->with('error','Cần phải điều chỉnh trạng thái sang returned mới hoàn trả cho khách được!');
        }
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        if(!$minventory )
            return back()->with('error','Không tìm thấy sản phẩm!');
        if($minventory->quantity < $data['quantity'] )
            return back()->with('error','Sản phẩm trong kho không đủ');
        
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
            return back()->with('error','Số hàng không có series '.$sold_noseri.' trong phiếu xuất nhiều hơn trong kho'.$n_noseri.'!')->withInput();;
        }

        $mi->final_amount = $data['final_amount'];
        $mi->shipback = $data['shipback'];
        $mi->maincost = $data['maincost'];
        $mi->paid_amount = $request->paid_amount;
        $mi->status = 'finished';
        $mi->save();
        ///sub inventory
            //add inventory maintenance
        \App\Models\InventoryMaintenance::removePro($mi->product_id,$mi->quantity);
        //tao detail out
          //save maintaintoproperty doc
       $user = auth()->user();
       
       //save propertytodestroy doc
       
       $imd= \App\Models\InvMaintainDetail::c_create($mi,'mr',-1,$count_n>0?1:0); //1 la nhap
       $in_ids =\App\Models\InvMaintainDetail::sold_product($data['product_id'],$sold_noseri);
  
       foreach ($series as $seri)
       {
           $seri = trim ($seri);
           if($seri == '')
                   continue;
           $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
               ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
           $data_seri['doc_id'] = $mi->id;
           $data_seri['seri'] = $seri;
           $data_seri['doc_type'] = 'mr';
           $data_seri['product_id'] = $data['product_id'];
           $data_seri['in_id'] = $wi_seri->id;
           $wd_seri = \App\Models\MaintainReturnSeries::create($data_seri);
           $wi_seri->out_id = $wd_seri->id;
           $wi_seri->is_sold = 1;
           $wi_seri->save();
           $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
           array_push($in_ids,$in_id);
       }
       $imd->in_ids = json_encode($in_ids);
       $imd->save();
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($mi->id,'mo',-1,$mi->final_amount, $mi->customer_id);
        $mi->suptrans_id = $sps->id;
        ///create paid transaction
        $user = auth()->user();
        if($request->paid_amount > 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$request->bank_id,1,$mi->id,'mo',$request->paid_amount);
            SupTransaction::createSubTrans($bank_doc->id,'fi',1,$request->paid_amount, $mi->customer_id); 
            $in_ids=array();
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $mi->paidtrans_ids = json_encode($in_ids);
        }
        $mi->save();

        
            ///create log /////////////
        $content = 'lưu phiếu trả bảo hành';
        \App\Models\Log::insertLogNew($content,$mi->id,'mi',$user->id);
        return redirect()->route('maintainin.index')->with('success','Lưu phiếu hoàn thành công!');
     
         
    }
    public function maintaininSaveFinish2(Request $request)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'string|nullable',
            'bank_id'=>'numeric|required',
        ]);
        $id = $request->id;
       
        $mi = MaintenanceIn::find($id);
        if($mi && $mi->status =='returned')
        {
            $mi->paid_amount = $request->paid_amount;
            $mi->status = 'finished';
            $mi->save();
            ///sub inventory
             //add inventory maintenance
            \App\Models\InventoryMaintenance::removePro($mi->product_id,$mi->quantity);
            //tao detail out

            ///create SupTransaction
            $sps = SupTransaction::createSubTrans($mi->id,'mo',-1,$mi->final_amount, $mi->customer_id);
            $mi->suptrans_id = $sps->id;
            ///create paid transaction
            $user = auth()->user();
            if($request->paid_amount > 0)
            {
                $bank_doc = BankTransaction::insertBankTrans($user->id,$request->bank_id,1,$mi->id,'mo',$request->paid_amount);
                SupTransaction::createSubTrans($bank_doc->id,'fi',1,$request->paid_amount, $mi->customer_id); 
                $in_ids=array();
                $in_id = new \App\Models\Number();
                $in_id->id = $bank_doc->id;
                array_push($in_ids,$in_id);
                $mi->paidtrans_ids = json_encode($in_ids);
            }
            $mi->save();

            
              ///create log /////////////
            $content = 'Lưu kết quả phiếu trả bảo hành';
            \App\Models\Log::insertLogNew($content,$mi->id,'mir',$user->id);
     
            // \App\Models\Log::insertLog($content,$user->id);
            return response()->json(['msg'=>'lưu thành công','status'=>true]);
     
        }
        else
        {
            return response()->json(['msg'=>'không tìm thấy thông tin','status'=>false]);
        }
    }

    public function maintaininSaveReturn(Request $request)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'comment'=>'string|nullable',
            'maincost'=>'numeric|nullable',
            'result'=>'string|required',
            
        ]);
        $id = $request->id;
        $comment = $request->comment;
        $maincost = $request->maincost;
        $mi = MaintenanceIn::find($id);
        if($mi && $mi->status != 'finished')
        {
            $mi->comment = $comment;
            $mi->final_amount = $mi->final_amount - $mi->maincost + $maincost;
            $mi->maincost = $maincost  ;
            $mi->final_amount =  $mi->maincost ;
            $mi->result = $request->result;
            $mi->status = 'returned';
            $mi->save();
            $user = auth()->user();

              ///create log /////////////
              $content = 'Lưu kết quả phiếu trả bảo hành';
              \App\Models\Log::insertLogNew($content,$mi->id,'mir',$user->id);
       
            return response()->json(['msg'=>'lưu thành công','status'=>true]);
     
        }
        else
        {
            return response()->json(['msg'=>'không tìm thấy thông tin','status'=>false]);
        }

    }
    public function store(Request $request)
    {
        $func = "min_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'customer_id'=>'numeric|required',
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'description'=>'string|nullable',
            'bank_id'=>'numeric|required',
            'shipcost'=>'numeric|nullable',
            
        ]);
        $data= $request->all();
       
        // return $data;
        $data['status']="received";
        $data['result']="pending";

        // $data['quantity']=$this->absnumber($data['quantity']);
        // $data['shipcost']=$this->absnumber($data['shipcost']);
        $product = \App\Models\Product::find($data['product_id']);
        if(!$product)
        {
            return back()->with('error','Không tìm thấy sản phẩm!');
        }
        if($data['customer_id'] == 0)
        {
            return back()->withInput()->with('error','Hãy chọn khách hàng!');
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
        if($count_n !=0 && $count_n != $data['quantity'] )
        {
            return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
        }
        foreach ($series as $seri)
        {
            $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
            if($seri == '')
                continue;
            $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
            $rows = \DB::select($query);
            if(count($rows) > 0)
            {
                return back()->with('error','Số serie' . $seri.' đã có trong kho!')->withInput();;
            }
        } 
        ///
        if($data['shipcost'] && $data['shipcost']  > 0)
        {
            $data['maincost'] = $data['shipcost'];
        }
        else
        {
            $data['maincost'] = 0;
        }
        $data['final_amount'] = 0;
        $data['paid_amount'] = 0;
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        if($data['shipcost'] && $bank->total < $data['shipcost'])
        {
            return back()->with('error','Không đủ tiền trả phí vận chuyển!');
        }
        $maintainin = MaintenanceIn::create($data);

        if($maintainin){

            //add inventory maintenance
             \App\Models\InventoryMaintenance::addPro($data['product_id'],$data['quantity']);
             $maintainin->price = 0;
             \App\Models\InvMaintainDetail::c_create($maintainin,'mi',1,$count_n>0?1:0); //1 la nhap
          
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $data_seri['wm_id'] = $maintainin->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'mi';
                $data_seri['product_id'] = $data['product_id'];
                // $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\MaintainSeries::create($data_seri);
               
            }
            ////////
            if($data['shipcost'] && $data['shipcost'] > 0)
            {
                 $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
                 $maintainin->shiptrans_id = $fts->id;
                 BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
            }
            ///create log /////////////
            $content = 'lưu phiếu nhận bảo hành' ;
            \App\Models\Log::insertLogNew($content,$maintainin->id,'mi',$user->id);
         
            return redirect()->route('maintainin.index')->with('success','thành công!');
        }
        else
        {
            return back()->with('error','Lỗi xãy ra!');
        }    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "min_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $maintainin = MaintenanceIn::find($id);
        if($maintainin)
        {
            $wd_series = \DB::select("select * from maintain_series where wm_id=".$maintainin->id.' and doc_type = "mi"');
            $seriesin = "";
            $i = 0;
            foreach($wd_series as $wd_seri)
            {
                if($i > 0)
                    $seriesin .= ',';
                $seriesin .= $wd_seri->seri;
                $i ++;
            }
            $wd_series = \DB::select("select * from maintain_return_series where doc_id=".$maintainin->id.' and doc_type = "mr"');
            $seriesout = "";
            $i = 0;
            foreach($wd_series as $wd_seri)
            {
                if($i > 0)
                    $seriesout .= ',';
                $seriesout .= $wd_seri->seri;
                $i ++;
            }
            $paid_amount = 0;
            $amount_before_paid = 0;
            $amount_before_trans = 0;
            $amount_after_trans = 0;
            $sup_trans = \App\Models\SupTransaction::where('doc_type','mo')->where('doc_id',$maintainin->id)->first();
            if($sup_trans)
            {
                $paid_amount = $maintainin->paid_amount;
                $amount_before_paid = $sup_trans->total  ;
                $amount_before_trans =  $sup_trans->total - $sup_trans->operation* $sup_trans->amount;
                $amount_after_trans = $sup_trans->total   +  $maintainin->paid_amount;
                if ( $amount_after_trans == $amount_before_trans && $amount_after_trans > 0 )
                {
                    $amount_after_trans = $amount_before_paid;
                }
            }
            

            $active_menu="mainin_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainin.index').'">Danh sách nhận bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem phiếu nhập bảo hành </li>';
            return view('backend.maintainins.show',compact('breadcrumb','active_menu','maintainin','seriesin','seriesout','amount_after_trans','amount_before_trans','amount_before_paid'));
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getitem(Request $request)
    {
        $this->validate($request,[
            'id'=>'numeric|required',
        ]);
        $maintainin = MaintenanceIn::find($request->id);
        if($maintainin)
        {
            return response()->json(['msg'=>$maintainin,'status'=>true]);
     
        }
        else
        {
            return response()->json(['msg'=>'không tìm thấy thông tin','status'=>false]);
        }
    }
    public function maintaininSavePaid(Request $request)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'numeric|required',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $mb = MaintenanceIn::find($data['id']);
        $user = auth()->user();
       
        if( $mb)
        {
             ///create paid transaction
             $bankaccount = Bankaccount::find($data['bank_id']);
            if($data['paid_amount'] <0  )
            {
                return response()->json(['msg'=>'Số tiền trả không hợp lệ!','status'=>false]);
            }
            if($bankaccount->total < $data['paid_amount']  )
            {
                return response()->json(['msg'=>'Số tiền trong tài khoản không đủ!','status'=>false]);
            }
            $mb->paid_amount += $data['paid_amount'];
            if($mb->paid_amount > $mb->final_amount)
            {
                return response()->json(['msg'=>'Số tiền trả nhiều hơn phải trả!','status'=>false]);
            }
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'], 1,$mb->id,'mo',$data['paid_amount']);
            SupTransaction::createSubTrans($bank_doc->id,'mo', 1, $data['paid_amount'], $mb->customer_id); 
            
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
            $content = 'Trả tiền cho phiếu trả bảo hành';
            \App\Models\Log::insertLogNew($content,$mb->id,'mip',$user->id);
     
            // $content = 'paid money for maintainin: '.$mb->id .' total: '.$data['paid_amount'];
            // \App\Models\Log::insertLog($content,$user->id);
            
            return response()->json(['msg'=>'Đã thêm số tiền trả!','status'=>true]);
            
        }
        else
        {
            return response()->json(['msg'=>'Không tìm thấy dữ liệu!','status'=>false]);
          
        }
    }
    public function edit(string $id)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $maintainin = MaintenanceIn::find($id);
        if($maintainin && $maintainin->status == 'received')
        {
            $active_menu="mainin_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainin.index').'">Danh sách nhận bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu nhận bảo hành </li>';
             $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
             $categories = \App\Models\Category::where('status','active')->orderBy('id','ASC')->get();
             $bank_id = 0;
             $ship_amount = 0;
             if($maintainin->shiptrans_id)
             {
                 $shiptrans = FreeTransaction::where('id',$maintainin->shiptrans_id)->first();
                 $bank_id = $shiptrans->bank_id;
                 $ship_amount = $shiptrans->total;
             }   

            $wd_series = \DB::select("select * from maintain_series where wm_id=".$maintainin->id.' and doc_type = "mi"');
            $series = "";
            $i = 0;
            foreach($wd_series as $wd_seri)
            {
                if($i > 0)
                    $series .= ',';
                $series .= $wd_seri->seri;
                $i ++;
            }
             return view('backend.maintainins.edit',compact('breadcrumb','active_menu', 'series',  'bankaccounts' ,'categories','bank_id','ship_amount','maintainin'));
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu hoặc đã gửi hàng!');
        }
       
    }
    public function edit_paid_amount(string $id)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $maintainin = MaintenanceIn::find($id);
        if($maintainin && $maintainin->status == 'finished')
        {
            $active_menu="mainin_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintainin.index').'">Danh sách nhận bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> cập nhật phiếu nhận bảo hành </li>';
             $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
             $categories = \App\Models\Category::where('status','active')->orderBy('id','ASC')->get();
             $bank_id = 0;
             $ship_amount = 0;
             if($maintainin->shiptrans_id)
             {
                 $shiptrans = FreeTransaction::where('id',$maintainin->shiptrans_id)->first();
                 $bank_id = $shiptrans->bank_id;
                 $ship_amount = $shiptrans->total;
             }   

            

           $wd_series = \DB::select("select * from maintain_return_series where doc_id=".$maintainin->id.' and doc_type = "mr"');
           $series = "";
           $i = 0;
           foreach($wd_series as $wd_seri)
           {
               if($i > 0)
                   $series .= ',';
               $series .= $wd_seri->seri;
               $i ++;
           }

             return view('backend.maintainins.edit_paid',compact('breadcrumb','active_menu',  'bankaccounts' ,'categories','bank_id','ship_amount','maintainin','series'));
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu hoặc đã gửi hàng!');
        }
       
    }
    public function maintaininUpdatepaid(Request $request)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'mi_id'=>'numeric|required',
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'final_amount'=>'numeric|required',
            'paid_amount'=>'string|nullable',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $mi = MaintenanceIn::find($request->mi_id);
        if(!$mi || $mi->status !='finished')
        {
            return back()->with('error','Cần phải điều chỉnh trạng thái sang finished mới hoàn trả cho khách được!');
        }
        if($data['paid_amount']> $mi->final_amount || $data['paid_amount']<0)
        {
            return back()->with('error','Số tiền trả không hợp lệ!');
        }
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        if(!$minventory )
            return back()->with('error','Không tìm thấy sản phẩm!');
        if($minventory->quantity + $mi->quantity < $data['quantity'] )
            return back()->with('error','Sản phẩm trong kho không đủ');
        
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
     

        $wr_series = \App\Models\MaintainReturnSeries::where('doc_id',$mi->id)->where('doc_type','mr')
            ->where('is_sold',0)->get();
        foreach($wr_series as $wr_seri)
        {
            $mi_seri = \App\Models\MaintainSeries::where('seri',$wr_seri->seri)->where('product_id',$wr_seri->product_id)
                ->where('id',$wr_seri->in_id)->first();
            $mi_seri->is_sold = 0;
            $mi_seri->save();
        }

        $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
        $counts_n = $counts_n[0]->tong;
        //so hang khong co seri ton kho
        $n_noseri = $minventory->quantity - $counts_n ;
        //so hang khong co seri xuat kho
        $sold_noseri =$data['quantity'] - $count_n;
       
        if($count_n > $counts_n )
        {
            foreach($wr_series as $wr_seri)
            {
                $mi_seri = \App\Models\MaintainSeries::where('seri',$wr_seri->seri)->where('product_id',$wr_seri->product_id)
                ->where('id',$wr_seri->in_id)->first();
                $mi_seri->is_sold = 1;
                $mi_seri->save();
            }
            return back()->withInput()->with('error','Số series lơn hơn số hàng số hàng có series trong kho!');
        }
        if($count_n !=0 && $count_n != $data['quantity'] )
        {
            foreach($wr_series as $wr_seri)
            {
                $mi_seri = \App\Models\MaintainSeries::where('seri',$wr_seri->seri)->where('product_id',$wr_seri->product_id)
                ->where('id',$wr_seri->in_id)->first();
                $mi_seri->is_sold = 1;
                $mi_seri->save();
            }
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
                    foreach($wr_series as $wr_seri)
                    {
                        $mi_seri = \App\Models\MaintainSeries::where('seri',$wr_seri->seri)->where('product_id',$wr_seri->product_id)
                            ->where('id',$wr_seri->in_id)->first();
                        $mi_seri->is_sold = 1;
                        $mi_seri->save();
                    }
                    return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
                }
                    
        } 
        //so hang khong co seri ton kho
        $n_noseri = $minventory->quantity + $mi->quantity - $counts_n ;
        //so hang khong co seri xuat kho
        $sold_noseri =$data['quantity']  -  $count_n;
        if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
        {
            foreach($wr_series as $wr_seri)
            {
                $mi_seri = \App\Models\MaintainSeries::where('seri',$wr_seri->seri)->where('product_id',$wr_seri->product_id)
                ->where('id',$wr_seri->in_id)->first();
                $mi_seri->is_sold = 1;
                $mi_seri->save();
            }
            return back()->with('error','Số hàng không có series '.$sold_noseri.' trong phiếu xuất nhiều hơn trong kho'.$n_noseri.'!')->withInput();;
        }
        
        
        if($mi && ($mi->status =='finished' ))
        {
            //process new data
            
            $user = auth()->user();
            //removed suptransaction
            if($mi->final_amount > 0)
            {
                // dd($mi->suptrans_id);
                SupTransaction::removeSubTrans($mi->suptrans_id,'mor',$mi->id);
            }
               
            
            //removed old paid trans
            if($mi->paidtrans_ids)
            {

                $in_ids = json_decode($mi->paidtrans_ids);
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
            //remove invmaintaindetail,seri
            $wi_series = \DB::select("delete from maintain_return_series where doc_type='mr' and doc_id=".$mi->id.' and is_sold = 0');
            if ($minventory)
            {
                $minventory->quantity += $mi->quantity;
                $minventory->save();
            }
            \App\Models\InvMaintainDetail::remove($mi->id,'mr');


            //save new data
            $mi->final_amount = $data['final_amount'];
            $mi->shipback = $data['shipback'];
            $mi->maincost = $data['maincost'];
            $mi->paid_amount = $request->paid_amount;
            $mi->status = 'finished';
            $mi->save();
            ///sub inventory
                //add inventory maintenance
            \App\Models\InventoryMaintenance::removePro($mi->product_id,$mi->quantity);
            //tao detail out
             //save propertytodestroy doc
             $imd= \App\Models\InvMaintainDetail::c_create($mi,'mr',-1,$count_n>0?1:0); //1 la nhap
             $in_ids =\App\Models\InvMaintainDetail::sold_product($data['product_id'],$sold_noseri);
        
             foreach ($series as $seri)
             {
                 $seri = trim ($seri);
                 if($seri == '')
                         continue;
                 $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
                     ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
                 $data_seri['doc_id'] = $mi->id;
                 $data_seri['seri'] = $seri;
                 $data_seri['doc_type'] = 'mr';
                 $data_seri['product_id'] = $data['product_id'];
                 $data_seri['in_id'] = $wi_seri->id;
                 $wd_seri = \App\Models\MaintainReturnSeries::create($data_seri);
                 $wi_seri->out_id = $wd_seri->id;
                 $wi_seri->is_sold = 1;
                 $wi_seri->save();
                 $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
                 array_push($in_ids,$in_id);
             }
             $imd->in_ids = json_encode($in_ids);
             $imd->save();
              ///create SupTransaction
              $sps = SupTransaction::createSubTrans($mi->id,'mo',-1,$mi->final_amount, $mi->customer_id);
              $mi->suptrans_id = $sps->id;
              ///create paid transaction
              
              $user = auth()->user();
              if($request->paid_amount > 0)
              {
                  $bank_doc = BankTransaction::insertBankTrans($user->id,$request->bank_id,1,$mi->id,'mo',$request->paid_amount);
                  SupTransaction::createSubTrans($bank_doc->id,'fi',1,$request->paid_amount, $mi->customer_id); 
                  $in_ids=array();
                  $in_id = new \App\Models\Number();
                  $in_id->id = $bank_doc->id;
                  array_push($in_ids,$in_id);
                  $mi->paidtrans_ids = json_encode($in_ids);
              }
              $mi->save();

            //   if( $totalbankpaid > 0)
            //   {
            //       $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],1,$oldmaintainin->id,'wo', $totalbankpaid );
            //       SupTransaction::createSubTrans($bank_doc->id,'fi',1,  $totalbankpaid , $data['customer_id']); 
            //       $in_ids=array();
            //       $in_id = new \App\Models\Number();
            //       $in_id->id = $bank_doc->id;
            //       array_push($in_ids,$in_id);
            //       $oldmaintainin->paidtrans_ids = json_encode($in_ids);
            //   }
            ///create log /////////////
            $content = 'cập nhật phiếu trả bảo hành';
            \App\Models\Log::insertLogNew($content,$mi->id,'mr',$user->id);
      

            return redirect()->route('maintainin.index')->with('success','thành công!');
    

        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu hoặc hàng hóa đã được xữ lý!');
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "min_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'customer_id'=>'numeric|required',
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'description'=>'string|nullable',
            'bank_id'=>'numeric|required',
            'shipcost'=>'numeric|nullable',
            
        ]);
        $maintainin = MaintenanceIn::find($id);
        if($maintainin && $maintainin->status =='received' && $maintainin->result == 'pending')
        {
            //process new data
            $data= $request->all();
            // dd($data);
            if($data['customer_id'] == 0)
            {
                return back()->withInput()->with('error','Hãy chọn khách hàng!');
            }

            $data['status']="received";
            $data['result']="pending";
            if(isset($data['shipcost']) && $data['shipcost']  > 0)
            {
                $data['maincost'] = $data['shipcost'];
            }
            else
            {
                $data['maincost'] = 0;
            }
            $data['paid_amount'] = 0;
            $data['final_amount'] = 0;
            $user = auth()->user();
            $data['vendor_id'] = $user->id;
            $bank = \App\Models\Bankaccount::find($data['bank_id']);
            if($data['shipcost'] && $bank->total +$maintainin->shipcost < $data['shipcost'])
            {
                return back()->with('error','Không đủ tiền trả phí vận chuyển!');
            }
            if(\App\Models\InvMaintainDetail::check_sold($maintainin->id,'mi'))
            {
                return back()->with('error','Sản phẩm đã xuất khỏi kho bảo hành, không thể chỉnh sửa!');
            }
            //doc tu detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
          
            $wi_series =  \App\Models\MaintainSeries::where('wm_id',$maintainin->id)->where('doc_type','mi')->get();
            
            $series = "";
            $i = 0;
            foreach($wi_series as $wi_seri)
            {
                $wi_seri->is_sold = 1;
                $wi_seri->save();
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
            if($count_n !=0 && $count_n != $data['quantity'] )
            {
                return back()->withInput()->with('error','Số lượng series khác số số lượng nhập!');
            }
            foreach ($series as $seri)
            {
                $seri = trim($seri); //tim danh sách hàng có số seri và chưa bán, nếu không có thì ko thể xuất
                if($seri == '')
                    continue;
                $query ='select * from maintain_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
                $rows = \DB::select($query);
                if(count($rows) > 0)
                {
                    foreach($wi_series as $wi_seri)
                    {
                        $wi_seri->is_sold = 0;
                        $wi_seri->save();
                    }
                    return back()->with('error','Số serie' . $seri.' đã có trong kho!')->withInput();;
                }
            } 
            //remove privious action
            \App\Models\InventoryMaintenance::removePro($maintainin->product_id,$maintainin->quantity);
            ///delete ship invoice
            if($maintainin->shiptrans_id)
            {
                    $fts = FreeTransaction::find($maintainin->shiptrans_id);
                    if($fts)
                    {
                        $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                        if($banktrans)
                            BankTransaction::removeBankTrans($banktrans);
                        $fts->delete();
                    }
            }
            //save new data
            //add inventory maintenance
                //xoa them detail invp 
            $sql = "delete from maintain_series where doc_type='mi' and wm_id=". $maintainin->id;
            \DB::select($sql);
            \App\Models\InvMaintainDetail::remove($maintainin->id,'mi');

            \App\Models\InventoryMaintenance::addPro($data['product_id'],$data['quantity']);
            ////////
            $maintainin->fill($data);
            if($data['shipcost'] && $data['shipcost'] > 0)
            {
                $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
                $maintainin->shiptrans_id = $fts->id;
                BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
            }
            $maintainin->save();
            $maintainin->price = 0;
            \App\Models\InvMaintainDetail::c_create($maintainin,'mi',1,$count_n>0?1:0); //1 la nhap
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $data_seri['wm_id'] = $maintainin->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'mi';
                $data_seri['product_id'] = $data['product_id'];
                // $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\MaintainSeries::create($data_seri);
               
            }
            ///create log /////////////
             $content = 'cập nhật phiếu nhận bảo hành' ;
            \App\Models\Log::insertLogNew($content,$maintainin->id,'mi',$user->id);
            
            return redirect()->route('maintainin.index')->with('success','thành công!');
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu hoặc hàng hóa đã được xữ lý!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "min_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $maintainin = MaintenanceIn::find($id);
        if($maintainin && $maintainin->status =='received' && $maintainin->result == 'pending')
        {
             //remove privious action
             \App\Models\InventoryMaintenance::removePro($maintainin->product_id,$maintainin->quantity);
             ///delete ship invoice
             if($maintainin->shiptrans_id)
             {
                     $fts = FreeTransaction::find($maintainin->shiptrans_id);
                     if($fts)
                     {
                         $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                         if($banktrans)
                             BankTransaction::removeBankTrans($banktrans);
                         $fts->delete();
                     }
             }

            if(\App\Models\InvMaintainDetail::check_sold($maintainin->id,'mi'))
            {
                return back()->with('error','Sản phẩm đã xuất khỏi kho bảo hành, không thể chỉnh sửa!');
            }
            $sql = "delete from maintain_series where doc_type='mi' and wm_id=". $maintainin->id;
            \DB::select($sql);
            \App\Models\InvMaintainDetail::remove($maintainin->id,'mi');


             $user = auth()->user();
               ///create log /////////////
            // $content = 'delete maintainin id'.$maintainin->id.' product_id: '.$maintainin->product_id.' quantity: '.$maintainin->quantity;
            // \App\Models\Log::insertLog($content,$user->id);
            $content = 'xóa phiếu nhận bảo hành' ;
            \App\Models\Log::insertLogNew($content,$maintainin->id,'mi',$user->id);
            
            $maintainin->delete();
            return redirect()->route('maintainin.index')->with('success','Xóa thành công!'); 

        }
        else
        {

            return back()->with('error','Không tìm thấy dữ liệu hoặc hàng hóa đã được xữ lý!');
    
        }
        
    }
}
