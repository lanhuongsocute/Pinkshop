<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Order;
use App\Models\SupTransaction; 
use App\Models\OrderDetail;
use App\Models\Bankaccount;
use App\Models\BankTransaction;
use App\Models\FreeTransaction;
use App\Models\UGroup;
use App\Models\Warehouseout;
use App\Models\User;
use App\Models\WarehouseoutDetail;
class OrderController extends Controller
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
        $func = "order_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="or_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách đặt hàng </li>';
        $orders=Order::where('status','pending')->orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.orders.index',compact('orders','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "order_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="or_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Ds đặt hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.orders.create',compact('breadcrumb','active_menu', 'warehouses', 'user' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "order_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data = $request->importDoc;
        // return $data;
        
       
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        
        ///save product detail ////////////
        ////average price///////////////////
        $details = $request->products;
        $count_item = 0;
        foreach ($details as $detail)
        {
            $count_item += $detail['quantity'];
        }
        $cost_extra = ($data['discount_amount'])/ $count_item ;
        $data['cost_extra'] = $cost_extra ;
        $wo = order::create($data);
        // return $wi;
        ////////////////////////////////////
        foreach ($details as $detail)
        {
            $product_detail['wo_id'] = $wo->id;
            $product_detail['product_id']= $detail['id'];
            $product_detail['quantity'] = $detail['quantity'];
            $product_detail['price'] = $detail['price'];
            $product = Product::find($detail['id']);
            $start_date = date('Y-m-d H:i:s');
            if($product->expired)
            {
                $strday = '+' . $product->expired*30 .' days';
                $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            
            OrderDetail::create($product_detail);
            //decrease stock
             
        }
        ///create SupTransaction
        
       ///create ship invocie ///////////
        
       ///create log /////////////
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "order_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $order = order::find($id);
        if($order)
        {
            $active_menu="or_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('order.index').'">DS đặt hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            $wo_details = OrderDetail::where('wo_id',$id)->get();
            return view('backend.orders.show',compact('breadcrumb','order','active_menu','wo_details'));
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
        $func = "order_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $order = order::find($id);
        if($order)
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('order.index').'">Danh sách đặt hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu đặt hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
             
            
            $user = auth()->user();
            
            return view('backend.orders.edit',compact('breadcrumb','order','active_menu','warehouses', 'user' ));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'wo_id'=>'numeric|required',
        ]);
        $wo = Order::find($request->wo_id);
        $query = "(select id,photo, type,title from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$wo->wh_id.") as np";
               
        $products = DB::table('order_details')
        ->select ('order_details.price','order_details.product_id','order_details.quantity', 'p.title','p.photo','p.id','p.type','np.quantity as stock_qty')
        ->where('wo_id',$request->wo_id)
        ->leftJoin(\DB::raw($query),'order_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'order_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        foreach($products as $product)
        {
            $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
            ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
            $prices = DB::select($query) ;
      
            $product->groupprice=$prices;
        }
        return response()->json(['msg'=>$products,'status'=>true]);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "order_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
          // return $request->all();
          $data = $request->importDoc;
          $oldorder = order::find($id);
          // return $oldorder;
          if($data['id']==null || $data['id']==0 || $oldorder==null)
              return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
         
          
         
          $user = auth()->user();
          $data['vendor_id'] = $user->id;
          //check detail product are exported
          $detailpros = orderDetail::where('wo_id',$data['id'])->get();
          
          
          //delete all old product detail
          
          foreach($detailpros as $dtpro)
          {
            $dtpro->delete();
          }
          ///delete sup trans 1 for importing
         
          
           ///save product detail ////////////
          ////average price///////////////////
          $details = $request->products;
          $count_item = 0;
          foreach ($details as $detail)
          {
              $count_item += $detail['quantity'];
          }
          $cost_extra = ($data['discount_amount'])/ $count_item ;
          $data['cost_extra'] = $cost_extra ;
          $oldorder->fill($data)->save();
  
          // return $wi;
          ////////////////////////////////////
          foreach ($details as $detail)
          {
              $product_detail['wo_id'] = $oldorder->id;
              $product_detail['product_id']= $detail['id'];
              $product_detail['quantity'] = $detail['quantity'];
              $product_detail['price'] = $detail['price'];
              $product = Product::find($detail['id']);
              $start_date = date('Y-m-d H:i:s');
              if($product->expired)
              {
                  $strday = '+' . $product->expired*30 .' days';
                  $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                  $product_detail['expired_at'] = $end_date;
              }
             
              
              orderDetail::create($product_detail);
              //decrease stock
               
          }
          
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "order_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $oldorder = Order::find($id);
        if(  $oldorder==null)
            return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
        $user = auth()->user();
        //check detail product are exported
        $detailpros = orderDetail::where('wo_id',$oldorder->id)->get();
        
        //delete all old product detail
        
        foreach($detailpros as $dtpro)
        {
          $dtpro->delete();
        }
        $oldorder->delete();
        ///delete sup trans 1 for importing
       return redirect()->route('order.index')->with('success','Xóa thành công!'); 
    }
    public function orderOutUpdate(Request $request)
    {
        $data = $request->importDoc;
        // return $data;
        if($data['paid_amount'] == $data['final_amount'])
            $data['is_paid'] = 1;
        else
            $data['is_paid'] = 0;
       
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        $order = Order::find($data['id']);
        $data['id'] = 0;
        if($order == null)
        {
            return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
        }
        $controller = new \App\Http\Controllers\WarehouseoutController();
        $kq = $controller->save_warehouseout($request);

        if ($kq == 1)
        {
            $order->status= "done";
            $order->save();
            $content = 'insert warehouse out stock: '.$data['wh_id'].' total: '.$data['final_amount'];
            \App\Models\Log::insertLog($content,$user->id);
            return response()->json(['msg'=>'Thêm đơn hàng thành công!','status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'Có lỗi xãy ra!','status'=>false]);
        }
       
       ///create log /////////////
      
      
    }
    public function orderOut($id)
    {
        $func = "order_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $order = order::find($id);
        if($order)
        {
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('order.index').'">Danh sách đặt hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> lập phiếu bán hàng </li>';
            $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $bankaccounts = Bankaccount::where('status','active')->orderBy('id','ASC')->get();
            $deliveries= User::where('role','delivery')->where('status','active')->orderBy('id','ASC')->get();
            // $ugroups=UGroup::where('status','active')->orderBy('id','ASC')->get();
            $user = auth()->user();
            return view('backend.orders.orderout',compact('breadcrumb','active_menu', 'warehouses','bankaccounts','user','deliveries','order'));
    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
