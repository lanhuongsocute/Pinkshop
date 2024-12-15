<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\InventoryDestroy;

class InventoryDestroyController extends Controller
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

    public function inventorydView($id)
    {
        $func = "invd_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $inventory = \App\Models\InventoryDestroy::find($id);
        if($inventory)
        {
            $data['product'] = \App\Models\Product::find($inventory->product_id);
            $data['series']=\DB::select(  ' select a.* from (select * from  destroy_series where is_sold = 0 and product_id ='.$inventory->product_id
                .'  ) as a ' );

            $data['wtds']= \App\Models\WarehouseToDestroy::where('product_id',$data['product']->id)->get();
       
            $data['inventory'] = $inventory;
            $data['active_menu']="pro_inv";
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page"> <a href="'.route('inventorydestroy.index').'">ds hàng hóa tồn kho tài sản</a></li>
            <li class="breadcrumb-item active" aria-current="page"> ds chi tiết series</li>';
            return view('backend.inventorydestroys.series',$data);
        }
        else
        {
            return back()->with('error','Không tìm thấy sản phẩm tồn kho tài sản!');
        }
    }

    public function index()
    {
        $func = "invd_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="des_inv";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa kho hủy</li>';
        $ids=InventoryDestroy::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.inventorydestroys.index',compact('ids','breadcrumb','active_menu'));

    }
    public function inventorySort(Request $request)
    {
        $func = "invd_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="des_inv";
        $searchdata =$request->datasearch;
        $ids = DB::table('inventory_destroys')->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa kho hủy</li>';
        return view('backend.inventorydestroys.index',compact('ids','breadcrumb','active_menu'));
    }
    public function inventorySearch(Request $request)
    {
        $func = "invd_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="des_inv";
            $searchdata =$request->datasearch;
            $query = "(select id as idpro, title from products where title like'%".$searchdata."%') as np";
            $ids = DB::table('inventory_destroys')
            ->select('inventory_destroys.*' )
            ->join(\DB::raw($query),
            'inventory_destroys.product_id', '=', 'np.idpro') 
            ->paginate($this->pagesize)->withQueryString();
            
            // $inventorys = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventorydestroy.index').'">Tồn kho hủy</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.inventorydestroys.search',compact('ids','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('inventorydestroy.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
