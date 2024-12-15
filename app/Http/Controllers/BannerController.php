<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Banner;
class BannerController extends Controller
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
        $func = "ban_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="banner_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Banners </li>';
        $banners=Banner::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.banners.index',compact('banners','breadcrumb','active_menu'));
    }
    public function bannerSearch(Request $request)
    {
        $func = "ban_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="banner_list";
            $searchdata =$request->datasearch;
            $banners = DB::table('banners')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('banner.index').'">Banners</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.banners.search',compact('banners','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('banner.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function bannerStatus(Request $request)
    {
        $func = "ban_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('banners')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('banners')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "ban_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="banner_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('banner.index').'">Banners</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo banners </li>';
        return view('backend.banners.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "ban_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'string|required',
            'condition'=>'nullable|in:banner,promo,ads,modpro',
            'status'=>'nullable|in:active,inactive',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->input('title'));
        $slug_count = Banner::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        
        $status = Banner::create($data);
        if($status){
            return redirect()->route('banner.index')->with('success','Tạo banner thành công!');
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
        $func = "ban_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
      
        $banner = Banner::find($id);
        if($banner)
        {
            $active_menu="banner_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('banner.index').'">Banners</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh banners </li>';
            return view('backend.banners.edit',compact('breadcrumb','banner','active_menu'));
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
        $func = "ban_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $banner = Banner::find($id);
        if($banner)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'description'=>'string|nullable',
                'photo'=>'string|nullable',
                'condition'=>'nullable|in:banner,promo,ads,modpro',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
             // ------end replace --///
             if($request->photo_old == null)
             {
                 $data['photo_old'] =   $banner->photo;
             }
               
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ;
            if($data['photo'] == null || $data['photo']=="")
                 $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
            $status = $banner->fill($data)->save();
            if($status){
                return redirect()->route('banner.index')->with('success','Cập nhật thành công');
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
        $func = "ban_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $banner = Banner::find($id);
        if($banner)
        {
            $status = $banner->delete();
            if($status){
                return redirect()->route('banner.index')->with('success','Xóa banner thành công!');
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
