<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
class ComboController extends Controller
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
        <li class="breadcrumb-item active" aria-current="page">Hàng hóa </li>';
        $combos = DB::table('combos')->where('combos.is_deleted',0)
        ->leftjoin('products', 'combos.product_id', '=', 'products.id')
        ->select('products.*', 'combos.id as combo_id','combos.status as combo_status')
        ->orderBy('combo_id','desc')
        ->paginate($this->pagesize)->withQueryString();;
        return view('backend.combos.index',compact('combos','breadcrumb','active_menu'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="combo_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('combo.index').'">Combo</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Thêm combo </li>';
        return view('backend.combos.create',compact('breadcrumb','active_menu'));
   
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $func = "product_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data = $request->importDoc;
        // return $data;
        ///check product inventory//////
        $combo = \App\Models\Combo::where('product_id',$data['product_id'])->first();
        if(!$combo)
        {
            $combo = \App\Models\Combo::create($data);
            $details = $request->products;
            foreach ($details as $detail)
            {
            $datac['product_id'] = $detail['id'];
            $datac['quantity'] = $detail['quantity'];
            $datac['price'] =  $detail['price'];
            $datac['combo_id'] = $combo['id'];
            $datac['product_id'] = $combo['product_id'];
            \App\Models\ComboDetail::create($datac);
            }
            $content = 'thêm đơn bán hàng' ;
            // return redirect()->route('combo.index')->with('success','Tạo combo thành công!');
            return response()->json(['msg'=>$combo,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'sản phẩm đã được cấu hình combo','status'=>false]);
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
    public function getProductList(Request $request)
    {
        $id = $request->combo_id;
        $query = "(select id,photo, title,type from products ) as p";
        $products = DB::table('combo_details')
        ->select ('combo_details.price','combo_details.product_id','combo_details.quantity', 'p.title','p.photo','p.id','p.type' )
        ->where('combo_id',$id)->where('combo_details.is_deleted',0) 
        ->leftJoin(\DB::raw($query),'combo_details.product_id','=','p.id')
          ->orderBy('id','ASC')->get();
         
        return response()->json(['msg'=>$products,'status'=>true]);
    }
    public function edit(string $id)
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
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('combo.index').'">Combo</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Điều chỉnh combo </li>';
       
        $combos = \DB::select("select c.id as combo_id, b.* from (select * from combos where id = ".$id.") as c left join products b on c.product_id = b.id");
        if(count($combos) > 0)
        {
            $combo = $combos[0];
            return view('backend.combos.edit',compact('combo','breadcrumb','active_menu'));
        }
        else
        {
            return back()->with('error','không tìm thấy sản phẩm');
        }
       

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "product_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data = $request->importDoc;
        // return $data;
        ///check product inventory//////
        $combo = \App\Models\Combo::where('id',$data['id'])->first();
        if( $combo)
        {
            $combo2 = \App\Models\Combo::where('product_id',$combo->product_id)
            ->where('is_deleted',0)
            ->where('id','<>',$data['id'])->first();
            if ($combo2)
            {
                return response()->json(['msg'=>$data['id'].'','status'=>false]);
            }
            $combo->fill($data)->save();
            \DB::select('update combo_details set is_deleted = 1 where combo_id =' .$combo->id);
           
            $details = $request->products;
            foreach ($details as $detail)
            {
           
                $datac['quantity'] = $detail['quantity'];
                $datac['price'] =  $detail['price'];
                $datac['combo_id'] = $combo['id'];
                $datac['product_id'] = $detail['id'];
                \App\Models\ComboDetail::create($datac);
            }
           
            
            return response()->json(['msg'=>$combo,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'không tìm thấy combo','status'=>false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $func = "product_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $combo = \App\Models\Combo::find($id);
        if($combo)
        {
            \DB::select('update combo_details set is_deleted = 1 where combo_id =' .$id);
            $status = $combo->delete();
            if($status){
                
                return redirect()->route('combo.index')->with('success','Xóa combo thành công!');
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

    public function comboStatus(Request $request)
    {
        $func = "product_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('combos')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('combos')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=> $request->id,'status'=>true]);
    }

}
