<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Warehouse;

class WarehouseController extends Controller
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
        $func = "warh_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="wh_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> kho </li>';
        $warehouses=Warehouse::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.warehouses.index',compact('warehouses','breadcrumb','active_menu'));
    }
    public function warehouseSearch(Request $request)
    {
        $func = "warh_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="wh_list";
            $searchdata =$request->datasearch;
            $warehouses = DB::table('warehouses')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouse.index').'">Kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.warehouses.search',compact('warehouses','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('warehouse.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function warehouseStatus(Request $request)
    {
        $func = "warh_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->id == 1)
        {
            return response()->json(['msg'=>"Đây là kho mặc định!",'status'=>false]);
        }
        if($request->mode =='true')
        {
            DB::table('warehouses')->where('id','<>',1)->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('warehouses')->where('id','<>',1)->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "warh_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="wh_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouse.index').'">Kho</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo kho </li>';
        return view('backend.warehouses.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "warh_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'description'=>'string|nullable',
            'status'=>'required|in:active,inactive',
        ]);
        $data = $request->all();
        
        $status = warehouse::create($data);
        if($status){
            return redirect()->route('warehouse.index')->with('success','Tạo warehouse thành công!');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
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
        $func = "warh_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if($id == 1)
        {
            return back()->with('error','Không thể điều chỉnh');
        }
        $warehouse = Warehouse::find($id);
        if($warehouse)
        {
            $active_menu="warehouse_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('warehouse.index').'">Danh sách kho</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh nhóm người dùng </li>';
            return view('backend.warehouses.edit',compact('breadcrumb','warehouse','active_menu'));
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
        $func = "warh_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if($id == 1)
        {
            return back()->with('error','Không thể điều chỉnh');
        }
        $warehouse = Warehouse::find($id);
        if($warehouse)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'description'=>'string|nullable',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $warehouse->fill($data)->save();
            if($status){
                return redirect()->route('warehouse.index')->with('success','Cập nhật thành công');
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
        $func = "warh_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $warehouse = Warehouse::find($id);
        if($warehouse && $id != 1)
        {
            $status = Warehouse::deletewarehouse($id);
            if($status){
                return redirect()->route('warehouse.index')->with('success','Xóa kho thành công!');
            }
            else
            {
                return back()->with('error','Vẫn còn giá liên quan nhóm khách hàng, không thể xóa!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
