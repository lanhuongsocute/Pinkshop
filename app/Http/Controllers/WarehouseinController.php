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
use App\Models\UGroup;
use Illuminate\Support\Str;
class WarehouseinController extends Controller
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
    public function add_einvoice(Request $request)
    {
        $helpController = new \App\Http\Controllers\HelpController();
        $re = $helpController->store_invoice($request->uiid);
        if(!$re)
        {
            return back()->with('error','Không lấy được thông tin!');
        }
        
        if($re['success']==false)
        {
            return back()->with('error',$re['message']);
        }
        $data = json_decode($re['data']);
        // dd($data);
        if($data->supplier_phone =='' )
        {
            return back()->with('error','Đối tác không có số điện thoại!');
        }
        if($data->supplier_email =='' )
        {
            $data->supplier_email = $data->supplier_phone.'@gmail.com';
        }
        $supplier = \App\Models\User::where('global_id',$data->invoice_id)->first();
        if(!$supplier)
        {
            $supplier = \App\Models\User::where('full_name',$data->supplier_name)->orWhere('phone',$data->supplier_phone)->orWhere('email',$data->supplier_email)->first();
        }
        if(!$supplier)
        {
            $supp['full_name'] = $data->supplier_name;
            $supp['phone'] = $data->supplier_phone; 
            $supp['address'] = $data->supplier_address;
            $supp['email']= $data->supplier_email;
            if ( $supp['email']=='')
                $supp['email']= $supp['phone'].'@gmail.com';
            $supp['role']='supplier';
            $supp['status']='inactive';
            $supp['password'] = '1';
            $supp['global_id'] = $data->invoice_id;
            $supp['username'] = $data->supplier_phone;
            $supplier = \App\Models\User::create($supp);
        }
        // dd($supplier);
        $products = $data->products;
        // dd($products);
        $newpros = array();
        foreach($products as $product)
        {
            $pro = \App\Models\Product::where('title',$product->title)->first();
            if (!$pro)
            {
                $datapro['title'] = $product->title;
                $datapro['photo'] = $product->photo;
                $datapro['summary'] = $product->summary;
                $datapro['type'] = $product->type;
                $datapro['description'] = $product->description;
                $datapro['price'] = 0;
                $datapro['status']='active';
               
                $slug = Str::slug($datapro['title']);
                $slug_count = Product::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $slug .= time().'-'.$slug;
                }
                $datapro['slug']  = $slug;

                $pro = \App\Models\Product::create($datapro);
            }
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$pro->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
            $product->groupprice=$prices;
            $product->product_id = $pro->id;
            $product->id = $pro->id;
        }
        
        // dd($re['data']);
        $datai = array();
        $datai['final_amount'] = $data->final_amount;
        $datai['paid_amount'] = $data->paid_amount;
        $datai['discount_amount'] = $data->discount_amount;
        $datai['wh_id'] = 1;
        $datai['supplier'] = $supplier;
        $datai['shipcost'] = 0;
        $datai['products'] = $products; 
        //dd($bank);
        $datai['active_menu']="wi_add";
        $datai['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">Nhập kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $datai['warehouses'] = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $datai['bankaccounts'] = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
        $datai['ugroups']=UGroup::where('status','active')->orderBy('id','ASC')->get();
        $datai['user'] = auth()->user();
        $datai['categories'] = \App\Models\Category::where('is_parent',0)
        ->where('status','active')->orderBy('title','ASC')->get();
        return view('backend.warehouseins.create_e', $datai);

    }
    public function index(Request $request)
    {
        //
        $func = "warin_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data['active_menu']="wi_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách nhập kho </li>';
       
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


         
        if(isset($request->supplier_id))
            $data['supplier_id'] = $request->supplier_id;
        else
            $data['supplier_id'] = 0;
        $where = "";
        if($data['supplier_id'] != 0)
        {
            $where=" supplier_id = ".$data['supplier_id'];
        }    
        
        if(isset($date1) && isset($date2) )
        {
            if($where != "")
                $where .= ' and ';
            $where.="  created_at >= '".$date1."' and created_at <= '".$date2."'";
        }
        if($where != "")
            $where = " where ".$where;
        
        $query = " (select id from warehouse_ins ".$where.") as b ";
        // dd($where);
        $data['warehouseins'] = DB::table('warehouse_ins')
        ->select ('warehouse_ins.*'   )
        ->join(\DB::raw($query),'warehouse_ins.id','=','b.id')
        ->orderBy('warehouse_ins.id','desc')
        ->paginate($this->pagesize)->withQueryString();

        // $warehouseins=WarehouseIn::orderBy('id','DESC')->paginate($this->pagesize);
      
        return view('backend.warehouseins.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "warin_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="wi_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">Nhập kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
        $ugroups=UGroup::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        $categories = \App\Models\Category::where('is_parent',0)
        ->where('status','active')->orderBy('title','ASC')->get();
        return view('backend.warehouseins.create',compact('breadcrumb','active_menu', 'warehouses','bankaccounts','user','ugroups','categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $func = "warin_add";
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
        $wi = Warehousein::c_create($data);
        // return $wi;
        ///////////////////create detail /////////////////
        foreach ($details as $detail)
        {
            $product_detail['doc_id'] = $wi->id;
            $product_detail['doc_type'] = 'wi';
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
            WarehouseInDetail::create($product_detail);
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
                \App\Models\WarehouseinDetailSeries::c_create($wi->id,$seri, $detail['id'],'wi',$data['wh_id']);
            }

        }
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($wi->id,'wi',1,$data['final_amount'], $data['supplier_id']);
        $wi->suptrans_id = $sps->id;
        ///create paid transaction
        if($totalbankpaid> 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$wi->id,'wi',$totalbankpaid);
            SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $totalbankpaid, $data['supplier_id']); 
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
       $content = 'thêm phiếu nhập kho' ;
       \App\Models\Log::insertLogNew($content,$wi->id,'wi',$user->id);
       return response()->json(['msg'=>'Thêm đơn nhập kho thành công!','status'=>true]);

    }

    /**
     * Display the specified resource.
     */

    public function showold(string $id)
    {
        $func = "warin_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $warehousein = \App\Models\DIn::find($id);
        if($warehousein)
        {
            $active_menu="i_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">DS nhập kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            $wi_details = \App\Models\DIndetail::where('doc_id',$id)->where('doc_type','wi')->get();
            return view('backend.warehouseins.showold',compact('breadcrumb','warehousein','active_menu','wi_details'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function show(string $id)
    {
        //
        $func = "warin_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $warehousein = Warehousein::find($id);
        if($warehousein && $warehousein->status != 'active')
        {
            $warehousein =   \App\Models\DIn::where('inid',$id)->orderBy('id','desc')->first();
            $wi_details = \App\Models\DIndetail::where('doc_id',$warehousein->id)->where('doc_type','wi')->get();
            foreach ($wi_details as $wi )
            {
                $series = "";
                $i = 0;
                $wo_seris = \DB::select("select seri from warehousein_detail_series where doc_type='wi' and wi_id =".$wi->doc_id ." and product_id = ".$wi->product_id );
                foreach($wo_seris as $wo_seri)
                {
                    if ($i > 0)
                        $series .= ",";
                    $series .= $wo_seri->seri;
                    $i ++;
                }
                $wi->series = $series;
            }
            if($warehousein)
            {
                $active_menu="i_list";
                $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">DS nhập kho</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            
                return view('backend.warehouseins.show',compact('breadcrumb','warehousein','active_menu','wi_details'));
            }
            else
            {
                return back()->with('error','Không tìm thấy dữ liệu');
            }
        }
        else
        {
            $wi_details = WarehouseInDetail::where('doc_id',$id)->where('doc_type','wi')->get();
            foreach ($wi_details as $wi )
            {
                $series = "";
                $i = 0;
                $wo_seris = \DB::select("select seri from warehousein_detail_series where doc_type='wi' and wi_id =".$wi->doc_id ." and product_id = ".$wi->product_id );
                foreach($wo_seris as $wo_seri)
                {
                    if ($i > 0)
                        $series .= ",";
                    $series .= $wo_seri->seri;
                    $i ++;
                }
                $wi->series = $series;
            }
            if($warehousein)
            {
                $active_menu="i_list";
                $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">DS nhập kho</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            
                return view('backend.warehouseins.show',compact('breadcrumb','warehousein','active_menu','wi_details'));
            }
            else
            {
                return back()->with('error','Không tìm thấy dữ liệu');
            }
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $func = "warin_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        $warehousein = Warehousein::find($id);
        if($warehousein && $warehousein->status == 'active')
        {
            $active_menu="i_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">Danh sách nhập kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu nhập kho </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $paid_trans = null;
            $ship_trans = null;
            $bank_id = 0;
            $ship_amount = 0;
            
            if($warehousein->paidtrans_ids)
            {
                $id_ins = json_decode($warehousein->paidtrans_ids); 
                $id_in = $id_ins[0];
                $paidtrans = BankTransaction::where('id', $id_in->id)->first();
                $bank_id = $paidtrans->bank_id;
            }   
            if($warehousein->shiptrans_id)
            {
                $shiptrans = FreeTransaction::where('id',$warehousein->shiptrans_id)->first();
                $bank_id = $shiptrans->bank_id;
                $ship_amount = $shiptrans->total;
            }   
            $user = auth()->user();
            return view('backend.warehouseins.edit',compact('breadcrumb','warehousein','active_menu','warehouses','bankaccounts','user','bank_id','ship_amount'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'wi_id'=>'numeric|required',
        ]);
        $query = "(select id,photo, title from products ) as p";
        $products = DB::table('warehouse_in_details')
        ->select ('warehouse_in_details.price','warehouse_in_details.product_id','warehouse_in_details.quantity', 'p.title','p.photo','p.id')
        ->where('doc_id',$request->wi_id)->where('doc_type','wi') 
        ->leftJoin(\DB::raw($query),'warehouse_in_details.product_id','=','p.id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;

            $productseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
            ->where('wi_id',$request->wi_id)->get();
            //->where('is_sold',0)
            $i = 0;
            $series = "";
            foreach ($productseris as $productseri)
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "warin_edit";
      
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

        $oldwarehousein = WarehouseIn::find($data['id']);
        // return $oldwarehousein;
        
        if($data['id']==null || $data['id']==0 || $oldwarehousein==null || $oldwarehousein->status == 'returned')
            return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
       
       
       
        
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
        $detailpros = WarehouseInDetail::where('doc_id',$data['id'])->where('doc_type','wi')->get();
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
        $bank_docs = BankTransaction::where('doc_id',$oldwarehousein->id)
            ->where('doc_type','wi')->get();
        $sum_paid = 0;
        foreach ($bank_docs as $bank_doc)
        {
            $sum_paid += $bank_doc->total;
        }
        if($sum_paid != $oldwarehousein->paid_amount )
        {
            return response()->json(['msg'=>'Đã có nhiều giao dịch trả tiền cho phiếu nhập hàng. Không thể thay đổi thông tin!','status'=>false]);
        }
        ////check detail ////////////
        $olddetails = \App\Models\WarehouseInDetail::where('doc_id',$oldwarehousein->id)->where('doc_type','wi')->get();
        //update old warehouseindetail to sold
        foreach($olddetails as $olddetail)
        {
            \DB::select('update warehousein_detail_series set is_sold = 1 where wi_id ='.
                 $oldwarehousein->id . ' and doc_type="wi" and product_id = '. $olddetail->product_id);
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
                            $oldwarehousein->id . ' doc_type="wi" and product_id = '. $olddetail->product_id);
                    }
                    return response()->json(['msg'=>'Số seri '.$seri.' đã có!','status'=>false]);
                }    
                if ($seri == '')
                    continue;
               
            }
        }
        
        ////////////////
        //delete all old product detail
        $din = \App\Models\WarehouseIn::log_change($oldwarehousein);
        
        foreach($detailpros as $dtpro)
        {
            WarehouseInDetail::deleteDetailProVersion($dtpro,$oldwarehousein->cost_extra,$oldwarehousein->wh_id,$din->id);
        }
        ///delete sup trans 1 for importing
        SupTransaction::removeSubTrans($oldwarehousein->suptrans_id,'wir',$din->id);

         ///add return sup trans 1 for importing
        //  $sps = SupTransaction::createSubTrans($din,'wir',-1,$oldwarehousein->final_amount, $oldwarehousein->supplier_id);
      

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
        // if ($total_return <  $oldwarehousein->paid_amount)
        // {
        //     $sps = SupTransaction::createSubTrans($oldwarehousein->id,'wir',-1,$oldwarehousein->paid_amount- $total_return, $data['supplier_id']);
        // }
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
        $sql = "delete from warehousein_detail_series where doc_type='wi' and wi_id=". $oldwarehousein->id;
        \DB::select($sql);
      
        
        ///save new product detail ////////////
        ////kiem tra budget co dang no tien của hang ko, co thi bù trừ giá trị đơn nhập (- là thiếu tiền cửa hàng)///////////////////
        $totalbankpaid = $data['paid_amount'];
        $totalbudgetpaid = 0;
        $customer = \App\Models\User::find($data['supplier_id']);
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
        $oldwarehousein->s_update_final_amount( $data['final_amount']);
        /////

        $status = $oldwarehousein->fill($data)->save();
        //////////////////create detail //////////////////
        foreach ($details as $detail)
        {
            $product_detail['doc_id'] = $oldwarehousein->id;
            $product_detail['doc_type'] = 'wi';
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
            WarehouseInDetail::create($product_detail);
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
                \App\Models\WarehouseinDetailSeries::c_create($oldwarehousein->id,$seri, $detail['id'],'wi',$data['wh_id']);
             }

        }
        ///create SupTransaction
        $sps = SupTransaction::createSubTrans($oldwarehousein->id,'wi',1,$data['final_amount'], $data['supplier_id']);
        $oldwarehousein->suptrans_id = $sps->id;
        ///create paid transaction
        if($totalbankpaid> 0)
        {
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$oldwarehousein->id,'wi',$totalbankpaid);
            SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $totalbankpaid, $data['supplier_id']); 
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
        $content = 'cập nhật phiếu nhập kho' ;
        \App\Models\Log::insertLogNew($content,$oldwarehousein->id,'wi',$user->id);
       return response()->json(['msg'=>'Cập nhật thành công!','status'=>true]);
    }
    public function warehouseinReturn(Request $request )
    {
        //
        $func = "warin_return";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            
        ]);
        $id = $request->id;
        $oldwarehousein = WarehouseIn::find( $id);
        // return $oldwarehousein;
        if( $oldwarehousein==null || $oldwarehousein->status == 'returned' )
            return back()->with('error','Không tìm thấy phiếu nhập kho!');
        $user = auth()->user();
        //check detail product are exported
        $detailpros = WarehouseInDetail::where('doc_id', $id)->where('doc_type','wi')->get();
        $flag = 0;
        foreach($detailpros as $dtpro)
        {
            if($dtpro->qty_sold > 0)
                $flag = 1;
        }
        if($flag == 1)
        {
            return back()->with('error','Đã xuất kho hàng hóa trong phiếu nhập!Không thể trả hàng');
        }
        
        
        //return all old product detail
        $din = \App\Models\WarehouseIn::log_change($oldwarehousein);
        foreach($detailpros as $dtpro)
        {
            WarehouseInDetail::returnDetailPro($dtpro,$oldwarehousein->cost_extra,$oldwarehousein->wh_id,$din->id);
        }
        ///add return sup trans 1 for importing
        // $sps = SupTransaction::createSubTrans($din,'wir',-1,$oldwarehousein->final_amount, $oldwarehousein->supplier_id);
        /// thay bang cau lẹnh duoi
          ///delete sup trans 1 for importing
          SupTransaction::removeSubTrans($oldwarehousein->suptrans_id,'wir',$din->id);
       
           ///  ////delete old series
        $sql = "delete from warehousein_detail_series where doc_type='wi' and wi_id=". $oldwarehousein->id;
        \DB::select($sql);
        
        $oldwarehousein->status = 'returned';
        $oldwarehousein->version+= 1;
        $oldwarehousein->save();

        //cap nhat paid amount cho cac phieu nhap
        SupTransaction::updatePaidAmount(-1,$din->paid_amount  ,$din->supplier_id); 
    
    //    ///create log /////////////
    //    $content = 'return warehouse in stock: '. $id.' total: '.$oldwarehousein->final_amount;
    //    \App\Models\Log::insertLog($content,$user->id);
      
        $content = 'hoàn phiếu nhập kho' ;
        \App\Models\Log::insertLogNew($content,$oldwarehousein->id,'wire',$user->id);
        
       return redirect()->route('warehousein.index')->with('success','Trả hàng thành công!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "warin_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $oldwarehousein = WarehouseIn::find( $id);
        // return $oldwarehousein;
        if( $oldwarehousein==null || $oldwarehousein->status == 'returned')
              return back()->with('error','Không tìm thấy phiếu nhập kho!');
       
        $user = auth()->user();
       
        //check detail product are exported
        $detailpros = WarehouseInDetail::where('doc_id',$id)->where('doc_type','wi')->get();
        $flag = 0;
        foreach($detailpros as $dtpro)
        {
            if($dtpro->qty_sold > 0)
                $flag = 1;
        }
        if($flag == 1)
        {
            return back()->with('error','Hàng trong phiếu nhập kho đã xuất, không thể xóa!');
      
        }
        // $bank_docs = BankTransaction::where('doc_id',$oldwarehousein->id)
        //     ->where('doc_type','wi')->get();
        
        // $sum_paid = 0;
        // foreach ($bank_docs as $bank_doc)
        // {
        //     $sum_paid += $bank_doc->total;
        // }
        // if($sum_paid != $oldwarehousein->paid_amount )
        // {
        //     return back()->with('error','Đã có nhiều giao dịch trả tiền cho phiếu nhập hàng. Không thể xóa!');
        // }
        //delete all old product detail
        
        $din = \App\Models\Warehousein::log_change($oldwarehousein);
        foreach($detailpros as $dtpro)
        {
            WarehouseInDetail::deleteDetailProVersion($dtpro,$oldwarehousein->cost_extra,$oldwarehousein->wh_id,$din->id);
        }
        ///delete sup trans 1 for importing
        SupTransaction::removeSubTrans($oldwarehousein->suptrans_id,'wir',$din->id);
        ///
      
         ///delete paid transaction
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
                        SupTransaction::removeSubTrans( $suptrans->id,'fo', $bank_doc->id);
                    BankTransaction::removeBankTrans($bank_doc);
                }
            }
        }
        // if ($total_return <  $oldwarehousein->paid_amount) //tra lai tien cho budget
        // {
        //     $sps = SupTransaction::createSubTrans($oldwarehousein->id,'wir',-1,$oldwarehousein->paid_amount- $total_return, $oldwarehousein->supplier_id);
        // }
        ///delete ship invoice
        ///create ship invocie ///////////
        
             
       if($oldwarehousein->shiptrans_id )
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
        ///  ////delete old series
        $sql = "delete from warehousein_detail_series where doc_type='wi' and wi_id=". $oldwarehousein->id;
        \DB::select($sql);

         ///create log /////////////
        $content = 'xóa phiếu nhập kho' ;
        \App\Models\Log::insertLogNew($content,$oldwarehousein->id,'wi',$user->id);
        
        //update sysaccount
        $oldwarehousein->s_update_final_amount(0,true);
        /////

        $oldwarehousein->status = "deleted" ;
        $oldwarehousein->version+= 1;
        $oldwarehousein->save();
        if(!$oldwarehousein->paidtrans_ids && $oldwarehousein->paidtrans_ids!= '')
        {
            //cap nhat paid amount cho cac phieu nhap
            SupTransaction::updatePaidAmount(-1,$din->paid_amount  ,$din->supplier_id); 
    
        }
       
        return redirect()->route('warehousein.index')->with('success','Xóa thành công!');

        
    }
    public function warehouseinSavePaid(Request $request)
    {
        $func = "warin_edit";
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
        $wi = Warehousein::find($data['id']);
        $user = auth()->user();
       
        if( $wi && $wi->status == 'active')
        {
             ///create paid transaction
            if($data['paid_amount'] > $wi->final_amount - $wi->paid_amount)
            {
                return back()->with('error','Số tiền trả lớn hơn số tiền nợ!');
            }
            if($data['paid_amount'] ==0 || $wi->is_paid == 1)
            {
                return back()->with('error','Số tiền trả không hợp lệ!');
            }
            $bankaccount = Bankaccount::find($data['bank_id']);
            if(!$bankaccount || $bankaccount->total < $data['paid_amount'])
            {
                return back()->with('error','Tài khoản không đủ tiền trả!');
            }
            
            $bank_doc = BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$wi->id,'wi',$data['paid_amount']);
            $sup = SupTransaction::createSubTrans($bank_doc->id,'fi',-1, $data['paid_amount'], $wi->supplier_id); 
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
            $content = 'thêm phiếu nộp tiền phiếu nhập kho' ;
            \App\Models\Log::insertLogNew($content,$sup->id,'inpay',$user->id);
        
            return redirect()->route('warehousein.index')->with('success','Đã thêm thanh toán cho phiếu nhập hàng!');
            
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function warehouseinPaid($id)
    {
        $func = "warin_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // return $id;
         
        $wi = Warehousein::find($id);
         
        if( $wi && $wi->status == 'active')
        {
             $bankaccounts = Bankaccount::where('status','active')->get();
             $active_menu="i_list";
             
             $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
             <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehousein.index').'">Ds nhập kho</a></li>
             <li class="breadcrumb-item active" aria-current="page"> trả tiền phiếu nhập </li>';
             return view('backend.warehouseins.paid',compact('wi','breadcrumb','bankaccounts','active_menu'));
             
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
     
}
