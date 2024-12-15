<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryCheck;
use App\Models\InventoryCheckDetail;
use App\Models\WarehouseInDetail;
 
 
use App\Models\User;

class InventoryCheckController extends Controller
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
        $func = "invc_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ic_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách kiểm kho </li>';
        $wcs=InventoryCheck::orderBy('id','DESC')->paginate($this->pagesize);
      
        return view('backend.inventorycheck.index',compact('wcs','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "invc_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ic_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventorycheck.index').'">Ds kiểm kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.inventorycheck.create',compact('breadcrumb','active_menu', 'warehouses', 'user'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "invc_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data = $request->importDoc;
        // return $data;
        $user = auth()->user();
        $data['vendor_id'] = $user->id;
        $data['total'] = 0;
        $ic = InventoryCheck::c_create($data);
        
          ///save product detail ////////////
        $details = $request->products;
        ////////////////////////////////////
        foreach ($details as $detail)
        {
            $count_n =0; //so series muốn xuất
            $series = array();
            if( $detail['stock_quantity']==null)
                $detail['stock_quantity']= 0;
            
            if(isset($detail['seri']))
            {
                $series =  explode(",",  $detail['seri']);
                $count_n =count($series );
            }
            
            if($count_n >   $detail['quantity'])
            {
                return response()->json(['msg'=>'Số lượng series lơn hơn số sản phẩm!','status'=>false]);
            }
        }
      

        $total = 0;
        foreach ($details as $detail)
        {
            if( $detail['stock_quantity']==null)
                $detail['stock_quantity']= 0;
            $product_detail['ic_id']= $ic->id;
            $product_detail['product_id']= $detail['id'];
            if($detail['quantity'] > $detail['stock_quantity'])
            {
                $product_detail['operation'] = 1;
                $product_detail['error'] = $detail['quantity'] - $detail['stock_quantity'];
            }
            else
            {
                $product_detail['operation'] = -1;
                $product_detail['error'] =  $detail['stock_quantity'] - $detail['quantity'];
            }
            $product_detail['quantity'] = $detail['stock_quantity'];
            //save warehouseindetail if operation 1 or sub inventory if operation -1
            $product = Product::find($detail['id']);
            $total += $product->price_avg * $product_detail['error'] *$product_detail['operation'];
            //luu seri trước
            //select old series
            $old_series = \App\Models\WarehouseinDetailSeries::where( 'wh_id', $data['wh_id'])->where('product_id',$product_detail['product_id'])->where('is_sold',0)->get();
             //\DB::select('select * from warehousein_detail_series where wh_id='.$data['wh_id'].' and product_id='.$product_detail['product_id'].' and is_sold= 0 ');
            $count_n =0; //so series muốn xuất
            $series = array();
            $seri_sub = 0;
            $seri_add = 0;
            $in_ids=array();
            $in_id_ws=array();
              //tao warehouseoutdetail voi loai la wm
              $product_detailin['wo_id'] =  $ic->id;
            //   $product_detailin['price'] = $data['price'];
            //   
              $product_detailin['doc_id'] = $ic->id;
              $product_detailin['doc_type'] = 'ci';
              $product_detailin['product_id']= $detail['id'];
              $product_detailin['quantity'] = $product_detail['error'];
              $product_detailin['price'] = $product->price_avg;
              $product_detailin['wh_id'] = $ic->wh_id;
              
            if( $detail['seri'] != '')
            {
                $series =  explode(",",  $detail['seri']);
                foreach($series as $seri)
                {
                    $seri = trim($seri);
                    $found = 0;
                    foreach($old_series as $old_seri)
                    {
                        $old_seri = $old_seri->seri;
                        $old_seri = trim($old_seri);
                        if($seri === $old_seri)
                            $found = 1;
                    }
                    if($found == 0)
                    {
                        \App\Models\WarehouseinDetailSeries::c_create($ic->id,$seri, $product_detail['product_id'],'ci',$data['wh_id']);
                        
                        $seri_add ++;
                    }
                }
                foreach($old_series as $old_seri_n)
                {
                    $old_seri = $old_seri_n->seri;
                    $old_seri = trim($old_seri);
                    $found = 0;
                    foreach($series as $seri)
                    {
                        $seri = trim($seri);
                        if($seri === $old_seri)
                            $found = 1;
                    }
                    if($found == 0)
                    {
                        $old_detail = \App\Models\WarehouseInDetail::where('doc_id',$old_seri_n->wi_id)->where('product_id',$old_seri_n->product_id)->where('doc_type',$old_seri_n->doc_type)->first();
                        $old_detail->qty_sold += 1;
                        $old_detail->save();
                        ////
                        $in_id = new \App\Models\IDs();
                        $in_id->id = $old_detail->id;
                        $in_id->qty = -1;
                        array_push($in_ids, $in_id);
                        array_push($in_id_ws, $in_id);
                       
                        ///
                        $old_seri_n->is_sold = 1;
                        $old_seri_n->save();
                        $seri_sub += 1;
                  //them seriout
                        $data_seri['wo_id'] = $ic->id;
                        $data_seri['seri'] = $old_seri;
                        $data_seri['product_id'] = $old_seri_n->product_id;
                        $data_seri['in_id'] = $old_seri_n->id;
                        $data_seri['doc_type'] = 'ci';
                        \App\Models\WarehouseoutDetailSeries::create($data_seri);
                    }
                }   
            }

            //////////
            if($seri_add > 0)
            {
                $product_detailin['quantity'] = $seri_add;
                $product_detailin['is_seri'] = 1;
                $inv = \App\Models\Inventory::where('product_id',$product_detailin['product_id'])
                ->where('wh_id', $product_detailin['wh_id'])
                ->first();
                if($inv)
                {
                    $product_detailin['prebalance'] =$inv->quantity;
                }
                else
                {
                    $product_detailin['prebalance'] = 0;
                }
                $din = WarehouseInDetail::create($product_detailin);
                $in_id = new \App\Models\IDs();
                $in_id->id = $din->id;
                $in_id->qty = $din->quantity;
                array_push($in_ids, $in_id);
                Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$seri_add, $product->price_avg ,0);
                $product_detailin['is_seri'] = 0;
            }
            if($seri_sub > 0)
            {
                $product_detailin['quantity'] = $seri_sub;
                $product_detailin['is_seri'] = 1;
                $product_detailin['in_ids'] = json_encode( $in_id_ws);
                $inv = \App\Models\Inventory::where('product_id',$product_detailin['product_id'])
                ->where('wh_id', $product_detailin['wh_id'])
                ->first();
                if($inv)
                {
                    $product_detailin['prebalance'] =$inv->quantity;
                }
                else
                {
                    $product_detailin['prebalance'] = 0;
                }
                Inventory::subProductInv($product_detailin['product_id'], $data['wh_id'],$seri_sub , $product->price_avg ,0);
                \App\Models\WarehouseoutDetail::c_create($product_detailin); 
                $product_detailin['is_seri'] = 0;
            }

            if($product_detail['operation'] > 0)
            {
                $inv = \App\Models\Inventory::where('product_id',$product_detailin['product_id'])
                    ->where('wh_id', $product_detailin['wh_id'])
                    ->first();
                if($inv)
                {
                    $product_detailin['prebalance'] =$inv->quantity;
                }
                else
                {
                    $product_detailin['prebalance'] = 0;
                }
               
                if($product_detail['error'] - ( $seri_add - $seri_sub) > 0)
                {
                    $product_detailin['quantity'] =$product_detail['error'] - ( $seri_add - $seri_sub) ;
                    $din = WarehouseInDetail::create($product_detailin);
                    $in_id = new \App\Models\IDs();
                    $in_id->id = $din->id;
                    $in_id->qty = $din->quantity;
                    array_push($in_ids, $in_id);
                    Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['error'] - ( $seri_add - $seri_sub)  , $product->price_avg ,0);
                }
                if($product_detail['error'] - ( $seri_add - $seri_sub) < 0)
                {
                    Inventory::subProductInv($product_detail['product_id'], $data['wh_id'],$product_detail['error'] - (  $seri_sub - $seri_add  )  , $product->price_avg ,0);
                    $out_ids =  Inventory::updateWarehouseLastIn_inventory_check($product_detail['product_id'],$data['wh_id'],$product_detail['error'] - (  $seri_sub - $seri_add  ) );
                    foreach($out_ids as $out_id)
                    {
                        array_push($in_ids, $out_id);
                    }
                    $product_detailin['in_ids'] = json_encode( $out_ids);
                    $product_detailin['quantity'] = $product_detail['error'] - (  $seri_sub - $seri_add  ) ;
                    \App\Models\WarehouseoutDetail::c_create($product_detailin); 
           
                }
            }
            else
            {
                $inv = \App\Models\Inventory::where('product_id',$product_detailin['product_id'])
                    ->where('wh_id', $product_detailin['wh_id'])
                    ->first();
                if($inv)
                {
                    $product_detailin['prebalance'] =$inv->quantity;
                }
                else
                {
                    $product_detailin['prebalance'] = 0;
                }
                // $in_ids = Inventory::subProduct($product_detail['product_id'], $data['wh_id'],$product_detail['error'], $product->price_avg ,0);
                if($product_detail['error'] - (  $seri_sub - $seri_add  ) > 0)
                {
                    Inventory::subProductInv($product_detail['product_id'], $data['wh_id'],$product_detail['error'] - (  $seri_sub - $seri_add  )  , $product->price_avg ,0);
                        //neu so seri tru bot nho hơn tổng số sản phẩm cần trừ thì thêm trừ ko seri trong warehousein
                    $out_ids =  Inventory::updateWarehouseLastIn_inventory_check($product_detail['product_id'],$data['wh_id'],$product_detail['error'] - (  $seri_sub - $seri_add  ) );
                    foreach($out_ids as $out_id)
                    {
                        array_push($in_ids, $out_id);
                    }
                    $product_detailin['in_ids'] = json_encode( $out_ids);
                    $product_detailin['quantity'] = $product_detail['error'] - (  $seri_sub - $seri_add  ) ;
                    \App\Models\WarehouseoutDetail::c_create($product_detailin); 
           
                }
                if($product_detail['error'] - (  $seri_sub - $seri_add  ) < 0)
                {
                    $product_detailin['quantity'] = $product_detail['error'] - ( $seri_add - $seri_sub ) ;
                    // $product_detailin['prebalance'] +=$seri_add;
                    $din = WarehouseInDetail::create($product_detailin);
                    $in_id = new \App\Models\IDs();
                    $in_id->id = $din->id;
                    $in_id->qty = $din->quantity;
                    array_push($in_ids, $in_id);
                    Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['error'] - ( $seri_add - $seri_sub)  , $product->price_avg ,0);
                }
            }
            $product_detail['ids'] = json_encode( $in_ids) ;
             //save inventorycheckdetail
             InventoryCheckDetail::create($product_detail);
           
        }
        $ic->total = $total;
        $ic->save();
        
       ///create log /////////////
        $content = 'thêm phiếu kiểm kho' ;
        \App\Models\Log::insertLogNew($content,$ic->id,'ic',$user->id);
    
       return response()->json(['msg'=>'Thêm đơn hàng thành công!','status'=>true]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "invc_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ic = InventoryCheck::find($id);
        if($ic)
        {
            $active_menu="wi_trans";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventorycheck.index').'">DS kiểm kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            $ic_details = InventoryCheckDetail::where('ic_id',$id)->get();
            foreach($ic_details as $ic_detail)
            {
                $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$ic_detail->product_id)
                    ->where('is_sold',0)->where('wh_id',$ic->wh_id)->get();
                
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
            return view('backend.inventorycheck.show',compact('breadcrumb','ic','active_menu','ic_details'));
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
        // $func = "invc_edit";
        // if(!$this->check_function($func))
        // {
        //     return redirect()->route('unauthorized');
        // }
        // //
        // if(!$this->checkRole(2))
        // {
        //     return redirect()->route('unauthorized');
        // }
        // $ic = InventoryCheck::find($id);
        // if($ic )
        // {
        //     $active_menu="ic_list";
        //     $breadcrumb = '
        //     <li class="breadcrumb-item"><a href="#">/</a></li>
        //     <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventorycheck.index').'">Danh sách kiểm kho</a></li>
        //     <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu kiểm kho </li>';
        //     $warehouses = Warehouse::where('status','active')->orderBy('id','ASC')->get();
        //     $user = auth()->user();
        //     return view('backend.inventorycheck.edit',compact('breadcrumb','ic','active_menu','warehouses', 'user'));
        // }
        // else
        // {
        //     return back()->with('error','Không tìm thấy dữ liệu');
        // }

      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $func = "invc_edit";
        // if(!$this->check_function($func))
        // {
        //     return redirect()->route('unauthorized');
        // }
        // //
         
        // // return $request->all();
        // $data = $request->importDoc;
        // $ic = InventoryCheck::find($id);
        // // return $oldwarehouseout;
        // $user = auth()->user();
        // $data['vendor_id'] = $user->id;
        
        // $details = $request->products;
        // ////////////////////////////////////
        // foreach ($details as $detail)
        // {
        //     $count_n =0; //so series muốn xuất
        //     $series = array();
        //     if(isset($detail['seri']))
        //     {
        //         $series =  explode(",",  $detail['seri']);
        //         $count_n =count($series );
        //     }
        //     if($count_n > $detail['quantity'])
        //     {
        //         return response()->json(['msg'=>'Số lượng series lơn hơn số sản phẩm!','status'=>false]);
        //     }
        // }
        // ///delete product detail ////////////
        // $detail_incs = \App\Models\InventoryCheckDetail::where('ic_id',$ic->id)->get();
        // foreach($detail_incs as $detail_inc)
        // {
        //     $product = \App\Models\Product::find($detail_inc->product_id);
        //     if($detail_inc->operation > 0)
        //     {
        //         $in_ids = json_decode($detail_inc->ids);
        //         foreach($in_ids as $in_id)
        //         {
        //             $detailpro = \App\Models\WarehouseInDetail::find($in_id->id);
        //             // return $dtpro;
        //             // InventoryCheck::deleteDetailIn($detailpro   ,0,$ic->wh_id);
        //             $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        //             $product->stock -= $detailpro->quantity;
        //             $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
        //                 ->where('wh_id',$ic->wh_id)->first();
        //             $inventory->quantity -= $detailpro->quantity;
        //             $product->save();
        //             $inventory->save();
        //             $detailpro->delete();
        //         }
        //     }
        //     else
        //     {
        //         $product = \App\Models\Product::where('id',$detail_inc->product_id)->first();
        //         if($product->type=='normal')
        //         {
                   
        //             $product->stock += $detail_inc->error;
        //             $inventory = \App\Models\Inventory::where('product_id',$detail_inc->product_id)
        //                 ->where('wh_id',$ic->wh_id)->first();
        //             $inventory->quantity += $detail_inc->error;
        //             $product->save();
        //             $inventory->save();
        //             //return product to warehouseindetail
        //             $in_ids = json_decode($detail_inc->ids);
        //             foreach ($in_ids as $in_id)
        //             {
        //                 $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
        //                 $detail_in->qty_sold -= $in_id->qty;
        //                 $detail_in->save();
        //             } 
        //         }
        //     }
        //     $detail_inc->delete();
        // }
        // $details = $request->products;
        // ////////////////////////////////////
        // $total = 0;
        // foreach ($details as $detail)
        // {
            
        //     $product_detail['ic_id']= $ic->id;
        //     $product_detail['product_id']= $detail['id'];
        //     if($detail['quantity'] > $detail['stock_quantity'])
        //     {
        //         $product_detail['operation'] = 1;
        //         $product_detail['error'] = $detail['quantity'] - $detail['stock_quantity'];
        //     }
        //     else
        //     {
        //         $product_detail['operation'] = -1;
        //         $product_detail['error'] =  $detail['stock_quantity'] - $detail['quantity'];
        //     }
        //     $product_detail['quantity'] = $detail['stock_quantity'];
        //     //save warehouseindetail if operation 1 or sub inventory if operation -1
        //     $product = Product::find($detail['id']);
        //     $total += $product->price_avg * $product_detail['error'] *$product_detail['operation'];
           
           
           
           
        //     if($product_detail['operation'] > 0)
        //     {
        //         $product_detailin['doc_id'] = $ic->id;
        //         $product_detailin['doc_type'] = 'ic';
        //         $product_detailin['product_id']= $detail['id'];
        //         $product_detailin['quantity'] = $product_detail['error'];
        //         $product_detailin['price'] = $product->price_avg;
        //         $product_detailin['wh_id'] = $ic->wh_id;
        //         $inv = \App\Models\Inventory::where('product_id',$product_detailin['product_id'])
        //         ->where('wh_id', $product_detailin['wh_id'])
        //         ->first();
        //         if($inv)
        //         {
        //             $product_detailin['prebalance'] =$inv->quantity;
        //         }
        //         else
        //         {
        //             $product_detailin['prebalance'] = 0;
        //         }
                
        //         $din = WarehouseInDetail::create($product_detailin);
        //         $in_ids=array();
        //         $in_id = new \App\Models\IDs();
        //         $in_id->id = $din->id;
        //         $in_id->qty = $din->quantity;
        //         array_push($in_ids, $in_id);
        //         $product_detail['ids'] = json_encode( $in_ids) ;
        //         Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['error'], $product->price_avg ,0);
        //     }
        //     else
        //     {
        //         $in_ids = Inventory::subProduct($product_detail['product_id'], $data['wh_id'],$product_detail['error'], $product->price_avg ,0);
        //         $product_detail['ids'] = json_encode($in_ids);
        //     }
        //         //save inventorycheckdetail
        //     InventoryCheckDetail::create($product_detail);
        //      //select old series
        //      //xoa old seri của ic
        //      \DB::select('delete from warehousein_detail_series where doc_type="ci" and wi_id ='.$ic->id);

        //     $old_series =\DB::select('select * from warehousein_detail_series where wh_id='.$data['wh_id'].' and product_id='.$product_detail['product_id'].' and is_sold= 0 ');
        //     $count_n =0; //so series muốn xuất
        //     $series = array();
        //     if( $detail['seri'] != '')
        //     {
        //         $series =  explode(",",  $detail['seri']);
        //         foreach($series as $seri)
        //         {
        //             $seri = trim($seri);
        //             $found = 0;
        //             foreach($old_series as $old_seri)
        //             {
        //                 $old_seri = $old_seri->seri;
        //                 $old_seri = trim($old_seri);
        //                 if($seri === $old_seri)
        //                     $found = 1;
        //             }
        //             if($found == 0)
        //             {
        //                 \App\Models\WarehouseinDetailSeries::c_create($ic->id,$seri, $product_detail['product_id'],'ci',$data['wh_id']);
        //             }
        //         }
        //         foreach($old_series as $old_seri)
        //         {
        //             $old_seri = $old_seri->seri;
        //             $old_seri = trim($old_seri);
        //             $found = 0;
        //             foreach($series as $seri)
        //             {
        //                 $seri = trim($seri);
        //                 if($seri === $old_seri)
        //                     $found = 1;
        //             }
        //             if($found == 0)
        //             {
        //                 $sql="update warehousein_detail_series set is_sold = 1 where seri = '".$old_seri."' and product_id=".$product_detail['product_id']." and wh_id =".$data['wh_id'];
        //                 \DB::select($sql);
        //             }
        //         }   
        //     }
        
        // }
        // $data['total'] = $total;
        // $ic->fill($data)->save();
        
        // ///create log /////////////
        // $content = 'cập nhật phiếu kiểm kho' ;
        // \App\Models\Log::insertLogNew($content,$ic->id,'ic',$user->id);

        // return response()->json(['msg'=>'Thêm đơn hàng thành công!','status'=>true]);
    }
    public function getProductList(Request $request)
    {

        $this->validate($request,[
            'ic_id'=>'numeric|required',
        ]);
        $ic = InventoryCheck::find($request->ic_id);
        $query = "(select id,photo, title,type, price_avg from products ) as p";
        $query1 = "(select product_id  from inventories where wh_id = ".$ic->wh_id.") as np";
               
        $products = DB::table('inventory_check_details')
        ->select ('inventory_check_details.product_id','inventory_check_details.error','inventory_check_details.operation','inventory_check_details.quantity as stock_qty', 'p.title','p.photo','p.id','p.price_avg as price','p.type' )
        ->where('ic_id',$request->ic_id)
        ->leftJoin(\DB::raw($query),'inventory_check_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'inventory_check_details.product_id','=','np.product_id')
        ->orderBy('id','ASC')->get();
        
        foreach($products as $product)
        {
            $iproductseris = \App\Models\WarehouseinDetailSeries::where('product_id',$product->id)
              ->where('is_sold',0)->where('wh_id',$ic->wh_id)->get();
            
            $series = "";
            $i = 0;
            foreach ($iproductseris as $productseri)
            {
               if($i > 0)
                    $series .=", ";
                $series .= $productseri->seri;
                $i ++;
            }
            $product->series=$series;

        }


        return response()->json(['msg'=>$products,'status'=>true]);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $func = "invc_delete";
        // if(!$this->check_function($func))
        // {
        //     return redirect()->route('unauthorized');
        // }
        // //
        // if(!$this->checkRole(2))
        // {
        //     return redirect()->route('unauthorized');
        // }
        // // return $request->all();
        // // $data = $request->importDoc;
        // $ic = InventoryCheck::find($id);
        // // return $oldwarehouseout;
        // $user = auth()->user();
        // $data['vendor_id'] = $user->id;
       
        // ///delete product detail ////////////
        // $detail_incs = \App\Models\InventoryCheckDetail::where('ic_id',$ic->id)->get();
        // foreach($detail_incs as $detail_inc)
        // {
        //     $product = \App\Models\Product::find($detail_inc->product_id);
        //     if($detail_inc->operation > 0)
        //     {
        //         $in_ids = json_decode($detail_inc->ids);
        //         foreach($in_ids as $in_id)
        //         {
        //             $detailpro = \App\Models\WarehouseInDetail::find($in_id->id);
        //             // return $dtpro;
        //             // InventoryCheck::deleteDetailIn($detailpro   ,0,$ic->wh_id);
        //             $product = \App\Models\Product::where('id',$detailpro->product_id)->first();
        //             $product->stock -= $detailpro->quantity;
        //             $inventory = \App\Models\Inventory::where('product_id',$detailpro->product_id)
        //                 ->where('wh_id',$ic->wh_id)->first();
        //             $inventory->quantity -= $detailpro->quantity;
        //             $product->save();
        //             $inventory->save();
        //             $detailpro->delete();
        //         }
        //     }
        //     else
        //     {
        //         $product = \App\Models\Product::where('id',$detail_inc->product_id)->first();
        //         if($product->type=='normal')
        //         {
                    
                   
        //             $product->stock += $detail_inc->error;
        //             $inventory = \App\Models\Inventory::where('product_id',$detail_inc->product_id)
        //                 ->where('wh_id',$ic->wh_id)->first();
        //             $inventory->quantity += $detail_inc->error;
        //             $product->save();
        //             $inventory->save();
        //             //return product to warehouseindetail
        //             $in_ids = json_decode($detail_inc->ids);
        //             foreach ($in_ids as $in_id)
        //             {
        //                 $detail_in = \App\Models\WarehouseInDetail::find($in_id->id);
        //                 $detail_in->qty_sold -= $in_id->qty;
        //                 $detail_in->save();
        //             } 
        //         }
        //     }
        //     $detail_inc->delete();
          
        // }

        //  //xoa old seri của ic
        //  \DB::select('delete from warehousein_detail_series where doc_type="ci" and doc_id ='.$ic->id);
            

        // $content = 'xóa phiếu kiểm kho' ;
        // \App\Models\Log::insertLogNew($content,$ic->id,'ic',$user->id);

        // $ic->delete();
        // return redirect()->route('maintaintodestroy.index')->with('success','xóa chuyển kho hủy thành công!');
    
    }
}
