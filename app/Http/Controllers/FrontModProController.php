<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontModProController extends Controller
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
    public function up($id,$mod_id )
    {
        $detail = \App\Models\FrontProModDetail::find($id);
        if($detail)
        {
            $detail->order_id ++;
            $detail->save();
        }
        return redirect()->route('modpro.addpro',$mod_id)->with('success','cập nhật thành công');
    }
    public function down($id,$mod_id )
    {
        $detail = \App\Models\FrontProModDetail::find($id);
        if($detail)
        {
            $detail->order_id --;
            $detail->save();
        }
        return redirect()->route('modpro.addpro',$mod_id)->with('success','cập nhật thành công');

    }
    public function productPriceView($id,$mod_id)
    {
       
        $product = \App\Models\Product::find($id);
        if(!$product)
        {
            return  back()->with('error','Không tìm thấy dữ liệu');
        }
        $active_menu="pro_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('product.index').'">Mod products</a></li>
        <li class="breadcrumb-item active" aria-current="page"> thiết lập giá '. $product->title.'</li>';
     
        $sql1 = "select a.* , b.price from (select * from u_groups where status = 'active') as a"
        ." left join ( select * from group_prices where product_id = ".$id.") as b on a.id = b.ugroup_id";
        // dd($sql1);
        $group_prices = \DB::select($sql1);
        foreach ($group_prices as $gprice)
        {
            
            if( $gprice->price ===null   )
            {
                $data['ugroup_id'] = $gprice->id;
                $data['price'] = 0;
                $data['product_id'] = $id;
                \App\Models\GroupPrice::create($data);
            }
        }
        $productextend =  \App\Models\Productextend::where('product_id',$id)->first();
        if(! $productextend  )
        {
            $data['old_price'] = 0;
            $data['product_id'] = $id;
            $productextend = \App\Models\Productextend::create($data);
        }
        return view('backend.frontpromods.viewprice',compact('mod_id','product','breadcrumb','active_menu','group_prices','productextend'));
      
    }
    public function productPriceUpdate(Request $request)
    {
        $this->validate($request,[
            'id'=>'numeric|required',
            'mod_id'=>'numeric|required',
            'old_price'=>'numeric|required',
        ]); 
        $data= $request->all();
        $productextend =  \App\Models\Productextend::where('product_id',$data['id'])->first();
        $productextend->old_price = $data['old_price'];
        $productextend->save();
        $product = \App\Models\Product::find($data['id']);
        $product->price = $data['price'];
        $product->save();
        
        $gprices =  \App\Models\GroupPrice::where('product_id',$data['id'])->get();
        // dd($data);
        foreach($gprices as $gprice)
        {
            $gprice->price = isset($data['gp'.$gprice->ugroup_id])?$data['gp'.$gprice->ugroup_id]:0;
            $gprice->save();
        }
        // return back()->with('success','Đã lưu thông tin');
        return redirect()->route('modpro.addpro',$request->mod_id)->with('success','cập nhật thành công');
    }
    public function index()
    {
        //
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mod_pro";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách mod pro </li>';
        $mods=\App\Models\FrontProMod::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.frontpromods.index',compact('mods','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="mod_pro";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('modpro.index').'">Front Mod Pro</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo mod mới </li>';
        return view('backend.frontpromods.create',compact('breadcrumb','active_menu'));
  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'mod_type'=>'integer|required',
            'op_type'=>'integer|required',
            
        ]);
        // return $request->all();
        $data = $request->all();
        //check user with phone
         
        $status = \App\Models\FrontProMod::create($data);
        if($status){
            return redirect()->route('modpro.index')->with('success','Tạo khách hàng thành công!');
        }
        else
        {
            return back()->with('error','Something went wrong!');
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
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $frontmod = \App\Models\FrontProMod::find($id);
        if($frontmod)
        {
            $active_menu="mod_pro";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('modpro.index').'">Khách hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh khách hàng </li>';
            
            return view('backend.frontpromods.edit',compact('breadcrumb','frontmod','active_menu' ));
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
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $promod = \App\Models\FrontProMod::find($id);
        if($promod)
        {
            $this->validate($request,[
                'title'=>'string|required',
               
            ]);
    
            $data = $request->all();
            $status = $promod->fill($data)->save();
            
            if($status){

                return redirect()->route('modpro.index')->with('success','Cập nhật thành công');
            }
            else
            {
                return back()->with('error','Something went wrong!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $promod = \App\Models\FrontProMod::find($id);
         if($promod)
        {
            $status =   $promod->delete();
            if($status){
                return redirect()->route('modpro.index')->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','Có lỗi xãy ra!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function modproSavepro(Request $request)
    {
        $details = $request->products;
        $mod_id = $request->mod_id;
        $i = 0;
        foreach ($details as $detail)
        {
            $i ++;
            $opro = \App\Models\Product::find($detail['id']);
            if(!$opro)
                continue;
            $prodetail = \App\Models\FrontProModDetail::where('mod_id',$mod_id)->where('pro_id',$opro->id)->first();
            if (!$prodetail)
            {
                $data['pro_id'] = $opro->id;
                $data['mod_id'] = $mod_id;
                $data['status'] = 'active';
                \App\Models\FrontProModDetail::create($data);
            }
        }
        return response()->json(['msg'=>'đã thêm thành công','status'=>true]);
    }
    public function modproAddpro($id )
    {
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $frontmod = \App\Models\FrontProMod::find($id);
        if($frontmod)
        {
            $active_menu="mod_pro";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('modpro.index').'">Danh sách mod pro</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Thêm sản phẩm cho mod '.$frontmod->title.' </li>';
            $prodetails = \App\Models\FrontProModDetail::where('mod_id',$frontmod->id)->get();
            return view('backend.frontpromods.addpro',compact('breadcrumb','frontmod','active_menu','prodetails' ));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function modproRemovepro(Request $request)
    {
        $mod_id = $request->mod_id;
        $pro_id = $request->pro_id;
        $detail = \App\Models\FrontProModDetail::where('mod_id',$mod_id)->where('pro_id',$pro_id)->first();
        if($detail)
        {
            $status =   $detail->delete();
            if($status){
                return redirect()->route('modpro.addpro',$mod_id)->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','Có lỗi xãy ra!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }

    }

    public function modproStatus(Request $request)
    {
        $func = "modpro_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            \DB::table('front_pro_mods')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            \DB::table('front_pro_mods')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
}
