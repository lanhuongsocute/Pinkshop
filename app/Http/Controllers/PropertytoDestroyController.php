<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\PropertytoDestroy;

class PropertytoDestroyController extends Controller
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
        $func = "pd_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ptd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho hủy</li>';
        $ptds=PropertytoDestroy::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.propertytodestroys.index',compact('ptds','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "pd_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ptd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('propertytodestroy.index').'">Ds chuyển kho hủy</a></li>
        <li class="breadcrumb-item">Thêm chuyển bảo hành</li>';
         $user = auth()->user();
        return view('backend.propertytodestroys.create',compact('user',   'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "pd_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'product_id'=>'numeric|required',
            'quantity'=>'numeric|required|gt:0',
            'price'=>'numeric|required|gt:0',
        ]);
        $data = $request->all();
       
        $minventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
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
            $counts_n = \DB::select ("select count(id) as tong from property_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
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
                    $query ='select * from property_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
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
            //
            \App\Models\InventoryProperties::addPropertyToDestroy($data['product_id'] ,$data['quantity']  );
             //save propertytodestroy doc
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $ptd = PropertytoDestroy::c_create($data);
            $mpd = \App\Models\InvPropertyDetail::c_create($ptd,'pd',-1,$count_n>0?1:0); //1 la nhap
            $in_ids =\App\Models\InvPropertyDetail::sold_product($data['product_id'],$sold_noseri );
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $wi_seri = \App\Models\PropertySeries::where('seri',$seri)
                    ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
              
                $data_seri['wd_id'] = $ptd->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'pd';
                $data_seri['product_id'] = $data['product_id'];
                $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\DestroySeries::create($data_seri);
                $wi_seri->out_id = $wd_seri->id;
                $wi_seri->is_sold = 1;
                $wi_seri->save();
                
                $in_id = \App\Models\InvPropertyDetail::sold_property_id($wi_seri->wp_id,$wi_seri->doc_type ) ;
                array_push($in_ids,$in_id);
            }
            $mpd->in_ids = json_encode($in_ids);
            $mpd->save();
           
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho hủy' ;
            \App\Models\Log::insertLogNew($content,$ptd->id,'ptd',$user->id);
            return redirect()->route('propertytodestroy.index')->with('success','Tạo chuyển kho bảo hành thành công!');
     
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
        $func = "pd_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ptd = PropertytoDestroy::find($id);
        if(!$ptd)
            return back()->with('error','Không tìm thấy dữ liệu!');
        $wd_series = \DB::select("select * from destroy_series where wd_id=".$ptd->id.' and doc_type = "pd"');
        $series = "";
        $i = 0;
        foreach($wd_series as $wd_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wd_seri->seri;
            $i ++;
        }
    
        $active_menu="ptd_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('maintaintowarehouse.index').'">Ds chuyển kho hủy</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho hủy</li>';
         
        return view('backend.propertytodestroys.edit',compact('ptd', 'series', 'breadcrumb','active_menu'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "pd_edit";
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
        $ptd = PropertytoDestroy::find($id);
        if(!$ptd)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
         $data = $request->all();
       
        $minventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if($minventory && $ptd->product_id == $data['product_id'] && $data['quantity'] > $ptd->quantity + $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
        if($minventory && $ptd->product_id != $data['product_id'] && $data['quantity'] >   $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }

        //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
        $wi_series = \DB::select("select * from destroy_series where doc_type='pd' and wd_id=".$ptd->id.' and is_sold = 0');
    
        $series = "";
        $i = 0;
        foreach($wi_series as $wi_seri)
        {
            $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
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
        $n_noseri = $minventory->quantity - $counts_n  + $ptd->quantity;
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
            $query ='select * from property_series where seri ="'.$seri.'" and is_sold = 0 and product_id ='.$data['product_id'];
            $rows = \DB::select($query);
            if(count($rows) == 0)
            {
                foreach($wi_series as $wi_seri)
                {
                    $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
                    $p_seri->is_sold = 1;
                    $p_seri->save();
                }
                return back()->with('error','Số serie' . $seri.' không có trong kho!')->withInput();;
            }
        } 
        //remove old transfer
        $sql = "delete from destroy_series where doc_type='pd' and wd_id=". $ptd->id;
        \DB::select($sql);
       
        \App\Models\InvPropertyDetail::remove($ptd->id,'pd');
        \App\Models\InventoryProperties::deletePropertyToDestroy($ptd->product_id, $ptd->quantity );
         ///them moi
        \App\Models\InventoryProperties::addPropertyToDestroy($data['product_id'], $data['quantity'] );
            //save maintaintowarehouse doc
        $user = auth()->user();
        $data['total'] = $data['price'] * $data['quantity'];
        $data['vendor_id'] = $user->id;
        $ptd->fill($data)->save();

        $mpd = \App\Models\InvPropertyDetail::c_create($ptd,'pd',-1,$count_n>0?1:0); //1 la nhap
        $in_ids =\App\Models\InvPropertyDetail::sold_product($data['product_id'],$sold_noseri );
        foreach ($series as $seri)
        {
            $seri = trim ($seri);
            if($seri == '')
                    continue;
            $wi_seri = \App\Models\PropertySeries::where('seri',$seri)
                ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
           
            $data_seri['wd_id'] = $ptd->id;
            $data_seri['seri'] = $seri;
            $data_seri['doc_type'] = 'pd';
            $data_seri['product_id'] = $data['product_id'];
            $data_seri['in_id'] = $wi_seri->id;
            $wd_seri = \App\Models\DestroySeries::create($data_seri);
            $wi_seri->out_id = $wd_seri->id;
            $wi_seri->is_sold = 1;
            $wi_seri->save();
            $in_id = \App\Models\InvPropertyDetail::sold_property_id($wi_seri->wp_id,$wi_seri->doc_type ) ;
            array_push($in_ids,$in_id);
        }
        $mpd->in_ids = json_encode($in_ids);
        $mpd->save();
        ///create log /////////////
        $content = 'cập nhật phiếu chuyển kho hủy' ;
        \App\Models\Log::insertLogNew($content,$ptd->id,'ptd',$user->id);
        return redirect()->route('propertytodestroy.index')->with('success','Cập nhật chuyển kho hủy thành công!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "pd_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ptd = PropertytoDestroy::find($id);
        if(!$ptd)
            return back()->with('error','Không tìm thấy dữ liệu!');
            $minventory = \App\Models\InventoryProperties::where('product_id',$ptd->product_id )
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
         //doc tu warehousein_detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
         $wi_series = \DB::select("select * from destroy_series where doc_type='pd' and wd_id=".$ptd->id.' and is_sold = 0');
        
         $series = "";
         $i = 0;
         foreach($wi_series as $wi_seri)
         {
            $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
            $p_seri->is_sold = 0;
            $p_seri->save();
         }
        //remove old transfer
          //xoa them detail invp 
          $sql = "delete from destroy_series where doc_type='pd' and wd_id=". $ptd->id;
          \DB::select($sql);
        \App\Models\InvPropertyDetail::remove($ptd->id,'pd');
        //
        \App\Models\InventoryProperties::deletePropertyToDestroy($ptd->product_id, $ptd->quantity );
        ///create log /////////////
        $user = auth()->user();
        $content = 'xóa phiếu chuyển kho hủy' ;
        \App\Models\Log::insertLogNew($content,$ptd->id,'ptd',$user->id);
        $ptd->delete();
        return redirect()->route('propertytodestroy.index')->with('success','xóa chuyển kho hủy thành công!');
    
    }
}
