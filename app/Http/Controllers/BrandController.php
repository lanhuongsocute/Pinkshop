<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
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
        $func = "brand_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="brand_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> nhà sản xuất </li>';
        $brands=Brand::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.brands.index',compact('brands','breadcrumb','active_menu'));
    }

    public function brandSearch(Request $request)
    {
        $func = "brand_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="brand_list";
            $searchdata =$request->datasearch;
            $brands = DB::table('brands')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('brand.index').'">Nhà sản xuất</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.brands.search',compact('brands','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('brand.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function brandStatus(Request $request)
    {
        $func = "brand_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('brands')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('brands')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "brand_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="brand_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('brand.index').'">Nhà sản xuất</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo brands </li>';
        return view('backend.brands.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "brand_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'photo'=>'string|required',
            'status'=>'nullable|in:active,inactive',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->input('title'));
        $slug_count = Brand::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        
        $status = Brand::create($data);
        if($status){
            return redirect()->route('brand.index')->with('success','Tạo brand thành công!');
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
        $func = "brand_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $brand = brand::find($id);
        if($brand)
        {
            $active_menu="brand_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('brand.index').'">Nhà sản xuất</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh nhà sản xuất </li>';
            return view('backend.brands.edit',compact('breadcrumb','brand','active_menu'));
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
        $func = "brand_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $brand = brand::find($id);
        if($brand)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'photo'=>'string|nullable',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            if($request->photo_old == null)
            {
                $data['photo_old'] =   $brand->photo;
            }
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ;
            if($data['photo'] == null || $data['photo']=="")
                    $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');

            $status = $brand->fill($data)->save();
            if($status){
                return redirect()->route('brand.index')->with('success','Cập nhật thành công');
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
        $func = "brand_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $brand = brand::find($id);
        if($brand)
        {
            $status = $brand->delete();
            if($status){
                return redirect()->route('brand.index')->with('success','Xóa brand thành công!');
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
}
