<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Str;
use App\Models\Comment;
class CommentController extends Controller
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

        echo 'Current PHP version: ' . phpversion();
        $func = "comment_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="comment_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách bình luận </li>';
        $comments = Comment::orderBy('id','DESC')->paginate($this->pagesize);
        // categories
        return view('backend.comment.index',compact('comments','breadcrumb','active_menu'));

    }
    public function create()
    {
        $func = "comment_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['categories'] = \App\Models\Comment::where('status','active')->orderBy('id','DESC')->get();
         $data['active_menu']="comment_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('comment.index').'">bình luận</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo bình luận </li>';
        return view('backend.comment.create', $data);
  
    }
    public function store(Request $request)
    {
        $func = "comment_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required',
            'content'=>'string|required',
            'url'=>'string|required',
            'email'=>'string|nullable',
        ]);
        
        $data = $request->all();
        $cat = Comment::create($data);
        return redirect()->route('comment.index')->with('success','Tạo comment thành công!');
    }
    public function edit(string $id)
    {
        $func = "comment_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $comment = Comment::find($id);
        $active_menu="comment_list";
        if($comment)
        {
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('comment.index').'">Danh mục</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh danh mục </li>';
             
            return view('backend.comment.edit',compact('breadcrumb','comment','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function update(Request $request, string $id)
    {
        $func = "comment_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $comment = Comment::find($id);
        if($comment)
        {
            $this->validate($request,[
                'name'=>'string|required',
                'content'=>'string|required',
                'url'=>'string|required',
                'email'=>'string|nullable',
            ]);
            $data = $request->all();
            $status = $comment->fill($data)->save();
            if($status){
                return redirect()->route('comment.index')->with('success','Cập nhật thành công');
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
        $func = "comment_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $comment = comment::find($id);
        if($comment)
        {
            $status = $comment->delete();
            if($status){
                return redirect()->route('comment.index')->with('success','Xóa comment thành công!');
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
    public function commentStatus(Request $request)
    {
        $func = "comment_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        if($request->mode =='true')
        {
            DB::table('comments')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('comments')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    public function commentSearch(Request $request)
    {
        $func = "comment_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="comment_list";
            $searchdata =$request->datasearch;
            $comments = DB::table('comments')->where('content','LIKE','%'.$request->datasearch.'%')->orWhere('name','LIKE','%'.$request->datasearch.'%')->orWhere('url','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('comment.index').'">bình luận</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.comment.search',compact('comments','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('comment.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
}
