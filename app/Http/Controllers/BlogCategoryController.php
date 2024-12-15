<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Str;
use App\Models\BlogCategory;
class BlogCategoryController extends Controller
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
        $func = "bcat_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="blogcat_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh mục bài viết </li>';
        $blogcats = BlogCategory::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.blogcat.index',compact('blogcats','breadcrumb','active_menu'));

    }
    public function blogcatSearch(Request $request)
    {
        $func = "bcat_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="blogcat_list";
            $searchdata =$request->datasearch;
            $blogcats = DB::table('blog_categories')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blogcategory.index').'">Danh mục bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.blogcat.search',compact('blogcats','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('banner.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "bcat_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="blogcat_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blogcategory.index').'">Danh mục bài viết</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo danh mục bài viết </li>';
        return view('backend.blogcat.create',compact('breadcrumb','active_menu'));
  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "bcat_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'photo'=>'string|nullable',
            'status'=>'required|in:active,inactive',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->input('title'));
        $slug_count = BlogCategory::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
       
        $status = BlogCategory::create($data);
        if($status){
            return redirect()->route('blogcategory.index')->with('success','Tạo danh mục bài viết thành công!');
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
        $func = "bcat_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blogcat = BlogCategory::find($id);
        if($blogcat)
        {
            $active_menu="blogcat_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blogcategory.index').'">Danh mục bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh mục bài viết </li>';
             return view('backend.blogcat.edit',compact('breadcrumb','blogcat','active_menu'));
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
        $func = "bcat_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blogcat = BlogCategory::find($id);
        if($blogcat)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'photo'=>'string|nullable',
                'status'=>'required|in:active,inactive',
            ]);
            $data = $request->all();
            if($request->photo_old == null)
            {
                $data['photo_old'] =   $blogcat->photo;
            }
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ;
            if($data['photo'] == null || $data['photo']=="")
                    $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
            
       
            $status = $blogcat->fill($data)->save();
            if($status){
                return redirect()->route('blogcategory.index')->with('success','Cập nhật thành công');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "bcat_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blogcat = BlogCategory::find($id);
        if($blogcat)
        {
            $status = $blogcat->delete();
            if($status){
                return redirect()->route('blogcategory.index')->with('success','Xóa danh mục thành công!');
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
    public function blogcatStatus(Request $request)
    {
        if($request->mode =='true')
        {
            DB::table('blog_categories')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('blog_categories')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
}
