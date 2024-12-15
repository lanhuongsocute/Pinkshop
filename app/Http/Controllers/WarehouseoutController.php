<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Warehouseout;
use App\Models\SupTransaction; 
use App\Models\WarehouseoutDetail;
use App\Models\Bankaccount;
use App\Models\BankTransaction;
use App\Models\FreeTransaction;
use App\Models\UGroup;
use App\Models\User;
class WarehouseoutController extends Controller
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
    public function publishItcctv(Request $request)
    {
        
        $func = "warout_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
             
        ]);
        //
        $wo_id = $request->id;
        $wo = \App\Models\Warehouseout::find($wo_id);
        if(!$wo)
            return back()->with('error','Không tìm thấy dữ liệu');
        $helpController = new \App\Http\Controllers\HelpController();
        $re = $helpController->send_invoice($wo_id,$wo->uiid);
        //  dd($re);
        if($re)
        {
            $wo->is_global = 1;
            $wo->save();
            return redirect()->route('warehouseout.index')->with('success','Lưu thành công!');
        }
        else
        {
            return redirect()->route('warehouseout.index')->with('success','Lưu thất bại!');
        }
        
    }
    public function today(Request $request)
    {
        $func = "warout_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // dd($request->datepicker);
        $data['active_menu']="wo_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách bán hàng </li>';
        
        
        $data['date1']   = date("Y-m-d");
        $date1 = date("Y-m-d");
        $data['date2'] =  date("Y-m-d");
        $date2 = date("Y-m-d");
         
        if(isset($request->customer_id))
            $data['customer_id'] = $request->customer_id;
        else
            $data['customer_id'] = 0;
        $where = "";
        if($data['customer_id'] != 0)
        {
            $where=" customer_id = ".$data['customer_id'];
        }    
        
        if(isset($date1) && isset($date2) )
        {
            if($where != "")
                $where .= ' and ';
            $where.=" datediff( created_at , '".$date1."')>= 0 and datediff(created_at , '".$date2."')<= 0";
        }
        if($where != "")
            $where = " where ".$where;
        
        $query = " (select id from warehouseouts ".$where.") as b ";
        // dd($query);
        $data['warehouseouts'] = DB::table('warehouseouts')
        ->select ('warehouseouts.*'   )
        ->join(\DB::raw($query),'warehouseouts.id','=','b.id')
        ->orderBy('id','desc')
        ->paginate($this->pagesize)->withQueryString();


        // $data['warehouseouts']=warehouseout::orderBy('id','DESC')->paginate($this->pagesize);
        // $data['customers'] = \App\Models\User::where('role','customer')->orWhere('role','supcustomer')->get();
        return view('backend.warehouseouts.index', $data);
    }
    public function index(Request $request)
    {
        $func = "warout_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // dd($request->datepicker);
        $data['active_menu']="wo_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách bán hàng </li>';
        
        if(isset($request->date1))
        {
             
            $data['date1'] = $request->date1;
            $data['date1'] = str_replace(',','', $data['date1']);
            $timestamp = strtotime($data['date1']);
            $date1 = date("Y-m-d",   $timestamp);
            // dd( $date1);
            $data['date1'] =   date("m-d-Y",   $timestamp);
        }    
        else
            $data['date1']   = date("Y-m-d", strtotime("-12 month"));

        if(isset($request->date2))
        {
            $data['date2'] = $request->date2;
            $data['date2'] = str_replace(',','', $data['date2']);
            $timestamp = strtotime($data['date2']);
            $date2 = date("Y-m-d", $timestamp);
            $data['date2'] =   date("m-d-Y",   $timestamp);
        }    
        else
            $data['date2'] =  date("Y-m-d");

         
        if(isset($request->customer_id))
            $data['customer_id'] = $request->customer_id;
        else
            $data['customer_id'] = 0;
        $where = "";
        if($data['customer_id'] != 0)
        {
            $where=" customer_id = ".$data['customer_id'];
        }    
        
        if(isset($date1) && isset($date2) )
        {
            if($where != "")
                $where .= ' and ';
            $where.=" datediff( created_at , '".$date1."')>= 0 and datediff(created_at , '".$date2."')<= 0";
        }
        if($where != "")
            $where = " where ".$where;
        
        $query = " (select id from warehouseouts ".$where.") as b ";
        // dd($query);
        $data['warehouseouts'] = DB::table('warehouseouts')
        ->select ('warehouseouts.*'   )
        ->join(\DB::raw($query),'warehouseouts.id','=','b.id')
        ->orderBy('id','desc')
        ->paginate($this->pagesize)->withQueryString();


        // $data['warehouseouts']=warehouseout::orderBy('id','DESC')->paginate($this->pagesize);
        // $data['customers'] = \App\Models\User::where('role','customer')->orWhere('role','supcustomer')->get();
        return view('backend.warehouseouts.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "warout_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="wo_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Ds bán hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
        $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
        // $ugroups=UGroup::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.warehouseouts.create',compact('breadcrumb','active_menu', 'warehouses','bankaccounts','user','deliveries'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function save_warehouseout(Request $request)
    {
        $func = "warout_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data = $request->importDoc;
        // return $data;
        $customer = \App\Models\User::find($data['customer_id']);
        $deb_before = $customer->budget;

        $totalbankpaid = $data['paid_amount'];
        $totalbudgetpaid = 0;
        if($customer->budget > 0 && $data['paid_amount'] < $data['final_amount'])
        {
            if($customer->budget + $data['paid_amount']  >= $data['final_amount'])
            {
                $totalbudgetpaid = ( $data['final_amount'] - $data['paid_amount'] );
                $data['paid_amount']  =  $data['final_amount'];
                 
            }
            else
            {
                $data['paid_amount']  =  $data['paid_amount'] +  $customer->budget;
                $totalbudgetpaid  =  $customer->budget;
            }
        }
       
        if($data['paid_amount'] == $data['final_amount'])
            $data['is_paid'] = 1;
        else
            $data['is_paid'] = 0;
       
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        if ($data['discount_amount'] == null)
            $data['discount_amount']=0;
        if ($data['shipcost'] == null)
            $data['shipcost']=0;
        ///check product inventory//////
        $details = $request->products;
        foreach ($details as $detail)
        {
            
            $pro_inventory = Inventory::where('product_id',$detail['id'])->where('wh_id', $data['wh_id'])->first();
            if(!$pro_inventory || $pro_inventory->quantity < $detail['quantity'] )
            {
                return 1;
            }
              ////update series for each product
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
                    return 2;
              }
              if($count_n > $detail['quantity'] )
              {
                    return 3;
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
                            return 5;
                        }
                            
                    } 
              }
             
              //so hang khong co seri ton kho
              $n_noseri = $pro_inventory->quantity - $counts_n ;
              //so hang khong co seri xuat kho
              $sold_noseri =$detail['quantity'] - $count_n;
              if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
              {
                    return 4;
              }

        }
        ///save product detail ////////////
        ////average price///////////////////
        
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['discount_amount'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        $data['bankpayment'] = $totalbankpaid;
        $data['debtbefore'] = $deb_before;
        $data['debtafter'] =  $deb_before - $data['final_amount'];

        $wo = Warehouseout::c_create($data);
       
        // return $wi;
        // dd($wo);
        ////////////////////////////////////
        foreach ($details as $detail)
        {
            $product_detail['wo_id'] = $wo->id;
            $product_detail['wh_id'] = $data['wh_id'];
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            //tim pre balance
            $inv = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id'])
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
            $counts_n = \DB::select ("select count(id) as tong from warehousein_detail_series where product_id = ".$detail['id'].' and is_sold = 0'); 
            $counts_n = $counts_n[0]->tong;
            //so hang khong co seri xuat kho
            $sold_noseri =$detail['quantity'] - $count_n;
            Inventory::subProductInv($product_detail['product_id'], $data['wh_id'], $detail['quantity'], $product_detail['price'], $cost_extra);
            $in_ids = Inventory::updateWarehouseLastIn($product_detail['product_id'], $data['wh_id'],$sold_noseri);
            
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                    ->where('product_id',$detail['id'])->where('is_sold',0)->first();
                $wi_seri->is_sold = 1;
                $wi_seri->save();
                $data_seri['wo_id'] = $wo->id;
                $data_seri['seri'] = $seri;
                $data_seri['product_id'] = $detail['id'];
                $data_seri['in_id'] = $wi_seri->id;
                $data_seri['doc_type'] = 'wo';
                \App\Models\WarehouseoutDetailSeries::create($data_seri);
                $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                    ->where('product_id',$wi_seri->product_id)->first();
                $in_id = Inventory::updateWarehouseInDetails($product_detail['product_id'], $data['wh_id'],$detail_in);
                array_push($in_ids, $in_id);
            }
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='wo'; //loai xuat la phieu xuat ban hang
            WarehouseoutDetail::c_create($product_detail);
            \Log::info('insert product detail.');
            \Log::info( $product_detail['product_id']);
            \Log::info( $product_detail['quantity'] );
            \Log::info( $product_detail['price'] );
        }
      
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($wo->id,'wo',-1,$data['final_amount'], $data['customer_id']);
        \Log::info( 'SupTransaction' );
        $wo->suptrans_id = $sps->id;
        ///create paid transaction
        if( $totalbankpaid > 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],1,$wo->id,'wo',$totalbankpaid );
            SupTransaction::createSubTrans($bank_doc->id,'fi',1, $totalbankpaid , $data['customer_id']); 
            $in_ids=array();
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $wo->paidtrans_ids = json_encode($in_ids);
 
        }
       ///create ship invocie ///////////
       if($data['shipcost'] > 0)
       {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $wo->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
       }
      //luu uiid cho phieu xuat
       $detail = \App\Models\SettingDetail::find(1);
       if($detail->itcctv_email != '')
       {
            $md5string = md5($detail->itcctv_email . '_'.$wo->id);
            $wo->uiid   = $formattedString = implode('-', str_split($md5string, 4));;
       }
      
       $wo->save();
       
       $content = 'thêm đơn bán hàng' ;
       \App\Models\Log::insertLogNew($content,$wo->id,'wo',$user->id);
       return $wo;
    }
    
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'importDoc.final_amount'=>'numeric|required',
            'importDoc.discount_amount'=>'numeric|nullable',
            'importDoc.shipcost'=>'numeric|nullable',
            'importDoc.paid_amount'=>'numeric|required',
        ]);
        $kq = $this->save_warehouseout($request);
        // dd($kq);
        if(is_int($kq) && $kq == 1)
        {
            return response()->json(['msg'=>'Số lượng trong kho không đủ','status'=>false]);
        }
        else
        {
            if(is_int($kq) &&  $kq == 2)
            {
                return response()->json(['msg'=>'Số lượng seri trong đơn lớn hơn trong kho!','status'=>false]);
            }
            else
            {
                if(is_int($kq) &&  $kq == 3)
                {
                    return response()->json(['msg'=>'số seri lớn hơn số trong kho','status'=>false]);
                }
                else
                {
                    if(is_int($kq) &&  $kq == 4)
                    {
                        return response()->json(['msg'=>'Seri không có trong kho','status'=>false]);
                    }
                    else
                    {
                        if(is_int($kq) && $kq == 5)
                        {
                            return response()->json(['msg'=>'Số sp không seri lớn hơn số sp không seri trong kho','status'=>false]);
                        }
                        else
                        {
                            // dd($kq);
                            $html = $this->print_invoice($kq->id);
                            return response()->json(['html'=> $html,'msg'=>'Thêm đơn hàng thành công!','status'=>true]);
                        }
                    }
                }
            }
        }
       
        
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $func = "warout_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $warehouseout = Warehouseout::find($id);
        if($warehouseout)
        {
            // $sup_trans = \App\Models\SupTransaction::where('doc_type','wo')->where('doc_id',$warehouseout->id)->first();
          
            // if ( $amount_after_trans == $amount_before_trans && $amount_after_trans > 0 )
            // {
            //     $amount_after_trans = $amount_before_paid;
            // }
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">DS bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            if($warehouseout->status == 'active')
            {
                $sup_trans = \App\Models\SupTransaction::where('doc_type','wo')->where('doc_id',$warehouseout->id)->where('is_delete',0)->first();
            
                $paid_amount = $warehouseout->paid_amount;
                $buyer =  \App\Models\User::find($warehouseout->customer_id);
                 $amount_before_paid = $sup_trans->total  ;
                $amount_before_trans =  $sup_trans->total - $sup_trans->operation* $sup_trans->amount;
                $amount_after_trans = $sup_trans->total   +  $warehouseout->paid_amount;
                $amount_after_trans = $buyer->budget;

                $wo_details = WarehouseoutDetail::where('wo_id',$id)->where('doc_type','wo')->get();
                foreach($wo_details as $wi_detail)
                {
                    $series = "";
                    $i = 0;
                    $wo_seris = \DB::select("select seri from warehouseout_detail_series where wo_id =".$wi_detail->wo_id ." and doc_type='wo' and product_id = ".$wi_detail->product_id );
                    foreach($wo_seris as $wo_seri)
                    {
                        if ($i > 0)
                            $series .= ",";
                        $series .= $wo_seri->seri;
                        $i ++;
                    }
                    $wi_detail->series = $series;
                }
                return view('backend.warehouseouts.show',compact('breadcrumb','warehouseout','active_menu','wo_details','amount_after_trans','amount_before_trans','amount_before_paid'));
            }
            else
            {
                if($warehouseout->status == 'returned' )
                {
                    // $sup_trans = \App\Models\SupTransaction::where('doc_type','wr')->where('doc_id',$warehouseout->id)->first();
                    $sup_trans = \App\Models\SupTransaction::where('doc_type','wr')->where('doc_id',$warehouseout->id)->where('is_delete',0)->first();
                    
                    $paid_amount = $warehouseout->paid_amount;
                    $buyer =  \App\Models\User::find($warehouseout->customer_id);
                     $amount_before_paid = $sup_trans->total  ;
                    $amount_before_trans =  $sup_trans->total - $sup_trans->operation* $sup_trans->amount;
                    $amount_after_trans = $sup_trans->total   +  $warehouseout->paid_amount;
                    $amount_after_trans = $buyer->budget;

                    $wo_details = \App\Models\WarehouseInDetail::where('doc_id',$id)->where('doc_type','wr')->get();
                    foreach($wo_details as $wi_detail)
                    {
                        $series = "";
                        $i = 0;
                        $wi_seris = \DB::select("select seri from warehousein_detail_series where wi_id =".$wi_detail->doc_id ." and doc_type='wr' and product_id = ".$wi_detail->product_id );
                        foreach($wi_seris as $wi_seri)
                        {
                            if ($i > 0)
                                $series .= ",";
                            $series .= $wi_seri->seri;
                            $i ++;
                        }
                        $wi_detail->series = $series;
                    }

                 
                    return view('backend.warehouseouts.showreturn',compact('breadcrumb','warehouseout','active_menu','wo_details','amount_after_trans','amount_before_trans','amount_before_paid'));

                }
                else
                {
                    $dout = \App\Models\DOut::where('outid',$id)->orderBy('id','desc')->first();
                    return $this->showold( $dout->id);
                }
               
            }
           
           
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function showold(string $id)
    {
        //
        $func = "warout_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $warehouseout = \App\Models\DOut::find($id);
        if($warehouseout)
        {
            $active_menu="i_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">DS bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            $wo_details = \App\Models\DOutdetail::where('wo_id',$id)->get();
            return view('backend.warehouseouts.showold',compact('breadcrumb','warehouseout','active_menu','wo_details'));
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
        //
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

         
        $warehouseout = Warehouseout::find($id);
        if($warehouseout && $warehouseout->status == 'returned')
        {
            return $this->editreturn( $id);
        }
        if($warehouseout && $warehouseout->status == 'active')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu bán hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
        
            $paid_trans = null;
            $ship_trans = null;
            $bank_id = 0;
            $ship_amount = 0;
            $bankpaid = 0;
            if($warehouseout->paidtrans_ids)
            {
                $id_ins = json_decode($warehouseout->paidtrans_ids); 
                foreach ($id_ins as $id_in)
                {
                    $paidtrans = BankTransaction::where('id',$id_in->id)->first();
                    $bank_id = $paidtrans->bank_id;
                    $bankpaid += $paidtrans->total;

                }
               
              
            }   
            if($warehouseout->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehouseout->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }   
            $user = auth()->user();
          
                     
            return view('backend.warehouseouts.edit',compact('breadcrumb','warehouseout','active_menu','warehouses','bankaccounts','user','bank_id','ship_amount','deliveries','bankpaid'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }

    }

    public function editreturn(string $id)
    {
        //
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

         
        $warehouseout = Warehouseout::find($id);
        if($warehouseout && $warehouseout->status == 'returned')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu bán hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
        
            $paid_trans = null;
            $ship_trans = null;
            $bank_id = 0;
            $ship_amount = 0;
            $bankpaid = 0;
            if($warehouseout->paidtrans_ids)
            {
                $id_ins = json_decode($warehouseout->paidtrans_ids); 
                foreach ($id_ins as $id_in)
                {
                    $paidtrans = BankTransaction::where('id',$id_in->id)->first();
                    $bank_id = $paidtrans->bank_id;
                    $bankpaid += $paidtrans->total;

                }
            }   
            if($warehouseout->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehouseout->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }   
            $user = auth()->user();
            $returned_ids  = json_decode($warehouseout->returned_ids);
            $returned_id = $returned_ids[0]->id;
            $warehouseoutreturn = Warehouseout::find($returned_id);
         
            return view('backend.warehouseouts.editreturn',compact('breadcrumb','warehouseout','active_menu','warehouses','bankaccounts','user','bank_id','ship_amount','deliveries','warehouseoutreturn','bankpaid'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }

    }

    public function deliveryPrint($id)
    {
        $warehouseout = Warehouseout::find($id);
        if($warehouseout && $warehouseout->status == 'active')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">phiếu gửi hàng </li>';
           
            return view('backend.warehouseouts.deprint',compact('breadcrumb','warehouseout','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    
    public function getOldProductList(Request $request)
    {
        $this->validate($request,[
            'wo_id'=>'numeric|required',
        ]);
        $wo = \App\Models\Warehouseout::find($request->wo_id);
        if($wo && $wo->status == 'active')
            return $this->getProductListnew($request);
        $wo = \App\Models\DOut::where('outid',$request->wo_id)->orderBy('version','desc')->first();
        $query = "(select id,photo, title,type from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id.") as np";
        $products = DB::table('d_outdetails')
        ->select ('d_outdetails.price','d_outdetails.product_id','d_outdetails.quantity', 'p.title','p.photo','p.id','p.type','np.quantity as stock_qty')
        ->where('wo_id', $wo->id)
        ->leftJoin(\DB::raw($query),'d_outdetails.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'d_outdetails.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;
            $oproductseris = \App\Models\WarehouseoutDetailSeries::where('product_id',$product->id)
            ->where('wo_id',$request->wo_id)->where('doc_type','wo')->get();
           $i = 0;
           $series = "";
            

           $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
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
    public function getProductListnew(Request $request)
    {

        $this->validate($request,[
            'wo_id'=>'numeric|required',
        ]);
        $wo = Warehouseout::find($request->wo_id);
        $query = "(select id,photo, title,type from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id.") as np";
               
        $products = DB::table('warehouseout_details')
        ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.quantity', 'p.title','p.photo','p.id','p.type','np.quantity as stock_qty')
        ->where('wo_id',$request->wo_id)->where('doc_type','wo')
        ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'warehouseout_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;
            $oproductseris = \App\Models\WarehouseoutDetailSeries::where('product_id',$product->id)
             ->where('wo_id',$request->wo_id)->where('doc_type','wo')->get();
            $i = 0;
            $series = "";
           

            $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
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
    public function getProductList(Request $request)
    {

        $this->validate($request,[
            'wo_id'=>'numeric|required',
        ]);
        $wo = Warehouseout::find($request->wo_id);
        $query = "(select id,photo, title,type,price_in,price_out from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id.") as np";
               
        $products = DB::table('warehouseout_details')
        ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.qty_returned','warehouseout_details.quantity', 'p.title','p.photo','p.price_in','p.price_out','p.id','p.type','np.quantity as stock_qty')
        ->where('wo_id',$request->wo_id)->where('doc_type','wo')
        ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'warehouseout_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;
            $oproductseris = \App\Models\WarehouseoutDetailSeries::where('product_id',$product->id)
             ->where('wo_id',$request->wo_id)->where('doc_type','wo')->get();
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

            $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
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
    public function getProductListReturn(Request $request)
    {

        $this->validate($request,[
            'wo_id'=>'numeric|required',
            'woold_id'=>'numeric|nullable',
        ]);
        $wo = Warehouseout::find($request->woold_id);
        $query = "(select id,photo, title,type from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id.") as np";
               
        $products = DB::table('warehouseout_details')
        ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.qty_returned','warehouseout_details.quantity', 'p.title','p.photo','p.id','p.type','np.quantity as stock_qty')
        ->where('wo_id',$request->woold_id)->where('doc_type','wo')
        ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'warehouseout_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $product->qty = 0;

            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;
            $oproductseris = \App\Models\WarehouseoutDetailSeries::where('product_id',$product->id)
             ->where('wo_id',$request->woold_id)->where('doc_type','wo')->get();
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

            $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
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
        if (isset ( $request->wo_id))
        {
            $return_details = \App\Models\WarehouseInDetail::where('doc_id',$request->wo_id)->where('doc_type','wr')->get();
            foreach($return_details as $detail )
            {
                foreach($products as $product)
                {
                    if($detail->product_id == $product->id)
                    {
                        $product->qty = $detail->quantity;
                    }
                    
                }
    
            }
        }
        return response()->json(['msg'=>$products,'status'=>true]);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'importDoc.final_amount'=>'numeric|required',
            'importDoc.discount_amount'=>'numeric|nullable',
            'importDoc.shipcost'=>'numeric|nullable',
            'importDoc.paid_amount'=>'numeric|required',
        ]);
        // return $request->all();
        $data = $request->importDoc;
        $oldwarehouseout = WarehouseOut::find($id);
        // return $oldwarehouseout;
        if($data['id']==null || $data['id']==0 || $oldwarehouseout==null || $oldwarehouseout->status == 'return')
            return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
       
        if($data['paid_amount'] == $data['final_amount'])
            $data['is_paid'] = 1;
        else
            $data['is_paid'] = 0;
       
       
        //check detail product are exported
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        if ($data['discount_amount'] == null)
            $data['discount_amount']=0;
        if ($data['shipcost'] == null)
            $data['shipcost']=0;
        ///check product inventory//////
        $details = $request->products;
        foreach ($details as $detail)
        {
                ////delete old series
           
              //lay chi tiet xuat kho cu de kiem tra cung ton kho va so xuat kho moi
            $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')
              ->where('product_id',$detail['id'])->first();
           
            $pro_inventory = Inventory::where('product_id',$detail['id'])->where('wh_id', $data['wh_id'])->first();
            if(!$pro_inventory || $pro_inventory->quantity + $wo_detail->quantity < $detail['quantity'] ) //so sanh so luong ton kho hien tai va so luong phieu xuat kho cux nho hon so luong xuat kho moi
            {
                    return response()->json(['msg'=>'Số hàng xuất nhiều hơn trong kho a!' .$wo_detail->quantity ,'status'=>false]);
            }
           
            ////cap nhat seri trong warehousein nhu chua xuat de kiem tra thong tin moi
            $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
            foreach($wo_series as $wo_seri)
            {
                    $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                    \DB::select($query);
            }
              ////update series for each product
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
                    return response()->json(['msg'=>'Số hàng xuất có seri lớn hơn số có seri trong kho!','status'=>false]);
              }
              if($count_n > $detail['quantity'] )
              {
                    return response()->json(['msg'=>'Số series '.$count_n.' lơn hơn số số lượng trong đơn'.$detail['quantity'] .'!','status'=>false]);
              }
              foreach ($series as $seri)
              {
                    $seri = trim($seri);
                    if ($seri == '')
                    continue;
                    $query ='select * from warehousein_detail_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$detail['id'];
                    $rows = \DB::select($query);
                    if(count($rows) == 0)
                    {
                        foreach($wo_series as $wo_seri) //neu co loi thi cap nhat da xuat lại nhu cũ và trả về
                        {
                                $query = 'update warehousein_detail_series set is_sold = 1 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                                \DB::select($query);
                        }
                        return response()->json(['msg'=>'seri không có trong kho!','status'=>false]);
     
                    }
                        
              } 
              //so hang khong co seri ton kho
              $n_noseri = $pro_inventory->quantity - $counts_n + $wo_detail->quantity;
              //so hang khong co seri xuat kho
              $sold_noseri =$detail['quantity'] - $count_n;
              if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
              {
                return response()->json(['msg'=>'Số hàng xuất không seri lớn hơn số hàng không seri trong kho!','status'=>false]);
     
              }

        }
       
        $detailpros = WarehouseoutDetail::where('wo_id',$data['id'])->where('doc_type','wo')->get();
        $bank_docs = BankTransaction::where('doc_id',$oldwarehouseout->id)
            ->where('doc_type','wo')->get();
        
        $sum_paid = 0;
        foreach ($bank_docs as $bank_doc)
        {
            $sum_paid += $bank_doc->total;
        }
        if($sum_paid != $oldwarehouseout->paid_amount )
        {
            return response()->json(['msg'=>'Đã có nhiều giao dịch trả tiền cho phiếu xuất hàng. Không thể thay đổi thông tin!','status'=>false]);
        }

        //delete all old product detail
        $dout = \App\Models\Warehouseout::log_change($oldwarehouseout);
        foreach($detailpros as $dtpro)
        {
            WarehouseoutDetail::deleteDetailProVersion($dtpro,$oldwarehouseout->cost_extra,$oldwarehouseout->wh_id,$dout->id);
        }
        ///delete sup trans 1 for importing
        SupTransaction::removeSubTrans($oldwarehouseout->suptrans_id,'wor',$dout->id);
        ///
         ///delete paid transaction
         $total_return = 0;
        if($oldwarehouseout->paidtrans_ids)
        {
            $in_ids = json_decode($oldwarehouseout->paidtrans_ids);
            foreach ($in_ids as $in_id)
            {
                $bank_doc = BankTransaction::find( $in_id->id );
                
                if($bank_doc)
                {
                    $total_return+= $bank_doc->total;
                    $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                    if($suptrans)
                        SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                    BankTransaction::removeBankTrans($bank_doc);
                }
            }
        }
       
        ///delete ship invoice
         
       if($oldwarehouseout->shiptrans_id)
       {
            $fts = FreeTransaction::find($oldwarehouseout->shiptrans_id);
            if($fts)
            {
                $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                if($banktrans)
                    BankTransaction::removeBankTrans($banktrans);
                $fts->delete();
            }
       }
         ////delete old series
       ////add series for each product
       $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
       foreach($wo_series as $wo_seri)
       {
            $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
            \DB::select($query);
       }
       $sql = "delete from warehouseout_detail_series where  doc_type='wo' and wo_id=". $oldwarehouseout->id;
       \DB::select($sql);
      
         ///save product detail ////////////
        ////average price///////////////////
         // return $data;
         $customer = \App\Models\User::find($data['customer_id']);
         $deb_before = $customer->budget;
         $totalbankpaid = $data['paid_amount'];
         $totalbudgetpaid = 0;
         if($customer->budget > 0 && $data['paid_amount'] < $data['final_amount'])
         {
             if($customer->budget + $data['paid_amount']  >= $data['final_amount'])
             {
                 $totalbudgetpaid = ( $data['final_amount'] - $data['paid_amount'] );
                 $data['paid_amount']  =  $data['final_amount'];
                  
             }
             else
             {
                 $data['paid_amount']  =  $data['paid_amount'] +  $customer->budget;
                 $totalbudgetpaid  =  $customer->budget;
             }
         }
        
         if($data['paid_amount'] == $data['final_amount'])
             $data['is_paid'] = 1;
         else
             $data['is_paid'] = 0;

        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['discount_amount'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        ///update sysaccount
        $oldwarehouseout->s_update_final_amount( $data['final_amount']);

        $oldwarehouseout->fill($data)->save();

        // return $wi;
        ////////////////////////////////////

       
        foreach ($details as $detail)
        {
            $product_detail['wo_id'] = $oldwarehouseout->id;
            $product_detail['wh_id'] = $data['wh_id'];
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            $product = Product::find($detail['id']);
            $start_date = date('Y-m-d H:i:s');
            //tim prebalance cua san pham truoc khi xuat
            $inv = \App\Models\Inventory::where('product_id',$product_detail['product_id'])
                ->where('wh_id',$product_detail['wh_id'])
                ->first();
            if( $inv)
                $product_detail['prebalance'] =$inv->quantity;
            else
                $product_detail['prebalance'] = 0;
            //tinh ngay het han
            if($product->expired)
            {
                $strday = '+' . $product->expired*30 .' days';
                $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $in_ids=array();
           ////update series for each product
           $series =  explode(",",  $detail['seri']);
           $count_n =0;
           if($detail['seri']!= '')
           {
               $count_n =count($series );
           }
           $counts_n = \DB::select ("select count(id) as tong from warehousein_detail_series where product_id = ".$detail['id'].' and is_sold = 0'); 
           $counts_n = $counts_n[0]->tong;
           //so hang khong co seri ton kho
           $n_noseri = $pro_inventory->quantity - $counts_n ;
           //so hang khong co seri xuat kho
           $sold_noseri =$detail['quantity'] - $count_n;
           //giam so luong ton kho
           Inventory::subProductInv($product_detail['product_id'], $data['wh_id'], $detail['quantity'], $product_detail['price'], $cost_extra);
           //tim detail in voi san pham ko seri
           $in_ids = Inventory::updateWarehouseLastIn($product_detail['product_id'], $data['wh_id'],$sold_noseri);
           
           foreach ($series as $seri)
           {
               $seri = trim ($seri);
               if ($seri == '')
                    continue;
               $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                   ->where('product_id',$detail['id'])->where('is_sold',0)->first();
               $wi_seri->is_sold = 1;
               $wi_seri->save();
               $data_seri['wo_id'] = $oldwarehouseout->id;
               $data_seri['seri'] = $seri;
               $data_seri['product_id'] = $detail['id'];
               $data_seri['in_id'] = $wi_seri->id;
               $data_seri['doc_type'] = 'wo';
               \App\Models\WarehouseoutDetailSeries::create($data_seri);
                //tim detailin cho seri
                $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                   ->where('product_id',$wi_seri->product_id)->first();
                $in_id = Inventory::updateWarehouseInDetails($product_detail['product_id'], $data['wh_id'],$detail_in);
                array_push($in_ids, $in_id);
           }
           $product_detail['in_ids'] = json_encode($in_ids);
           $product_detail['doc_type']='wo'; //loai xuat la phieu xuat ban hang
           WarehouseoutDetail::c_create($product_detail);
        }
        
             
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($oldwarehouseout->id,'wo',-1,$data['final_amount'], $data['customer_id']);
        $oldwarehouseout->suptrans_id = $sps->id;
        ///create paid transaction
        if( $totalbankpaid > 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],1,$oldwarehouseout->id,'wo', $totalbankpaid );
            SupTransaction::createSubTrans($bank_doc->id,'fi',1,  $totalbankpaid , $data['customer_id']); 
            $in_ids=array();
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $oldwarehouseout->paidtrans_ids = json_encode($in_ids);
        }
        ///create ship invocie ///////////
        if($data['shipcost'] > 0)
        {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $oldwarehouseout->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
        }
        $oldwarehouseout->version += 1;

        $oldwarehouseout->bankpayment  = $totalbankpaid;
        $oldwarehouseout->debtbefore  = $deb_before;
        $oldwarehouseout->debtafter =  $deb_before + $data['final_amount'];

        $oldwarehouseout->save();
        ///create log /////////////
        $content = 'cập nhật đơn bán hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehouseout->id,'wo',$user->id);
        return response()->json(['msg'=>'Cập nhật đơn hàng thành công!','status'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "warout_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
           // return $request->all();
            if(!$this->checkRole(2))
            {
                return redirect()->route('unauthorized');
            }
            $oldwarehouseout = WarehouseOut::find($id);
           // return $oldwarehouseout;
            if(  $oldwarehouseout==null || $oldwarehouseout->status == 'return')
                return back()->with('error','Không tìm thấy dữ liệu');
            $user = auth()->user();
           //check detail product are exported
            $detailpros = WarehouseoutDetail::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
            $bank_docs = BankTransaction::where('doc_id',$oldwarehouseout->id)
               ->where('doc_type','wo')->get();
         /*   $sum_paid = 0;
            foreach ($bank_docs as $bank_doc)
            {
                $sum_paid += $bank_doc->total;
            }
            if($sum_paid != $oldwarehouseout->paid_amount )
            {
                return back()->with('error','Đã có nhiều giao dịch trả tiền cho phiếu nhập hàng. Không thể xóa!');
            }
            */
           //delete all old product detail
            $dout = \App\Models\Warehouseout::log_change($oldwarehouseout);
            foreach($detailpros as $dtpro)
            {
                WarehouseoutDetail::deleteDetailProVersion($dtpro,$oldwarehouseout->cost_extra,$oldwarehouseout->wh_id,$dout->id);
            }
            
           ///delete sup trans 1 for importing
           SupTransaction::removeSubTrans($oldwarehouseout->suptrans_id,'wor',$dout->id);
           ///
        
            ///delete paid transaction
            $total_return = 0;
           if($oldwarehouseout->paidtrans_ids)
           {
                $in_ids = json_decode($oldwarehouseout->paidtrans_ids);
                foreach ($in_ids as $in_id)
                {
                    $bank_doc = BankTransaction::find( $in_id->id );

                    if($bank_doc)
                    {
                        $total_return+= $bank_doc->total;
                        $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                        if($suptrans)
                            SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                        BankTransaction::removeBankTrans($bank_doc);
                    }
                }
           }
        //    if ($total_return <  $oldwarehouseout->paid_amount)
        //     {
        //         $sps = SupTransaction::createSubTrans($oldwarehouseout->id,'wor',1,$oldwarehouseout->paid_amount- $total_return, $oldwarehouseout->customer_id);
                
        //     }
           ///delete ship invoice
        
         
          if($oldwarehouseout->shiptrans_id)
          {
               $fts = FreeTransaction::find($oldwarehouseout->shiptrans_id);
               if($fts)
               {
                   $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                   if($banktrans)
                       BankTransaction::removeBankTrans($banktrans);
                   $fts->delete();
               }
               
          }
        //   $content = 'delete warehouse out stock: '.$oldwarehouseout->wh_id.' total: '.$oldwarehouseout->final_amount;
        //   \App\Models\Log::insertLog($content,$user->id);

           ////delete old series
        ////delete series for each product
        $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
        foreach($wo_series as $wo_seri)
        {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                \DB::select($query);
        }
        $sql = "delete from warehouseout_detail_series where doc_type='wo' and wo_id=". $oldwarehouseout->id;
        \DB::select($sql);
        
        $content = 'xóa đơn bán hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehouseout->id,'wo',$user->id);

        ///update sysaccount
    //    $oldwarehouseout->s_update_final_amount( 0,true);
        //delete
        $oldwarehouseout->version += 1;
        $oldwarehouseout->status = "deleted";
        $oldwarehouseout->save();
        if(!$oldwarehouseout->paidtrans_ids && $oldwarehouseout->paidtrans_ids!= '')
        {
            SupTransaction::updatePaidAmount(1,$dout->paid_amount ,$dout->customer_id); 
        }
      
      
        return redirect()->route('warehouseout.index')->with('success','Xóa thành công!'); 
    }
    public function warehouseoutPaid($id)
    {
        // return $id;
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $wo = Warehouseout::find($id);
         
        if( $wo)
        {
             $bankaccounts = Bankaccount::where('status','active')->get();
             $active_menu="wo_list";
             
             $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
             <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Ds bán hàng</a></li>
             <li class="breadcrumb-item active" aria-current="page">  </li>';
             return view('backend.warehouseouts.paid',compact('wo','breadcrumb','bankaccounts','active_menu'));
             
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function warehouseoutSavePaid(Request $request)
    {
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'numeric|required',
             
        ]);
        $data = $request->all();
        $wi = Warehouseout::find($data['id']);
        $user = auth()->user();
       
        if( $wi)
        {
             ///create paid transaction
            
            if($data['paid_amount'] > $wi->final_amount - $wi->paid_amount)
            {
                return back()->with('error','Số tiền trả lớn hơn số tiền nợ!');
            }
            if($data['paid_amount'] <=0 || $wi->is_paid == 1)
            {
                return back()->with('error','Số tiền trả không hợp lệ!');
            }
            $bankaccount = Bankaccount::find($data['bank_id']);
           
            
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'], 1,$wi->id,'wo',$data['paid_amount']);
            $sup = SupTransaction::createSubTrans($bank_doc->id,'fi', 1, $data['paid_amount'], $wi->customer_id); 
            $wi->paid_amount += $data['paid_amount'];
            if($wi->paid_amount == $wi->final_amount)
                $wi->is_paid = true;

              //save ids in paid_ids
              $in_ids = array();
              if($wi->paidtrans_ids )
              {
                  $in_ids = json_decode($wi->paidtrans_ids);
              }
              $in_id = new \App\Models\Number();
              $in_id->id = $bank_doc->id;
              array_push($in_ids,$in_id);
              $wi->paidtrans_ids = json_encode($in_ids);
              
            $wi->save();
            ///create log /////////////
            $user = auth()->user();
            // $content = 'paid money for selling invoice: '.$data['id'].' total: '.$data['paid_amount'];
            // \App\Models\Log::insertLog($content,$user->id);
            $content = 'trả tiền cho đơn bán hàng' ;
            \App\Models\Log::insertLogNew($content,$sup->id,'supwo',$user->id);
  
            return redirect()->route('warehouseout.index')->with('success','Đã thêm thanh toán cho phiếu bán hàng!');
            
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function warehouseoutReturn(Request $request )
    {
        //
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            
        ]);
        $id = $request->id;
        $oldwarehouseout = Warehouseout::find( $id);
        // return $oldwarehousein;
        if( $oldwarehouseout==null || $oldwarehouseout->status == 'returned' )
            return back()->with('error','Không tìm thấy phiếu nhập kho!');
        $user = auth()->user();
        //check detail product are exported
        $detailpros = WarehouseoutDetail::where('wo_id', $id)->where('doc_type','wo')->get();
        
        //return all old product detail
        $dout = \App\Models\Warehouseout::log_change($oldwarehouseout);
        foreach($detailpros as $dtpro)
        {
            WarehouseOutDetail::returnDetailPro($dtpro,$oldwarehouseout->cost_extra,$oldwarehouseout->wh_id, $dout->id );
        }
        ///add return sup trans 1 for importing
        // $sps = SupTransaction::createSubTrans($oldwarehouseout->id,'wo',+1,$oldwarehouseout->final_amount, $oldwarehouseout->customer_id);
        SupTransaction::removeSubTrans($oldwarehouseout->suptrans_id,'wor',$dout->id);
         ////delete series for each product
        $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
        foreach($wo_series as $wo_seri)
        {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                \DB::select($query);
        }
        $sql = "delete from warehouseout_detail_series where doc_type='wo' and wo_id=". $oldwarehouseout->id;
        \DB::select($sql);

        
        ///
        $oldwarehouseout->version += 1;
        $oldwarehouseout->status = 'returned';
        $oldwarehouseout->save();
       //lay gia tri paid amount cap nhat lai cac don (1 la huy don xuat)
       SupTransaction::updatePaidAmount(1,$dout->paid_amount,$dout->customer_id); 
     
         

        $content = 'trả đơn bán hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehouseout->id,'rewo',$user->id);
       
       
        return redirect()->route('warehouseout.index')->with('success','Trả hàng thành công!');

    }
    public function print_invoice($id)
    {
        $warehouseout = \App\Models\Warehouseout::find($id);
        $wo_details = \App\Models\WarehouseoutDetail::where('wo_id',$id)->where('doc_type','wo')->get();
        foreach($wo_details as $wi_detail)
        {
            $series = "";
            $i = 0;
            $wo_seris = \DB::select("select seri from warehouseout_detail_series where wo_id =".$wi_detail->wo_id ." and product_id = ".$wi_detail->product_id );
            foreach($wo_seris as $wo_seri)
            {
                if ($i > 0)
                    $series .= ",";
                $series .= $wo_seri->seri;
                $i ++;
            }
            $wi_detail->series = $series;
        }
        $sup_trans = \App\Models\SupTransaction::where('doc_type','wo')->where('doc_id',$warehouseout->id)->first();
        $paid_amount = $warehouseout->paid_amount;
        $amount_before_paid = $sup_trans->total  ;
        $amount_before_trans =  $sup_trans->total - $sup_trans->operation* $sup_trans->amount;
        $amount_after_trans = $sup_trans->total  +  $paid_amount;
        $buyer =  \App\Models\User::find($warehouseout->customer_id);
        $amount_after_trans = $buyer->budget;
       
        $html =  view('backend.warehouseouts.show_p',compact('warehouseout','wo_details','amount_before_paid','amount_before_trans','amount_after_trans'))->render();
        return $html;
        $html = '<div id="divprint" class="intro-y box overflow-hidden mt-5">
        <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
            <div class="px-1 py-2 sm:px-1 sm:py-2">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; vertical-align:top" class="text-left">
                        <div class="text-primary font-semibold text-2xl">PHIẾU BÁN HÀNG</div>
                        <div class="mt-2"> Mã: <span class="">'.$warehousein->code.'</span> </div>
                        <div class="mt-2"> Mã điện tử: <span class="">'.$warehousein->uiid .'</span> </div>
                                
                        <div class="mt-1">Ngày lập: '.$warehousein->created_at.'</div>
                         
                        </td>';
                 
                         $detail = \App\Models\SettingDetail::find(1);  
                         $html .= '
                        <td style="width: 50%; vertical-align:top" class="text-center">
                        <div class="text-primary font-semibold text-2xl">'.$detail->company_name.'</div>
                        <div class="mt-2">   ' .$detail->phone. '-'.$detail->address.'</span> </div>
                        
                        <style>
                            .divclass {
                            display: flex;
                            justify-content: center;
                            
                            }
                        </style>
                        <div class="mt-1 justify-center divclass" style=" margin: auto;" >
                                <img src="'.$detail->logo.'" style="width:50px;"> 
                        </div>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%" class="text-left">
                        <div >
                            <div class="text-base text-slate-500">Khách hàng</div>
                                <div class="text-lg  text-primary mt-2">
                                   '. \App\Models\User::where('id',$warehousein->customer_id)->value('full_name') .'
                                </div>
                                <div class="mt-1">'. \App\Models\User::where('id',$warehousein->customer_id)->value('phone'). '</div>
                                <div class="mt-1">'. \App\Models\User::where('id',$warehousein->customer_id)->value('address'). '</div>
                            </div>
                        </td>
                        <td class="text-right">
                        <div  >
                            <div class="text-base text-slate-500">Kho xuất hàng</div>
                            <div class="text-lg  text-primary mt-2">
                            '. \App\Models\Warehouse::where('id',$warehousein->wh_id)->value('title') .'
                            </div>
                            <div class="mt-1">' . \App\Models\User::where('id',$warehousein->vendor_id)->value('full_name') .

                            '</div>
                        </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="px-1 py-2 sm:px-1 sm:py-2">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> STT </th>
                            <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 ">Hàng hóa</th>
                            <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right ">Số lượng</th>
                            <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right ">Đơn giá</th>
                            <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right ">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>';
                         $i = 1; 
                        foreach ($wi_details as $wi )
                        {

                           $html .= '
                            <tr>
                                <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> 
                                    '.$i.'
                                </td>
                                <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b dark:border-darkmode-400">
                                    <div class="  ">
                                '. \App\Models\Product::where('id', $wi->product_id)->value('title')   .
                                ' </div>
                                </td>
                                <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                    '. $wi->quantity .'
                                </td>
                                <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                    '. number_format($wi->price, 0, '.', ',') .'
                                </td>
                                <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400   ">
                                '. number_format(($wi->quantity*$wi->price), 0, '.', ','). '
                                </td>
                            </tr>';
                            if ($wi->series != '')
                                $html .= '<tr><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "></td><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="4">số seri:'.$wi->series.'</td></tr>';
                            $i++;
                        }
                        $html .= '  
                       
                    </tbody>
                    <tfooter>
                        <tr>
                            <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="2">
                                <span class=" "> 
                                    Giảm giá:  '. number_format($warehousein->discount_amount, 0, '.', ',') .'
                                </span> 
                            </td>
                            <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="2" class="text-right  ">
                                Tổng:
                            </td>
                            <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right ">
                                '.number_format($warehousein->final_amount, 0, '.', ',') .'
                            </td>
                        </tr>
                        <tr>
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right" colspan="4">
                            Nợ cũ:
                        </td>
                        
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right  ">
                            '.number_format(-1*($amount_before_trans), 0, '.', ',').'
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right" colspan="4">
                           Phải thanh toán:
                        </td>
                        
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right  ">
                            '.number_format(-1*($amount_before_paid ), 0, '.', ',').'
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right" colspan="4">
                           Đã thanh toán:
                        </td>
                        
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right  ">
                            '.number_format($warehousein->paid_amount, 0, '.', ',') .'
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right" colspan="4">
                           Nợ hiện tại:
                        </td>
                        
                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right ">
                            '.number_format(-1*($amount_after_trans ), 0, '.', ',').'
                        </td>
                    </tr>
                    </tfooter>
                </table>
            </div>
        </div>
        <div class="px-1 py-2 sm:px-1 sm:py-2">
            <table style="width:100%">
                <tr>
                    <td style="width:50%">
                        <div class="text-center sm:text-left mt-1 sm:mt-0">
                            <div class="text-base  ">Người lập</div>
                            <div class="mt-1">
                                
                                <br/>'.
                             \App\Models\User::where('id',auth()->user()->id)->value('full_name').'
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-center sm:text-right sm:ml-auto" >
                            <div class="text-base  "> </div>
                            <div class="text-xl text-primary  mt-1">  </div>
                        </div>
                        
                    </td>
                </tr>
                </table>
            </div>
        </div>';
        return $html;
    }
    public function warehouseoutReturnNew(Request $request )
    {
        //
        $this->validate($request,[
            'id'=>'numeric|required',
            
        ]);
        $id = $request->id;
         
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        $oldwarehouseout = Warehouseout::find( $id);
        // return $oldwarehousein;
        if( $oldwarehouseout==null || $oldwarehouseout->status == 'returned' )
            return back()->with('error','Không tìm thấy phiếu nhập kho!');
        $user = auth()->user();
        //check detail product are exported
        $detailpros = WarehouseoutDetail::where('wo_id', $id)->where('doc_type','wo')->get();
        //return all old product detail
        $dout = \App\Models\Warehouseout::log_change($oldwarehouseout);
        foreach($detailpros as $dtpro)
        {
            WarehouseOutDetail::returnDetailPro($dtpro,$oldwarehouseout->cost_extra,$oldwarehouseout->wh_id,$dout->id);
        }
        ///add return sup trans 1 for importing
        // $sps = SupTransaction::createSubTrans($oldwarehouseout->id,'wo',+1,$oldwarehouseout->final_amount, $oldwarehouseout->customer_id);
        SupTransaction::removeSubTrans($oldwarehouseout->suptrans_id,'wor',$dout->id);
        ///
       ////delete series for each product
        $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
        foreach($wo_series as $wo_seri)
        {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                \DB::select($query);
        }
        $sql = "delete from warehouseout_detail_series where doc_type='wo' and wo_id=". $oldwarehouseout->id;
        \DB::select($sql);
        
        $oldwarehouseout->version += 1;
        $oldwarehouseout->status = 'returned';
        $oldwarehouseout->save();

        //lay gia tri paid amount cap nhat lai cac don (1 la huy don xuat)
        SupTransaction::updatePaidAmount(1,$dout->paid_amount,$dout->customer_id); 
       
        $content = 'trả đơn bán hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehouseout->id,'rewo',$user->id);
        // //////////tao view don hang moi de luu /////////////////
        
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
         
        $warehouseout = Warehouseout::find($id);
        if($warehouseout && $warehouseout->status == 'returned')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">tạo phiếu bán hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
            $user = auth()->user();
            $ship_amount = 0;
            if($warehouseout->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehouseout->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }  
            return view('backend.warehouseouts.returnedit',compact('breadcrumb','warehouseout','active_menu','warehouses','bankaccounts','user', 'deliveries','ship_amount'));
        }
        else
            return redirect()->route('warehouseout.index')->with('success','Trả hàng thành công!');

    }
    
    public function warehouseoutNew($id )
    {
        // //////////tao view don hang moi de luu /////////////////
        
        $func = "warout_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
         
        $warehouseout = Warehouseout::find($id);
        if($warehouseout  )
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">tạo phiếu bán hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
            $user = auth()->user();
            $ship_amount = 0;
            $bank_id = 0;
            if($warehouseout->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehouseout->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }  
            return view('backend.warehouseouts.copy',compact('breadcrumb','warehouseout','active_menu','warehouses','bankaccounts','user', 'deliveries','ship_amount','bank_id'));
        }
        else
            return redirect()->route('warehouseout.index')->with('error','Không tìm thấy!');

    }
    public function warehouseoutReturndetail(Request $request)
    {
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            
        ]);
        $id = $request->id;
        $warehouseout = Warehouseout::find($id);
        if($warehouseout && $warehouseout->status == 'active')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> trả hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
        
            $paid_trans = null;
            $ship_trans = null;
            $bank_id = 0;
            $ship_amount = 0;
            if($warehouseout->paidtrans_ids)
            {
                $id_ins = json_decode($warehouseout->paidtrans_ids); 
                $id_in = $id_ins[0];
                $paidtrans = BankTransaction::where('id',$id_in->id)->first();
                $bank_id = $paidtrans->bank_id;
            }   
            if($warehouseout->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehouseout->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }   
            $user = auth()->user();
            
            return view('backend.warehouseouts.returndetail',compact('breadcrumb','warehouseout','active_menu','warehouses','bankaccounts','user','bank_id','ship_amount','deliveries'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }

    }
    public function warehouseoutReturnall(Request $request )
    {
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            
        ]);
        $id = $request->id;
        $wo = Warehouseout::find( $id);
        if($wo && $wo->status == 'active')
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouseout.index').'">Danh sách bán hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> trả hàng hoàn tiền </li>';
             $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $user = auth()->user();
            return view('backend.warehouseouts.returnpaid',compact('breadcrumb','wo','active_menu', 'bankaccounts','user'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function warehouseoutDestroyReturndetail(Request $request )
    {
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $oldwarehousein = \App\Models\Warehouseout::find($request->id);
        // return $oldwarehousein;
        
        if( $oldwarehousein->status != 'returned')
            return response()->json(['msg'=>'không tìm thấy!' ,'status'=>false]);
        
        $user = auth()->user();
       
        //check detail product are exported
        $detailpros = \App\Models\WarehouseInDetail::where('doc_id',$request->id)->where('doc_type','wr')->get();
        $flag = 0;
        foreach($detailpros as $dtpro)
        {
            if($dtpro->qty_sold > 0)
                $flag = 1;
            
        }
        if($flag == 1)
        {
            return response()->json(['msg'=>'Đã xuất kho hàng hóa trong phiếu nhập!','status'=>false]);
        }
        //kiem tra co nhieu giao dich rôi ko edit nua vi luc cap nhat se luu so tien da tra vào tk ngân hàng ko đúng so với trước kia
   
        /******thử nghiệm ko kiểm tra nhiều giao dịch cho edit  */
        /*    $bank_docs = BankTransaction::where('doc_id',$oldwarehousein->id)
            ->where('doc_type','wr')->get();
        $sum_paid = 0;
        foreach ($bank_docs as $bank_doc)
        {
            $sum_paid += $bank_doc->total;
        }
        if($sum_paid != $oldwarehousein->paid_amount )
        {
            return response()->json(['msg'=>'Đã có nhiều giao dịch trả tiền cho phiếu nhập hàng. Không thể thay đổi thông tin!','status'=>false]);
        }
            */
            /******end  thử nghiệm ko kiểm tra nhiều giao dịch cho edit  */
        ////check detail ////////////
        
        
        ////////////////
        //delete all old product detail
      
        //tao mot ham log_chang cho return sau
        $returned_ids  = json_decode($oldwarehousein->returned_ids);
        $returned_id = $returned_ids[0]->id;
        $woold = Warehouseout::find($returned_id);
       
       
        $din = \App\Models\WarehouseOut::log_change($oldwarehousein);
        foreach($detailpros as $dtpro)
        {
            $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$woold->id)->where('doc_type','wo')->where('product_id', $dtpro->product_id)->first();
            $wo_detail->qty_returned -= $dtpro->quantity ;
            $wo_detail->save();

            \App\Models\WarehouseInDetail::deleteDetailProVersion($dtpro,$oldwarehousein->cost_extra,$oldwarehousein->wh_id,$din->id);
        }
        ///delete sup trans 1 for importing
        SupTransaction::removeSubTrans($oldwarehousein->suptrans_id,'wrr',$din->id);

        ///
         ///delete paid transaction
        ///delete paid transaction
        ///tra lai tien cho cua hang xoa het bantrans, neu chua du thì tra vao budget
        $total_return = 0;
        if($oldwarehousein->paidtrans_ids)
        {
            $in_ids = json_decode($oldwarehousein->paidtrans_ids);
            foreach ($in_ids as $in_id)
            {
                $bank_doc = BankTransaction::find( $in_id->id );
                if($bank_doc)
                {
                    $total_return+= $bank_doc->total;
                    $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                    if($suptrans)
                    {
                        $fre_id_huy = BankTransaction::removeBankTrans($bank_doc);
                        SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                    }    
                   
                }
            }
        }
        
        ///delete ship invoice
         
        if($oldwarehousein->shiptrans_id)
        {
            $fts = FreeTransaction::find($oldwarehousein->shiptrans_id);
            if($fts)
            {
                $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                if($banktrans)
                    BankTransaction::removeBankTrans($banktrans);
                $fts->delete();
            }
        }
        ////delete old series
        ////add series for each product
        $sql = "delete from warehousein_detail_series where doc_type='wr' and wi_id=". $oldwarehousein->id;
        \DB::select($sql);
      
        
        $oldwarehousein->status='deleted';
       $oldwarehousein->version+= 1;
       $oldwarehousein->save();
       ///create log /////////////
        ///create log /////////////
        $content = 'xóa phiếu trả hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehousein->id,'wr',$user->id);
       return redirect()->route('warehouseout.index')->with('sucess','Xóa thành công!');
    }
    public function warehouseoutUpdateReturndetail(Request $request )
    {
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'importDoc.final_amount'=>'numeric|required',
            'importDoc.discount_amount'=>'numeric|nullable',
            'importDoc.shipcost'=>'numeric|nullable',
            'importDoc.paid_amount'=>'numeric|required',
        ]);

        
        $data = $request->importDoc;
        if ($data['discount_amount'] == null)
            $data['discount_amount']=0;
        if ($data['shipcost'] == null)
            $data['shipcost']=0;

        
       
            
        if($data['paid_amount']< $data['shipcost'] )
        {
            return response()->json(['msg'=>'Số tiền trả nhỏ hơn số tiền ship nhận hàng!','status'=>false]);
        }

        $oldwarehousein = \App\Models\Warehouseout::find($data['id']);
        // return $oldwarehousein;
        
        if($data['id']==null || $data['id']==0 || $oldwarehousein==null || $oldwarehousein->status != 'returned')
            return response()->json(['msg'=>'không tìm thấy!'.$data['id'],'status'=>false]);
       
       
       
        
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        //dd($bank);
        if($data['paid_amount']< $data['shipcost'] )
        {
            return response()->json(['msg'=>'Số tiền trả nhỏ hơn số tiền ship nhận hàng!','status'=>false]);
        }
        if(!$bank || $bank->total < $data['paid_amount'] )
        {
            return response()->json(['msg'=>'Số tiền trong tài khoản không đủ thực hiện!','status'=>false]);
        }
        //tru tien ship de luu don hang dung voi nha cung cap
        $data['paid_amount'] -= $data['shipcost'];
        $data['final_amount'] -= $data['shipcost'];
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        
        //check detail product are exported
        $detailpros = \App\Models\WarehouseInDetail::where('doc_id',$data['id'])->where('doc_type','wr')->get();
        $flag = 0;
        foreach($detailpros as $dtpro)
        {
            if($dtpro->qty_sold > 0)
                $flag = 1;
            
        }
        if($flag == 1)
        {
            return response()->json(['msg'=>'Đã xuất kho hàng hóa trong phiếu nhập!','status'=>false]);
        }
        //kiem tra co nhieu giao dich rôi ko edit nua vi luc cap nhat se luu so tien da tra vào tk ngân hàng ko đúng so với trước kia
   
        /******thử nghiệm ko kiểm tra nhiều giao dịch cho edit  */
        /*    $bank_docs = BankTransaction::where('doc_id',$oldwarehousein->id)
            ->where('doc_type','wr')->get();
        $sum_paid = 0;
        foreach ($bank_docs as $bank_doc)
        {
            $sum_paid += $bank_doc->total;
        }
        if($sum_paid != $oldwarehousein->paid_amount )
        {
            return response()->json(['msg'=>'Đã có nhiều giao dịch trả tiền cho phiếu nhập hàng. Không thể thay đổi thông tin!','status'=>false]);
        }
            */
            /******end  thử nghiệm ko kiểm tra nhiều giao dịch cho edit  */
        ////check detail ////////////
        $olddetails = \App\Models\WarehouseInDetail::where('doc_id',$oldwarehousein->id)->where('doc_type','wr')->get();
        //update old warehouseindetail to sold
        foreach($olddetails as $olddetail)
        {
            \DB::select('update warehousein_detail_series set is_sold = 1 where wi_id ='.
                 $oldwarehousein->id . ' and doc_type="wr" and product_id = '. $olddetail->product_id);
        }
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            //check new series has been warehouse, exclude the old ones
            $series =  explode(",",  $detail['seri']); 
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            if($count_n!= 0 && count($series ) != $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số series '.$count_n.' khác số số lượng trong đơn'.$detail['quantity'] .'!','status'=>false]);
                 
            }
            foreach ($series as $seri)
            {
                if (\App\Models\WarehouseinDetailSeries::check_seri_in_avaible($seri,$detail['id'],$data['wh_id']))
                {
                     //if exits update old warehouseindetail to un sold and return false
                    foreach($olddetails as $olddetail)
                    {
                        \DB::select('update warehousein_detail_series set is_sold = 0 where wi_id ='.
                            $oldwarehousein->id . ' doc_type="wr" and product_id = '. $olddetail->product_id);
                    }
                    return response()->json(['msg'=>'Số seri '.$seri.' đã có!','status'=>false]);
                }    
                if ($seri == '')
                    continue;
               
            }
        }
        
        ////////////////
        //delete all old product detail
      
        //tao mot ham log_chang cho return sau
        $returned_ids  = json_decode($oldwarehousein->returned_ids);
        $returned_id = $returned_ids[0]->id;
        $woold = Warehouseout::find($returned_id);
       
       
        $din = \App\Models\WarehouseOut::log_change($oldwarehousein);
        foreach($detailpros as $dtpro)
        {
            $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$woold->id)->where('doc_type','wo')->where('product_id', $dtpro->product_id)->first();
            $wo_detail->qty_returned -= $dtpro->quantity ;
            $wo_detail->save();

            \App\Models\WarehouseInDetail::deleteDetailProVersion($dtpro,$oldwarehousein->cost_extra,$oldwarehousein->wh_id,$din->id);
        }
        ///delete sup trans 1 for importing
        SupTransaction::removeSubTrans($oldwarehousein->suptrans_id,'wrr',$din->id);

        ///
         ///delete paid transaction
        ///delete paid transaction
        ///tra lai tien cho cua hang xoa het bantrans, neu chua du thì tra vao budget
        $total_return = 0;
        if($oldwarehousein->paidtrans_ids)
        {
            $in_ids = json_decode($oldwarehousein->paidtrans_ids);
            foreach ($in_ids as $in_id)
            {
                $bank_doc = BankTransaction::find( $in_id->id );
                if($bank_doc)
                {
                    $total_return+= $bank_doc->total;
                    $suptrans = SupTransaction::where('doc_id',$bank_doc->id)->where('doc_type','fi')->first();
                    if($suptrans)
                    {
                        $fre_id_huy = BankTransaction::removeBankTrans($bank_doc);
                        SupTransaction::removeSubTrans( $suptrans->id,'fo',$bank_doc->id);
                    }    
                   
                }
            }
        }
        
        ///delete ship invoice
         
        if($oldwarehousein->shiptrans_id)
        {
            $fts = FreeTransaction::find($oldwarehousein->shiptrans_id);
            if($fts)
            {
                $banktrans = BankTransaction::where('doc_type','fi')->where('doc_id',$fts->id)->first();
                if($banktrans)
                    BankTransaction::removeBankTrans($banktrans);
                $fts->delete();
            }
        }
        ////delete old series
        ////add series for each product
        $sql = "delete from warehousein_detail_series where doc_type='wr' and wi_id=". $oldwarehousein->id;
        \DB::select($sql);
      
        
        ///save new product detail ////////////
        ////kiem tra budget co dang no tien của hang ko, co thi bù trừ giá trị đơn nhập (- là thiếu tiền cửa hàng)///////////////////
        $totalbankpaid = $data['paid_amount'];
        $totalbudgetpaid = 0;
        $customer = \App\Models\User::find($data['customer_id']);
        $deb_before = $customer->budget;
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
        if($data['paid_amount'] == $data['final_amount'])
            $data['is_paid'] = 1;
        else
            $data['is_paid'] = 0;


        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['shipcost'] -  $data['discount_amount'])/ $count_item ;
        $data['cost_extra'] = $cost_extra;

        
         //update sysaccount
        // $oldwarehousein->s_update_final_amount( $data['final_amount']);
        /////
        $data['bankpayment'] = $totalbankpaid;
        $data['debtbefore'] = $deb_before;
        $data['debtafter'] =  $deb_before + $data['final_amount'];

        $status = $oldwarehousein->fill($data)->save();
        //////////////////create detail //////////////////
        foreach ($details as $detail)
        {
            if($detail['quantity']<=0)
                continue;
            $product_detail['doc_id'] = $oldwarehousein->id;
            $product_detail['doc_type'] = 'wr';
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            $product_detail['wh_id'] = $data['wh_id'];
            $inv = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id'])
                ->first();
            if($inv)
                $product_detail['prebalance'] =$inv->quantity;
            else
                $product_detail['prebalance'] = 0;
             //save expired days
             $product = Product::find($detail['id']);
             $start_date = date('Y-m-d H:i:s');
             if($product->expired)
             {
                 $strday = '+' . $product->expired*30 .' days';
                 $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                 $product_detail['expired_at'] = $end_date;
             }
             $product_detail['is_seri'] = $count_n>0?1:0;
            //  return $product_detail;
            \App\Models\WarehouseInDetail::create($product_detail);
            //increase stock
            Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,$cost_extra);
            ///update group price//////
            $product_prices = $detail['pricelist'];
            foreach ($product_prices as $product_price)
            {
                \App\Models\GroupPrice::updateProductPriceId($product_price['gpid'],$product_price['price']);
            }

             ////add series for each product
             $series =  explode(",",  $detail['seri']); 
             foreach ($series as $seri)
             {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                \App\Models\WarehouseinDetailSeries::c_create($oldwarehousein->id,$seri, $detail['id'],'wr',$data['wh_id']);
             }

             $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$woold->id)->where('doc_type','wo')->where('product_id', $product_detail['product_id'])->first();
             $wo_detail->qty_returned += $product_detail['quantity'];
             $wo_detail->save();
 

        }
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($oldwarehousein->id,'wr',1,$data['final_amount'], $data['customer_id']);
        $oldwarehousein->suptrans_id = $sps->id;
        ///create paid transaction
        if($totalbankpaid> 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$oldwarehousein->id,'wr',$totalbankpaid);
            SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $totalbankpaid, $data['customer_id']); 
            // $oldwarehousein->paidtrans_id = $bank_doc->id;
            $in_ids=array();
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $oldwarehousein->paidtrans_ids = json_encode($in_ids);
        }
       ///create ship invocie ///////////
       if($data['shipcost'] > 0)
       {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $oldwarehousein->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
       }
      
       $oldwarehousein->version+= 1;
       $oldwarehousein->save();
       ///create log /////////////
        ///create log /////////////
        $content = 'cập nhật phiếu trả hàng' ;
        \App\Models\Log::insertLogNew($content,$oldwarehousein->id,'wr',$user->id);
       return response()->json(['msg'=>'Cập nhật thành công!','status'=>true]);
    }
    public function warehouseoutSaveReturndetail(Request $request )
    {
        //

        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'importDoc.final_amount'=>'numeric|required',
            'importDoc.discount_amount'=>'numeric|nullable',
            'importDoc.shipcost'=>'numeric|nullable',
            'importDoc.paid_amount'=>'numeric|required',
        ]);
        $data = $request->importDoc;
        if ($data['discount_amount'] == null)
            $data['discount_amount']=0;
        if ($data['shipcost'] == null)
            $data['shipcost']=0;

        $customer = \App\Models\User::find($data['customer_id']);
        
        $deb_before = $customer->budget;

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
        
        if($data['paid_amount'] == $data['final_amount'])
            $data['is_paid'] = 1;
        else
            $data['is_paid'] = 0;
       
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        //dd($bank);
        if($data['paid_amount']< $data['shipcost'] )
        {
            return response()->json(['msg'=>'Số tiền trả nhỏ hơn số tiền ship nhận hàng!','status'=>false]);
        }
        if((!$bank || $bank->total <$totalbankpaid  )   )
        {
            return response()->json(['msg'=>'Số tiền trong tài khoản không đủ thực hiện!','status'=>false]);
        }
        //
        // return $request->all();
       
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
       
        //tru tien ship de luu don hang dung voi nha cung cap
        $data['paid_amount'] -= $data['shipcost'];
        $data['final_amount'] -= $data['shipcost'];
        ///save product detail ////////////
        ////check detail//////////////////
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
            $series =  explode(",",  $detail['seri']); 
            $count_n =0;
            if($detail['seri']!= '')
            {
                $count_n =count($series );
            }
            if($count_n!= 0 && count($series ) != $detail['quantity'] )
            {
                return response()->json(['msg'=>'Số series '.$count_n.' khác số số lượng trong đơn'.$detail['quantity'] .'!','status'=>false]);
                 
            }

            foreach ($series as $seri)
            {
                if (\App\Models\WarehouseinDetailSeries::check_seri_in_avaible($seri,$detail['id'],$data['wh_id']))
                    return response()->json(['msg'=>'Số seri '.$seri.' đã có!','status'=>false]);
                if ($seri == '')
                    continue;
               
            }
        }
        $cost_extra = ($data['shipcost'] -  $data['discount_amount'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        $data['status']  = 'returned';
        $woold = Warehouseout::find($data['id']);

        $data['bankpayment'] = $totalbankpaid;
        $data['debtbefore'] = $deb_before;
        $data['debtafter'] =  $deb_before + $data['final_amount'];
        $in_ids=array();
        $in_id = new \App\Models\Number();
        $in_id->id = $woold->id;
        array_push($in_ids,$in_id);
        $data['returned_ids']   = json_encode($in_ids);
        $wi = Warehouseout::c_create($data);

        $in_ids=array();
        $in_id = new \App\Models\Number();
        $in_id->id = $wi->id;
        array_push($in_ids,$in_id);
        $woold->returned_ids = json_encode($in_ids);
        $woold->save();

       
        // return $wi;
        ///////////////////create detail /////////////////
        foreach ($details as $detail)
        {
            if( $detail['quantity'] <= 0)
                continue;
            $product_detail['doc_id'] = $wi->id;
            $product_detail['doc_type'] = 'wr';
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            $product_detail['wh_id'] = $data['wh_id'];
            $inv = \App\Models\Inventory::where('product_id',$detail['id'])
                ->where('wh_id',$data['wh_id'])
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
                $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $product_detail['is_seri'] = $count_n>0?1:0;
            //  return $product_detail;
            \App\Models\WarehouseInDetail::create($product_detail);
            //increase stock
            Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,$cost_extra);
            ///update group price//////
            $product_prices = $detail['pricelist'];
            foreach ($product_prices as $product_price)
            {
                $product_price['price'] = intval($product_price['price'] );
                \App\Models\GroupPrice::updateProductPriceId($product_price['gpid'],$product_price['price']);
            }
            ////add series for each product
            $series =  explode(",",  $detail['seri']); 
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                    continue;
                \App\Models\WarehouseinDetailSeries::c_create($wi->id,$seri, $detail['id'],'wr',$data['wh_id']);
               

            
            }
            $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$woold->id)->where('doc_type','wo')->where('product_id', $product_detail['product_id'])->first();
            $wo_detail->qty_returned += $product_detail['quantity'];
            $wo_detail->save();
        }

        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($wi->id,'wr',1,$data['final_amount'], $data['customer_id']);
        $wi->suptrans_id = $sps->id;
        ///create paid transaction
        if($totalbankpaid> 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$wi->id,'wr',$totalbankpaid);
            SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $totalbankpaid, $data['customer_id']); 
            $in_ids=array();
            $in_id = new \App\Models\Number();
            $in_id->id = $bank_doc->id;
            array_push($in_ids,$in_id);
            $wi->paidtrans_ids = json_encode($in_ids);
        }
       ///create ship invocie ///////////
       if($data['shipcost'] > 0)
       {
            $fts= FreeTransaction::addFreeTrans($data['shipcost'],$data['bank_id'],-1,'ship',$user->id);
            $wi->shiptrans_id = $fts->id;
            BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$fts->id,'fi',$data['shipcost']);
       }
       
       $wi->save();
       ///create log /////////////
       $content = 'thêm phiếu trả hàng' ;
       \App\Models\Log::insertLogNew($content,$wi->id,'wr',$user->id);
       return response()->json(['msg'=>'Thêm đơn nhập kho thành công!','status'=>true]);

 
    }

    public function warehouseoutSaveReturnall(Request $request )
    {
        //
        $func = "warout_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'bank_id'=>'numeric|required',
            'paid_amount'=>'numeric|required',
        ]);
        $data = $request->all();
        $id = $request->id;
        $oldwarehouseout = Warehouseout::find( $id);
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        if(   $data['paid_amount'] <= 0)
            return back()->with('error','Số tiền phải lớn hơn 0!');
        if(   $data['paid_amount'] > $oldwarehouseout->paid_amount)
            return back()->with('error','Số tiền không thể lơn hơn số tiền đã trả!');
        if( $bank==null || $bank->total < $data['paid_amount'])
            return back()->with('error','Tiền tài khoản không đủ để thực hiện!');
        // return $oldwarehousein;
        if( $oldwarehouseout==null || $oldwarehouseout->status != 'active' )
            return back()->with('error','Không tìm thấy phiếu nhập kho!');
        $user = auth()->user();
        //check detail product are exported
        $detailpros = WarehouseoutDetail::where('wo_id', $id)->where('doc_type','wo')->get();
        
        //return all old product detail
        $dout = \App\Models\Warehouseout::log_change($oldwarehouseout);
        foreach($detailpros as $dtpro)
        {
            WarehouseOutDetail::returnDetailPro($dtpro,$oldwarehouseout->cost_extra,$oldwarehouseout->wh_id,$dout->id);
        }
        ///add return sup trans 1 for importing
        // $sps = SupTransaction::createSubTrans($oldwarehouseout->id,'wo',1,$oldwarehouseout->final_amount, $oldwarehouseout->customer_id);
        SupTransaction::removeSubTrans($oldwarehouseout->suptrans_id,'wor',$dout->id);
        ///add return money sup
        if($data['paid_amount'] > 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'], -1,$oldwarehouseout->id,'wo',$data['paid_amount']);
            SupTransaction::createSubTrans($bank_doc->id,'fi', -1, $data['paid_amount'], $oldwarehouseout->customer_id); 
         
        }

        ////delete series for each product
        $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldwarehouseout->id)->where('doc_type','wo')->get();
        foreach($wo_series as $wo_seri)
        {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                \DB::select($query);
        }
        $sql = "delete from warehouseout_detail_series where doc_type='wo' and wo_id=". $oldwarehouseout->id;
        \DB::select($sql);
        
        /////
        
        $oldwarehouseout->version += 1;
        $oldwarehouseout->status = 'returned';
        $oldwarehouseout->save();
        //lay gia tri paid amount cap nhat lai cac don (1 la huy don xuat)
        SupTransaction::updatePaidAmount(1,$dout->paid_amount - $data['paid_amount'],$dout->customer_id); 
    
       ///create log /////////////
    //    $content = 'return warehouseout and paid id : '. $id.' total: '.$oldwarehouseout->final_amount;
    //    \App\Models\Log::insertLog($content,$user->id);

       $content = 'trả đơn bán hàng và hoàn tiền' ;
       \App\Models\Log::insertLogNew($content,$oldwarehouseout->id,'rewo',$user->id);
      
       return redirect()->route('warehouseout.index')->with('success','Trả hàng thành công!');
    }
    public function get_out_view()
    {
        $month = date('m');
    }
}
