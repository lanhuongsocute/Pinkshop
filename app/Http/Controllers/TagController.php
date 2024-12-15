<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function store_blog_tag($blog_id,$tag_ids)
    {
        if(!$tag_ids || count($tag_ids) == 0)
            return;
        foreach($tag_ids as $tag_id)
        {
            $tag = Tag::find($tag_id);
            if(!$tag)
            {
                $datatag['title'] = $tag_id;
                $slug = Str::slug( $datatag['title'] );
                $slug_count = Tag::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $slug .= time().'-'.$slug;
                }
                $datatag['slug'] = $slug;
                
                $tag = Tag::create($datatag);
                sleep(1);
            }
            $data['tag_id'] = $tag->id;
            $data['blog_id'] = $blog_id;
            \App\Models\TagBlog::create($data);
            $tag->hit += 1;
            $tag->save();
        }
    }

    public function update_blog_tag($blog_id,$tag_ids)
    {
        $sql = "delete from tag_blogs where blog_id = ".$blog_id;
        DB::select($sql);
         $this->store_blog_tag($blog_id,$tag_ids);
    }
    public function update_product_tag($product_id,$tag_ids)
    {
        $sql = "delete from tag_products where product_id = ".$product_id;
        DB::select($sql);
         $this->store_product_tag($product_id,$tag_ids);
    }
    public function store_product_tag($product_id,$tag_ids)
    {
        if(!$tag_ids || count($tag_ids) == 0)
            return;
        foreach($tag_ids as $tag_id)
        {
            $tag = Tag::find($tag_id);
            if(!$tag)
            {
                $datatag['title'] = $tag_id;
                $slug = Str::slug( $datatag['title'] );
                $slug_count = Tag::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $slug .= time().'-'.$slug;
                }
                $datatag['slug'] = $slug;
                
                $tag = Tag::create($datatag);

            }
            $data['tag_id'] = $tag->id;
            $data['product_id'] = $product_id;
            \App\Models\TagProduct::create($data);
            $tag->hit += 1;
            $tag->save();
            sleep(1);
        }
    }

    public function index()
    {
        $func = "tag_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="tag_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tags </li>';
        $tags=Tag::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.tags.index',compact('tags','breadcrumb','active_menu'));
    }
    public function tagSearch(Request $request)
    {
        $func = "tag_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="tag_list";
            $searchdata =$request->datasearch;
            $tags = DB::table('tags')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('tag.index').'">tags</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.tags.search',compact('tags','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('tag.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function tagStatus(Request $request)
    {
        $func = "tag_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('tags')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('tags')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "tag_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="tag_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('tag.index').'">tags</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo tags </li>';
        return view('backend.tags.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "tag_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'status'=>'nullable|in:active,inactive',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->input('title'));
        $slug_count = Tag::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        
        $status = Tag::create($data);
        if($status){
            return redirect()->route('tag.index')->with('success','Tạo tag thành công!');
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
        //
        $func = "tag_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $tag = Tag::find($id);
        if($tag)
        {
            $active_menu="tag_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('tag.index').'">tags</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh tags </li>';
            return view('backend.tags.edit',compact('breadcrumb','tag','active_menu'));
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
        $func = "tag_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $tag = Tag::find($id);
        if($tag)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $tag->fill($data)->save();
            if($status){
                return redirect()->route('tag.index')->with('success','Cập nhật thành công');
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
        $func = "tag_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $tag = Tag::find($id);
        if($tag)
        {
            $status = $tag->delete();
            if($status){
                return redirect()->route('tag.index')->with('success','Xóa tag thành công!');
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
