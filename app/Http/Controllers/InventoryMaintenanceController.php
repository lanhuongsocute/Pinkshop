<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\InventoryMaintenance;

class InventoryMaintenanceController extends Controller
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
    public function inventorymView($id)
    {
        $func = "invm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $inventory = \App\Models\InventoryMaintenance::find($id);
        if($inventory)
        {
            $data['product'] = \App\Models\Product::find($inventory->product_id);
            $data['series']=\DB::select(  ' select a.* from (select * from  maintain_series where is_sold = 0 and product_id ='.$inventory->product_id
                .'  ) as a ' );

            $data['wtms']= \App\Models\Warehousetomaintain::where('product_id',$data['product']->id)->get();
            $data['mtws']= \App\Models\MaintainToWarehouse::where('product_id',$data['product']->id)->get();
       
           
            $data['inventory'] = $inventory;
            $data['active_menu']="main_inv";
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page"> <a href="'.route('inventorymaintenance.index').'">ds hàng hóa tồn kho bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> ds chi tiết series</li>';
            return view('backend.inventorymaintenance.series',$data);
        }
        else
        {
            return back()->with('error','Không tìm thấy sản phẩm tồn kho bảo hành!');
        }
    }
    public function index()
    {
        $func = "invm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="main_inv";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa tồn kho bảo hành</li>';
        $inventorymaintenances=InventoryMaintenance::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.inventorymaintenance.index',compact('inventorymaintenances','breadcrumb','active_menu'));

    }
    public function inventorySort(Request $request)
    {
        $func = "invm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="main_inv";
        $searchdata =$request->datasearch;
        $inventorymaintenances = DB::table('inventory_maintenances')->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> ds hàng hóa tồn kho bảo hành</li>';
        return view('backend.inventorymaintenance.index',compact('inventorymaintenances','breadcrumb','active_menu'));
    }
    public function inventorySearch(Request $request)
    {
        $func = "invm_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="main_inv";
            $searchdata =$request->datasearch;
            $query = "(select id as idpro, title from products where title like'%".$searchdata."%') as np";
            $inventorymaintenances = DB::table('inventory_maintenances')
            ->select('inventory_maintenances.*' )
            ->join(\DB::raw($query),
            'inventory_maintenances.product_id', '=', 'np.idpro') 
            ->paginate($this->pagesize)->withQueryString();
            
            // $inventorys = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('inventorymaintenance.index').'">Tồn kho bảo hành</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.inventorymaintenance.search',compact('inventorymaintenances','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('inventorymaintenance.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    
    public function inventorySaveToShop(Request $request)
    {
        
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
