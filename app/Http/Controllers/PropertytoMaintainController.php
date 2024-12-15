<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\PropertytoMaintain;

class PropertytoMaintainController extends Controller
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
        $func = "pm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ptm_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chuyển kho tài sản tới bảo hành</li>';
        $ptms=PropertytoMaintain::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.propertytomaintains.index',compact('ptms','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "pm_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ptm_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('propertytomaintain.index').'">Ds chuyển kho tài sản tới bảo hành</a></li>
        <li class="breadcrumb-item">Thêm chuyển tài sản tới bảo hành</li>';
         $user = auth()->user();
        return view('backend.propertytomaintains.create',compact('user',   'breadcrumb','active_menu'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "pm_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
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
                    if ($seri == '')
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
            //tao moi
            $user = auth()->user();
            $data['total'] = $data['price'] * $data['quantity'];
            $data['vendor_id'] = $user->id;
            $ptm = PropertytoMaintain::c_create($data);
            //create InvMaintaindetail
                
            \App\Models\InventoryProperties::addPropertyToMaintain($data['product_id'] ,$data['quantity']  );
            //save propertytodestroy doc
            $imd= \App\Models\InvMaintainDetail::c_create($ptm,'pm',1,$count_n>0?1:0); //1 la nhap
            $ipd= \App\Models\InvPropertyDetail::c_create($ptm,'pm',-1,$count_n>0?1:0); //1 la nhap
            $in_ids =\App\Models\InvPropertyDetail::sold_product($data['product_id'],$sold_noseri);
    
            foreach ($series as $seri)
            {
                $seri = trim ($seri);
                if($seri == '')
                        continue;
                $wi_seri = \App\Models\PropertySeries::where('seri',$seri)
                    ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
                
                $data_seri['wm_id'] = $ptm->id;
                $data_seri['seri'] = $seri;
                $data_seri['doc_type'] = 'pm';
                $data_seri['product_id'] = $data['product_id'];
                $data_seri['in_id'] = $wi_seri->id;
                $wd_seri = \App\Models\MaintainSeries::create($data_seri);
                $wi_seri->out_id = $wd_seri->id;
                $wi_seri->is_sold = 1;
                $wi_seri->save();
                $in_id = \App\Models\InvPropertyDetail::sold_property_id($wi_seri->wp_id,$wi_seri->doc_type ) ;
                array_push($in_ids,$in_id);
            }
            $ipd->in_ids = json_encode($in_ids);
            $ipd->save();
            ///create log /////////////
            $content = 'tạo phiếu chuyển kho tài sản tới bảo hành' ;
            \App\Models\Log::insertLogNew($content,$ptm->id,'ptm',$user->id);
            return redirect()->route('propertytomaintain.index')->with('success','Tạo chuyển kho tài sản tới bảo hành thành công!');
     
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
        $func = "pm_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ptm = PropertytoMaintain::find($id);
        if(!$ptm)
            return back()->with('error','Không tìm thấy dữ liệu!');

        $wd_series = \DB::select("select * from maintain_series where wm_id=".$ptm->id.' and doc_type = "pm"');
        $series = "";
        $i = 0;
        foreach($wd_series as $wd_seri)
        {
            if($i > 0)
                $series .= ',';
            $series .= $wd_seri->seri;
            $i ++;
        }
        $active_menu="ptm_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item " aria-current="page"><a href="'.route('maintaintowarehouse.index').'">Ds chuyển kho tài sản tới bảo hành</a></li>
        <li class="breadcrumb-item">Điều chỉnh chuyển kho tài sản tới bảo hành</li>';
         
        return view('backend.propertytomaintains.edit',compact('ptm', 'series', 'breadcrumb','active_menu'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "pm_edit";
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
        $ptm = PropertytoMaintain::find($id);
        if(!$ptm)
            return back()->with('error','Không tìm thấy dữ liệu!');
       
         $data = $request->all();
       
        $minventory = \App\Models\InventoryProperties::where('product_id',$data['product_id'])
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if($minventory && $ptm->product_id == $data['product_id'] && $data['quantity'] > $ptm->quantity + $minventory->quantity )
        {
            return back()->with('error','Số lượng vượt quá tồn kho!');
        }
        if(\App\Models\InvMaintainDetail::check_sold($ptm->id,'pm'))
        {
            return back()->with('error','Sản phẩm đã xuất khỏi kho bảo hành, không thể chỉnh sửa!');
        }
        //doc tu detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
        $wi_series = \DB::select("select * from maintain_series where doc_type='pm' and wm_id=".$ptm->id.' and is_sold = 0');

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
        $counts_n = \DB::select ("select count(id) as tong from property_series where product_id = ".$data['product_id'].' and is_sold = 0'); 
        $counts_n = $counts_n[0]->tong;
    //so hang khong co seri ton kho
        $n_noseri = $minventory->quantity - $counts_n  + $ptm->quantity;
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
       //remove old transfer
          //xoa them detail invp 
          $sql = "delete from maintain_series where doc_type='pm' and wm_id=". $ptm->id;
          \DB::select($sql);
         
        \App\Models\InvMaintainDetail::remove($ptm->id,'pm');
        \App\Models\InvPropertyDetail::remove($ptm->id,'pm');
        //
             //save maintaintowarehouse doc
        //
        \App\Models\InventoryProperties::deletePropertyToMaintain($ptm->product_id, $ptm->quantity );
        
        
         //tao moi
         $user = auth()->user();
         $data['total'] = $data['price'] * $data['quantity'];
         $data['vendor_id'] = $user->id;
         $ptm->fill($data)->save();
         //create InvMaintaindetail
             
         \App\Models\InventoryProperties::addPropertyToMaintain($data['product_id'] ,$data['quantity']  );
         //save propertytodestroy doc
         $imd= \App\Models\InvMaintainDetail::c_create($ptm,'pm',1,$count_n>0?1:0); //1 la nhap
         $ipd= \App\Models\InvPropertyDetail::c_create($ptm,'pm',-1,$count_n>0?1:0); //1 la nhap
         $in_ids =\App\Models\InvPropertyDetail::sold_product($data['product_id'],$sold_noseri);
 
         foreach ($series as $seri)
         {
             $seri = trim ($seri);
             if($seri == '')
                     continue;
             $wi_seri = \App\Models\PropertySeries::where('seri',$seri)
                 ->where('product_id',$data['product_id'])->where('is_sold',0)->first();
             
             $data_seri['wm_id'] = $ptm->id;
             $data_seri['seri'] = $seri;
             $data_seri['doc_type'] = 'pm';
             $data_seri['product_id'] = $data['product_id'];
             $data_seri['in_id'] = $wi_seri->id;
             $wd_seri = \App\Models\MaintainSeries::create($data_seri);
             $wi_seri->out_id = $wd_seri->id;
             $wi_seri->is_sold = 1;
             $wi_seri->save();
             $in_id = \App\Models\InvPropertyDetail::sold_property_id($wi_seri->wp_id,$wi_seri->doc_type ) ;
             array_push($in_ids,$in_id);
         }
         $ipd->in_ids = json_encode($in_ids);
         $ipd->save();
        
        ///create log /////////////
        $content = 'cập nhật phiếu chuyển kho tài sản tới bảo hành' ;
        \App\Models\Log::insertLogNew($content,$ptm->id,'ptm',$user->id);
        return redirect()->route('propertytomaintain.index')->with('success','Cập nhật chuyển kho tài sản tới bảo hành thành công!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "pm_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //

        $ptm = PropertytoMaintain::find($id);
        if(!$ptm)
            return back()->with('error','Không tìm thấy dữ liệu!');
            $minventory = \App\Models\InventoryProperties::where('product_id',$ptm->product_id )
            ->first();
       
        if(  !$minventory)
        {
            return  $data ;
            return back()->with('error','không tìm thấy tồn kho!');
        }
        if(\App\Models\InvMaintainDetail::check_sold($ptm->id,'pm'))
        {
            return back()->with('error','Sản phẩm đã xuất khỏi kho bảo hành, không thể chỉnh sửa!');
        }
         //remove old transfer
       //remove old transfer
          //xoa them detail invp 
           //doc tu detail_series de lay thong tin seri in sau do cap nhat các properties seri nhu chua xuat
        $wi_series = \DB::select("select * from maintain_series where doc_type='pm' and wm_id=".$ptm->id.' and is_sold = 0');

        $series = "";
        $i = 0;
        foreach($wi_series as $wi_seri)
        {
            $p_seri = \App\Models\PropertySeries::where('seri',$wi_seri->seri)->where('id',$wi_seri->in_id)->first();
            $p_seri->is_sold = 0;
            $p_seri->save();
        }

          $sql = "delete from maintain_series where doc_type='pm' and wm_id=". $ptm->id;
          \DB::select($sql);
         
        \App\Models\InvMaintainDetail::remove($ptm->id,'pm');
        \App\Models\InvPropertyDetail::remove($ptm->id,'pm');

        \App\Models\InventoryProperties::deletePropertyToMaintain($ptm->product_id, $ptm->quantity );
        ///create log /////////////
        $user = auth()->user();
        $content = 'xóa phiếu chuyển kho tài sản tới bảo hành' ;
        \App\Models\Log::insertLogNew($content,$ptm->id,'ptm',$user->id);
        $ptm->delete();
        return redirect()->route('propertytomaintain.index')->with('success','xóa chuyển kho tài sản tới bảo hành thành công!');
    
    
    }
}
