<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\MaintaintoDestroy;

class MaintaintoDestroyController extends Controller
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
        $func = "md_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="mtd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển bảo hành tới kho hủy</li>';
        $mtds=MaintaintoDestroy::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.maintaintodestroys.index',compact('mtds','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "md_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="mtd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('maintaintodestroy.index').'">Ds chuyển bảo hành tới kho hủy</a></li>
        <li class="breadcrumb-item">Thêm chuyển bảo hành</li>';
         $user = auth()->user();
        return view('backend.maintaintodestroys.create',compact('user',   'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "md_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required',
            'price'=>'numeric|required',
        ]);
        $data = $request->all();
       
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
        if($minventory && $minventory->quantity >=  $data['quantity'])
        {

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
                    if($seri == '')
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
                return back()->with('error','Số hàng không có series trong phiếu xuất nhiều hơn trong kho!')->withInput();;
            }
            //
            \App\Models\InventoryMaintenance::addMaintainToDestroy($data['product_id'] ,$data['quantity']  );
             //save maintaintodestroy doc
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $mtd = MaintaintoDestroy::c_create($data);
             //tao inv_property_detail out
            
            $mpd = \App\Models\InvMaintainDetail::c_create($mtd,'md',-1,$count_n>0?1:0); //1 la nhap
            $in_ids =\App\Models\InvMaintainDetail::sold_product($data['product_id'],$sold_noseri );
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
                    ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
              
                $data_seri['wd_id'] = $mtd->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'md';
                $data_seri['product_id'] = $data['product_id'];
                $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\DestroySeries::create($data_seri);
                $wi_seri->out_id = $wd_seri->id;
                $wi_seri->is_sold = 1;
                $wi_seri->save();
                
                $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
                array_push($in_ids,$in_id);
            }
            $mpd->in_ids = json_encode($in_ids);
            $mpd->save();
            ///create log /////////////
            $content = 'tạo phiếu chuyển bảo hành tới bảo hành tới kho hủy' ;
            \App\Models\Log::insertLogNew($content,$mtd->id,'mtd',$user->id);
            return redirect()->route('maintaintodestroy.index')->with('success','Tạo chuyển kho bảo hành thành công!');
     
        }
        else
        {
            return back()->with('error','Không tìm thấy tồn kho!');
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
        //
        $func = "md_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $mtd = MaintaintoDestroy::find($id);
        if(!$mtd)
            return back()->with('error','Không tìm thấy dữ liệu!');

        $wd_series = \DB::select("select * from destroy_series where wd_id=".$mtd->id.' and doc_type = "md"');


        $series = "";
        $i = 0;
        foreach($wd_series as $wd_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wd_seri->seri;
            $i ++;
        }

        $active_menu="mtd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('maintaintowarehouse.index').'">Ds chuyển bảo hành tới kho hủy</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển bảo hành tới kho hủy</li>';
         
        return view('backend.maintaintodestroys.edit',compact('mtd', 'series', 'breadcrumb','active_menu'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "md_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'product_id'=>'numeric|required|gt:0',
            'quantity'=>'numeric|required|gt:0',
            'price'=>'numeric|required|gt:0',
        ]);
        $data = $request->all();
        // return $data;
        $mtd = MaintaintoDestroy::find($id);
        if(!$mtd)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
         $data = $request->all();
       
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$data['product_id'])
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if($minventory && $mtd->product_id == $data['product_id'] && $data['quantity'] > $mtd->quantity + $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
        
         //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
         $wi_series = \DB::select("select * from destroy_series where doc_type='md' and wd_id=".$mtd->id.' and is_sold = 0');
        
         $series = "";
         $i = 0;
         foreach($wi_series as $wi_seri)
         {
            $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
            $p_seri->is_sold = 0;
            $p_seri->save();
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
             $counts_n = \DB::select ("select count(id) as tong from maintain_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
             $counts_n = $counts_n[0]->tong;
            //so hang khong co seri ton kho
             $n_noseri = $minventory->quantity - $counts_n  + $mtd->quantity;
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
                     foreach($wi_series as $wi_seri)
                     {
                        $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
                         $p_seri->is_sold = 1;
                         $p_seri->save();
                     }
                     return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
                 }
             } 
          

        //remove old transfer
          //xoa them detail invp 
          $sql = "delete from destroy_series where doc_type='md' and wd_id=". $mtd->id;
          \DB::select($sql);
         
        \App\Models\InvMaintainDetail::remove($mtd->id,'md');
        //
        \App\Models\InventoryMaintenance::deleteMaintainToDestroy($mtd->product_id, $mtd->quantity );
            //save maintaintowarehouse doc
      
      ///them moi
        \App\Models\InventoryMaintenance::addMaintainToDestroy($data['product_id'] ,$data['quantity']  );
        //save maintaintodestroy doc
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $mtd->fill($data)->save();
        //tao inv_property_detail out
        
        $mpd = \App\Models\InvMaintainDetail::c_create($mtd,'md',-1,$count_n>0?1:0); //1 la nhap
        $in_ids =\App\Models\InvMaintainDetail::sold_product($data['product_id'],$sold_noseri );
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            if($seri == '')
                    continue;
            $wi_seri = \App\Models\MaintainSeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
           
            $data_seri['wd_id'] = $mtd->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'md';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            $wd_seri = \App\Models\DestroySeries::create($data_seri);
            $wi_seri->out_id = $wd_seri->id;
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $in_id = \App\Models\InvMaintainDetail::sold_maintain_id($wi_seri->wm_id,$wi_seri->doc_type ) ;
            array_push($in_ids,$in_id);
        }
        $mpd->in_ids = json_encode($in_ids);
        $mpd->save();
        ///create log /////////////
        $content = 'cập nhật phiếu chuyển bảo hành tới kho hủy' ;
        \App\Models\Log::insertLogNew($content,$mtd->id,'mtd',$user->id);
        return redirect()->route('maintaintodestroy.index')->with('success','Cập nhật chuyển bảo hành tới kho hủy thành công!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "md_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $mtd = MaintaintoDestroy::find($id);
        if(!$mtd)
            return back()->with('error','Không tìm thấy dữ liệu!');
        
       
        $minventory = \App\Models\InventoryMaintenance::where('product_id',$mtd->product_id )
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
         $wi_series = \DB::select("select * from destroy_series where doc_type='md' and wd_id=".$mtd->id.' and is_sold = 0');
        
         $series = "";
         $i = 0;
         foreach($wi_series as $wi_seri)
         {
            $p_seri = \App\Models\MaintainSeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
            $p_seri->is_sold = 0;
            $p_seri->save();
         }

        //remove old transfer
          //xoa them detail invp 
          $sql = "delete from destroy_series where doc_type='md' and wd_id=". $mtd->id;
          \DB::select($sql);
         
        \App\Models\InvMaintainDetail::remove($mtd->id,'md');
        //
        \App\Models\InventoryMaintenance::deleteMaintainToDestroy($mtd->product_id, $mtd->quantity );
            //save maintaintowarehouse doc
                   ///create log /////////////
        $user = auth()->user();
        $content = 'xóa phiếu chuyển bảo hành tới kho hủy' ;
        \App\Models\Log::insertLogNew($content,$mtd->id,'mtd',$user->id);
        $mtd->delete();
        return redirect()->route('maintaintodestroy.index')->with('success','xóa chuyển bảo hành tới kho hủy thành công!');
    
    }
}
