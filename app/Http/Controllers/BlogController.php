<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Str;
use App\Models\Blog;

class BlogController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "blog_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="blog_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách bài viết </li>';
        $blogs = Blog::orderBy('id','DESC')->paginate($this->pagesize);
        // categories
        return view('backend.blog.index',compact('blogs','breadcrumb','active_menu'));

    }
    public function create()
    {
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['categories'] = \App\Models\BlogCategory::where('status','active')->orderBy('title','ASC')->get();
        $data['tags'] = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $data['active_menu']="blog_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blog.index').'">bài viết</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo bài viết </li>';
        return view('backend.blog.create', $data);
  
    }
     
    public function store(Request $request)
    {
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
      
        //
        $this->validate($request,[
            'title'=>'string|required',
            'photo'=>'string|nullable',
            'summary'=>'string|required',
            'content'=>'string|required',
            'photo'=>'string|nullable',
            'cat_id'=>'numeric|nullable',
            'status'=>'required|in:active,inactive',
        ]);
        $tag_ids = $request->tag_ids;
        $data = $request->all();
        /// ------end replace --///
        $helpController = new \App\Http\Controllers\HelpController();
        $data['content'] = $helpController->uploadImageInContent( $data['content'] );
        $data['content'] = $helpController->removeImageStyle( $data['content'] );
        
        // ------end replace --///

        $slug = Str::slug($request->input('title'));
        $slug_count = Blog::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['user_id'] = auth()->user()->id;
        $blog = Blog::create($data);
        $tagservice = new \App\Http\Controllers\TagController();
        $tagservice->store_blog_tag($blog->id,$tag_ids);
        if($blog){
            return redirect()->route('blog.index')->with('success','Tạo bài viết thành công!');
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
        $func = "blog_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $categories = \App\Models\BlogCategory::where('status','active')->orderBy('title','ASC')->get();
        $blog = Blog::find($id);
        $tags  = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids =DB::select("select tag_id from tag_blogs where blog_id = ".$blog->id)  ;
        if($blog)
        {
            $active_menu="blog_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blog.index').'">bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh bài viết </li>';
            return view('backend.blog.edit',compact('breadcrumb','blog','active_menu','categories','tag_ids','tags'));
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
        $func = "blog_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blog = Blog::find($id);
        if($blog)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'photo'=>'string|nullable',
                'summary'=>'string|required',
                'content'=>'string|required',
                'photo'=>'string|nullable',
                'cat_id'=>'numeric|nullable',
                'status'=>'required|in:active,inactive',
            ]);
            $data = $request->all();

            /// ------end replace --///
            $helpController = new \App\Http\Controllers\HelpController();
            $data['content'] = $helpController->uploadImageInContent( $data['content'] );
            //   $data['content'] = $helpController->removeImageStyle( $data['content'] );
        
            // ------end replace --///
            if($request->photo_old == null)
            {
                $data['photo_old'] =   $blog->photo;
            }
              
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ;
            // else
            //     $data['photo'] = $data['photo'].",". $data['photo_old']   ;

            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
       
            $status = $blog->fill($data)->save();
            $tagservice = new \App\Http\Controllers\TagController();
            $tag_ids = $request->tag_ids;
            $tagservice->update_blog_tag($blog->id,$tag_ids);
            if($status){
                return redirect()->route('blog.index')->with('success','Cập nhật thành công');
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
        $func = "blog_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blog = Blog::find($id);
        if($blog)
        {
            $status = $blog->delete();
            if($status){
                return redirect()->route('blog.index')->with('success','Xóa danh mục thành công!');
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
    public function blogStatus(Request $request)
    {
        $func = "blog_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        if($request->mode =='true')
        {
            DB::table('blogs')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('blogs')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    public function blogSearch(Request $request)
    {
        $func = "blog_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="blog_list";
            $searchdata =$request->datasearch;
            $blogs = DB::table('blogs')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('content','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('blog.index').'">Bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.blog.search',compact('blogs','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('blog.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}
