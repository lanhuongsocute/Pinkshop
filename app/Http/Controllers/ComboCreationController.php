<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComboCreationController extends Controller
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
        //
        $func = "combo_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="combo_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách tạo thành phẩm </li>';
        $combocreations = \DB::table('combo_creations')->where('combo_creations.is_deleted',0)
        ->leftjoin('products', 'combo_creations.product_id', '=', 'products.id')
        ->select('products.*', 'combo_creations.*')
        ->where('combo_creations.is_deleted',0)
        ->orderBy('combo_creations.id','desc')
        ->paginate($this->pagesize)->withQueryString();;
        return view('backend.combocreations.index',compact('combocreations','breadcrumb','active_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "combo_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="combo_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('combocreation.index').'">Danh sách tạo thành phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thêm mới </li>';
        $warehouses = \App\Models\Warehouse::where('status','active')->orderBy('id','ASC')->get();
        $user = auth()->user();
        return view('backend.combocreations.create',compact('breadcrumb','active_menu', 'warehouses' ,'user' ));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

        
            //
            $func = "combo_list";
            if(!$this->check_function($func))
            {
                return redirect()->route('unauthorized');
            }
            $data = $request->importDoc;
            // return $data;
            $user = auth()->user();
            $data['user_id'] = $user->id; 
            
            
            // return $data;
            ///check product inventory//////
            if( $data['quantity'] == 0 )
            {
                return response()->json(['msg'=>'số lượng phải lớn hơn 0','status'=>false]);
            }
        
            ///check product inventory//////
            $details = $request->products;
            foreach ($details as $detail)
            {
                
                $pro_inventory = \App\Models\Inventory::where('product_id',$detail['id'])->where('wh_id', $data['wh_id'])->first();
                if(!$pro_inventory || $pro_inventory->quantity < $detail['quantity'] )
                {
                    return response()->json(['msg'=>'Số lượng trong kho không đủ'.$pro_inventory->quantity.' - '.$detail['id'],'status'=>false]);
        
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
                    return response()->json(['msg'=>'Số lượng seri trong đơn lớn hơn trong kho!','status'=>false]);
        
                }
                if($count_n > $detail['quantity'] )
                {
                    return response()->json(['msg'=>'số seri lớn hơn số trong kho','status'=>false]);
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
                                return response()->json(['msg'=>'Số sp không seri lớn hơn số sp không seri trong kho','status'=>false]);
                            }
                                
                        } 
                }
                
                //so hang khong co seri ton kho
                $n_noseri = $pro_inventory->quantity - $counts_n ;
                //so hang khong co seri xuat kho
                $sold_noseri =$detail['quantity'] - $count_n;
                if($sold_noseri > $n_noseri) //neu so hang ban ko seri > so hàng tonkho thi false
                {
                    return response()->json(['msg'=>'Seri không có trong kho','status'=>false]);
                }

            }
            ///save product detail ////////////
            ////average price///////////////////
            $data['price'] = $data['final_amount']/$data['quantity'];
            $combo = \App\Models\ComboCreation::create($data);
            // return $wi;
            // dd($wo);
            ////////////////////////////////////
            foreach ($details as $detail)
            {
                $product_detail['wo_id'] = $combo->id;
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
                $product = \App\Models\Product::find($detail['id']);
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
                $cost_extra = 0;
                \App\Models\Inventory::subProductInv($product_detail['product_id'], $data['wh_id'], $detail['quantity'], $product_detail['price'], $cost_extra);
                $in_ids = \App\Models\Inventory::updateWarehouseLastIn($product_detail['product_id'], $data['wh_id'],$sold_noseri);
                
                foreach ($series as $seri)
                {
                    $seri = trim ($seri);
                    if ($seri == '')
                        continue;
                    $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                        ->where('product_id',$detail['id'])->where('is_sold',0)->first();
                    $wi_seri->is_sold = 1;
                    $wi_seri->save();
                    $data_seri['wo_id'] = $combo->id;
                    $data_seri['seri'] = $seri;
                    $data_seri['product_id'] = $detail['id'];
                    $data_seri['in_id'] = $wi_seri->id;
                    $data_seri['doc_type'] = 'co';
                    \App\Models\WarehouseoutDetailSeries::create($data_seri);
                    $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                        ->where('product_id',$wi_seri->product_id)->first();
                    $in_id = \App\Models\Inventory::updateWarehouseInDetails($product_detail['product_id'], $data['wh_id'],$detail_in);
                    array_push($in_ids, $in_id);
                }
                $product_detail['in_ids'] = json_encode($in_ids);
                $product_detail['doc_type']='co'; //loai xuat la phieu xuat ban hang
                \App\Models\WarehouseoutDetail::c_create($product_detail);
                \Log::info('insert product detail.');
                \Log::info( $product_detail['product_id']);
                \Log::info( $product_detail['quantity'] );
                \Log::info( $product_detail['price'] );
            }
            ///nhap kho cho sản pham combo voi so luong combo->quantity va don gia combo->price
            $product_detail['doc_id'] = $combo->id;
            $product_detail['doc_type'] = 'co';
            $product_detail['product_id']= $combo->product_id ;
            $product_detail['quantity'] = $combo->quantity ;
            $product_detail['price'] = $combo->price;
            $product_detail['wh_id'] = $data['wh_id'];
            $inv = \App\Models\Inventory::where('product_id', $combo->product_id)
                ->where('wh_id',$data['wh_id'])
                ->first();
            if( $inv)
                $product_detail['prebalance'] =$inv->quantity;
            else
                $product_detail['prebalance'] = 0;
            //save expired days
            $product = \App\Models\Product::find( $combo->product_id);
            $start_date = date('Y-m-d H:i:s');
            if($product->expired)
            {
                $strday = '+' . $product->expired*30 .' days';
                $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $product_detail['is_seri'] =  0;
            //  return $product_detail;
            \App\Models\WarehouseInDetail::create($product_detail);
            //cập nhật giá bán thông thường cho sản phẩm tạo combo
            $product = \App\Models\Product::find($combo->product_id);
            $product->price = $request->sold_price;
            $product->save();

            //increase stock
            \App\Models\Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,0);
            $content = 'thêm combo sản phẩm' ;
            \App\Models\Log::insertLogNew($content,$combo->id,'co',$user->id);
            return response()->json(['combo'=> $combo,'msg'=>'Thêm combo thành công!','status'=>true]);
        }
        catch (\Exception $e) {
            // Quay lại form với thông báo lỗi nếu có lỗi
            // return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi lưu dữ liệu.'.$e]);
            return response()->json(['msg'=>'Có lỗi xảy ra khi lưu dữ liệu.'.$e,'status'=>true]);
        }

    }
    public function getProductList(Request $request)
    {
        $this->validate($request,[
            'combo_id'=>'numeric|required',
        ]);
        $combo = \App\Models\ComboCreation::find($request->combo_id);
        $query = "(select id,photo, title,type from products ) as p";
        $query1 = "(select product_id ,quantity from inventories where wh_id = ".$combo->wh_id.") as np";
               
        $products = \DB::table('warehouseout_details')
        ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.quantity', 
        'p.title','p.photo','p.id','p.type','np.quantity as stock_qty'
        )
        ->where('wo_id',$request->combo_id)->where('doc_type','co')
        ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
        ->leftJoin(\DB::raw($query1),'warehouseout_details.product_id','=','np.product_id')
         ->get();
      
         foreach($products as $product)
         {
             $query = "select b.*,c.id as idg, c.title from (select id, price, ugroup_id from group_prices where product_id = ".$product->id
             ." ) as b left join (select id,title from u_groups) as c on b.ugroup_id = c.id  order by c.id ASC";
             $prices = \DB::select($query) ;
       
             $product->groupprice=$prices;
             $oproductseris = \App\Models\WarehouseoutDetailSeries::where('product_id',$product->id)
              ->where('wo_id',$request->combo_id)->where('doc_type','co')->get();
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
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $func = "combo_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $combos = \DB::select('select a.* , b.title, b.photo from (select * from combo_creations where id = '.$id.') as a left join products b on a.product_id = b.id');
        if($combos)
        {
            //dd($combo);
            $combo = $combos[0];
            $active_menu="wo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('combocreation.index').'">Danh sách tạo thành phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Xem chi tiết</li>';
            if($combo)
            {
                $co_details = \App\Models\WarehouseoutDetail::where('wo_id',$id)->where('doc_type','co')->get();
                foreach($co_details as $wi_detail)
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
                return view('backend.combocreations.show',compact('breadcrumb','combo','active_menu','co_details' ));
            }
            else
            {
                $dout = \App\Models\DOut::where('outid',$id)->orderBy('id','desc')->first();
                return $this->showold( $dout->id);
            }
           
           
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
        $func = "combo_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $combos = \DB::select("select c.*, b.title, b.price from (select * from combo_creations where id = ".$id.") as c left join products b on c.product_id = b.id");
        $combo = null;
        if(count($combos) > 0)
        {
            $combo = $combos[0];
        }
        else
        {
            return back()->with('error','không tìm thấy sản phẩm');
        }
        if($combo  )
        {
            $active_menu="combo_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('combocreation.index').'">Danh sách combo</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh phiếu tạo combo </li>';
            $warehouses = \App\Models\Warehouse::where('status','active')->orderBy('id','ASC')->get();
            $user = auth()->user();
            return view('backend.combocreations.edit',compact('breadcrumb','combo','active_menu','warehouses', 'user'));
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
        //
        try{

      
            $func = "combo_list";
            if(!$this->check_function($func))
            {
                return redirect()->route('unauthorized');
            }
            $this->validate($request,[
                'importDoc.final_amount'=>'numeric|required',
                'importDoc.discount_amount'=>'numeric|nullable',
                'importDoc.product_id'=>'numeric|nullable',
            ]);
            // return $request->all();
            $data = $request->importDoc;
            $oldcombo = \App\Models\ComboCreation::find($id);
            // return $oldcombo;
            if($data['id']==null || $data['id']==0 || $oldcombo==null  )
                return response()->json(['msg'=>'không tìm thấy!','status'=>false]);
        
        
            //check detail product are exported
            $user = auth()->user();
            $data['user_id'] = $user->id;
            
            ///check product inventory//////
            $details = $request->products;
            foreach ($details as $detail)
            {
                    ////delete old series
            
                //lay chi tiet xuat kho cu de kiem tra cung ton kho va so xuat kho moi
                $wo_detail = \App\Models\WarehouseoutDetail::where('wo_id',$oldcombo->id)->where('doc_type','co')
                ->where('product_id',$detail['id'])->first();
                if (!$wo_detail)
                    continue;
                //   return response()->json(['msg'=>' wo detail ' .$wo_detail->id ,'status'=>false]);
        
                $pro_inventory = \App\Models\Inventory::where('product_id',$detail['id'])->where('wh_id', $data['wh_id'])->first();
                if(!$pro_inventory || $pro_inventory->quantity + $wo_detail->quantity < $detail['quantity'] ) //so sanh so luong ton kho hien tai va so luong phieu xuat kho cux nho hon so luong xuat kho moi
                {
                        return response()->json(['msg'=>'Số hàng xuất nhiều hơn trong kho a!' .$wo_detail->quantity ,'status'=>false]);
                }
            
                ////cap nhat seri trong warehousein nhu chua xuat de kiem tra thong tin moi
                $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldcombo->id)->where('doc_type','co')->get();
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
        
            $detailpros = \App\Models\WarehouseoutDetail::where('wo_id',$data['id'])->where('doc_type','co')->get();
        
            

            //delete all old product detail
            
            foreach($detailpros as $dtpro)
            {
                \App\Models\WarehouseoutDetail::deleteDetailPro ($dtpro,0,$oldcombo->wh_id);
            }
        
        
            
            ////delete old series
        ////add series for each product
        $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldcombo->id)->where('doc_type','co')->get();
        foreach($wo_series as $wo_seri)
        {
                $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
                \DB::select($query);
        }
        $sql = "delete from warehouseout_detail_series where  doc_type='co' and wo_id=". $oldcombo->id;
        \DB::select($sql);
        
            ///save product detail ////////////
            ////average price///////////////////
            // return $data;
            

            $details = $request->products;
            $count_item = 0;
            foreach ($details as $detail)
            {
                $count_item += $detail['quantity'];
            }
            // $cost_extra = ($data['discount_amount'])/ $count_item ;
            $data['cost_extra'] =0 ;
            $cost_extra = 0;
            ///update sysaccount
        //xoa so lượng sản phẩm nhập combo trước đó
        $dtpro = \App\Models\WarehouseInDetail::where('doc_type','co')->where('doc_id',$oldcombo->id)->where('product_id',$oldcombo->product_id)->first();
        \App\Models\WarehouseInDetail::deleteDetailPro ($dtpro,0,$oldcombo->wh_id);

            $oldcombo->fill($data)->save();

            // return $wi;
            ////////////////////////////////////
            foreach ($details as $detail)
            {
                $product_detail['wo_id'] = $oldcombo->id;
                $product_detail['wh_id'] = $data['wh_id'];
                $product_detail['product_id']= $detail['id'];
                $product_detail['quantity'] = $detail['quantity'];
                $product_detail['price'] = $detail['price'];
                $product = \App\Models\Product::find($detail['id']);
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
            \App\Models\Inventory::subProductInv($product_detail['product_id'], $data['wh_id'], $detail['quantity'], $product_detail['price'], $cost_extra);
            //tim detail in voi san pham ko seri
            $in_ids = \App\Models\Inventory::updateWarehouseLastIn($product_detail['product_id'], $data['wh_id'],$sold_noseri);
            
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if ($seri == '')
                        continue;
                $wi_seri = \App\Models\WarehouseinDetailSeries::where('seri',$seri)
                    ->where('product_id',$detail['id'])->where('is_sold',0)->first();
                $wi_seri->is_sold = 1;
                $wi_seri->save();
                $data_seri['wo_id'] = $oldcombo->id;
                $data_seri['seri'] = $seri;
                $data_seri['product_id'] = $detail['id'];
                $data_seri['in_id'] = $wi_seri->id;
                $data_seri['doc_type'] = 'co';
                \App\Models\WarehouseoutDetailSeries::create($data_seri);
                    //tim detailin cho seri
                    $detail_in = \App\Models\WarehouseInDetail::where('doc_id',$wi_seri->wi_id)
                    ->where('product_id',$wi_seri->product_id)->first();
                    $in_id =  \App\Models\Inventory::updateWarehouseInDetails($product_detail['product_id'], $data['wh_id'],$detail_in);
                    array_push($in_ids, $in_id);
            }
            $product_detail['in_ids'] = json_encode($in_ids);
            $product_detail['doc_type']='co'; //loai xuat la phieu xuat ban hang
            \App\Models\WarehouseoutDetail::c_create($product_detail);
            }
            //
            ///nhap kho cho sản pham combo voi so luong combo->quantity va don gia combo->price
            $product_detail['doc_id'] = $oldcombo->id;
            $product_detail['doc_type'] = 'co';
            $product_detail['product_id']= $oldcombo->product_id ;
            $product_detail['quantity'] = $oldcombo->quantity ;
            $product_detail['price'] = $oldcombo->price;
            $product_detail['wh_id'] = $data['wh_id'];
            $inv = \App\Models\Inventory::where('product_id', $oldcombo->product_id)
                ->where('wh_id',$data['wh_id'])
                ->first();
            if( $inv)
                $product_detail['prebalance'] =$inv->quantity;
            else
                $product_detail['prebalance'] = 0;
            //save expired days
            $product = \App\Models\Product::find( $oldcombo->product_id);
            $start_date = date('Y-m-d H:i:s');
            if($product->expired)
            {
                $strday = '+' . $product->expired*30 .' days';
                $end_date = date("Y-m-d H:i:s", strtotime( $strday, strtotime($start_date)));
                $product_detail['expired_at'] = $end_date;
            }
            $product_detail['is_seri'] =  0;
            //  return $product_detail;
            \App\Models\WarehouseInDetail::create($product_detail);
            //increase stock
            \App\Models\Inventory::addProduct($product_detail['product_id'], $data['wh_id'],$product_detail['quantity'], $product_detail['price'] ,0);
        
              //cập nhật giá bán thông thường cho sản phẩm tạo combo
              $product = \App\Models\Product::find($oldcombo->product_id);
              $product->price = $request->sold_price;
              $product->save();
  

            ///create log /////////////
            $content = 'cập nhật combo sản phẩm' ;
            \App\Models\Log::insertLogNew($content,$oldcombo->id,'co',$user->id);
            return response()->json(['msg'=>'Cập nhật đơn hàng thành công!','status'=>true]);
        }
        catch (\Exception $e) {
            // Quay lại form với thông báo lỗi nếu có lỗi
            // return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi lưu dữ liệu.'.$e]);
            return response()->json(['msg'=>'Có lỗi xảy ra khi lưu dữ liệu.'.$e,'status'=>true]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "combo_list";
       
        $oldcombo = \App\Models\ComboCreation::find($id);
        // return $oldcombo;
        if(  $oldcombo==null  )
            return back()->with('error','không tìm thấy!');
       
       
        //check detail product are exported
        $user = auth()->user();
        $detailpros = \App\Models\WarehouseoutDetail::where('wo_id',$oldcombo->id)->where('doc_type','co')->get();
        //delete all old product detail
        foreach($detailpros as $dtpro)
        {
            \App\Models\WarehouseoutDetail::deleteDetailPro ($dtpro,0,$oldcombo->wh_id);
        }
       
         ////delete old series
       ////add series for each product
       $wo_series = \App\Models\WarehouseoutDetailSeries::where('wo_id',$oldcombo->id)->where('doc_type','co')->get();
       foreach($wo_series as $wo_seri)
       {
            $query = 'update warehousein_detail_series set is_sold = 0 where id = '.$wo_seri->in_id.' and product_id = '.$wo_seri->product_id;
            \DB::select($query);
       }
       $sql = "delete from warehouseout_detail_series where  doc_type='co' and wo_id=". $oldcombo->id;
       \DB::select($sql);
        
        $cost_extra = 0;
        ///update sysaccount
       //xoa so lượng sản phẩm nhập combo trước đó
        $dtpro = \App\Models\WarehouseInDetail::where('doc_type','co')->where('doc_id',$oldcombo->id)->where('product_id',$oldcombo->product_id)->first();
        \App\Models\WarehouseInDetail::deleteDetailPro ($dtpro,0,$oldcombo->wh_id);
        $oldcombo->is_deleted = 1;
        $oldcombo->save();
        return redirect()->route('combocreation.index')->with('success','xóa thành công!');

    }
}
