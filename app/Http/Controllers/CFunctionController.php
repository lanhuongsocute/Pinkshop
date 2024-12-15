<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\CmdFunction;
use Illuminate\Support\Facades\DB;

class CFunctionController extends Controller
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
        $func = "cfunc_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="cmdfunction_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> cmdfunctions </li>';
        $cmdfunctions=CmdFunction::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.cmdfunctions.index',compact('cmdfunctions','breadcrumb','active_menu'));
    }
    public function cmdfunctionSearch(Request $request)
    {
        $func = "cfunc_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="cmdfunction_list";
            $searchdata =$request->datasearch;
            $cmdfunctions = DB::table('cmd_functions')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('alias','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('cmdfunction.index').'">cmdfunctions</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.cmdfunctions.search',compact('cmdfunctions','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('cmdfunction.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function cmdfunctionStatus(Request $request)
    {
        $func = "cfunc_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('cmd_functions')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('cmd_functions')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "cfunc_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="cmdfunction_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('cmdfunction.index').'">cmdfunctions</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo cmdfunctions </li>';
        return view('backend.cmdfunctions.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "cfunc_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'alias'=>'string|required',
            'title'=>'string|required',
            'status'=>'nullable|in:active,inactive',
        ]);
        $data = $request->all();
        $status = CmdFunction::create($data);
        if($status){
            return redirect()->route('cmdfunction.index')->with('success','Tạo cmdfunction thành công!');
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
        $func = "cfunc_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
      
        $cmdfunction = CmdFunction::find($id);
        if($cmdfunction)
        {
            $active_menu="cmdfunction_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('cmdfunction.index').'">cmdfunctions</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh cmdfunctions </li>';
            return view('backend.cmdfunctions.edit',compact('breadcrumb','cmdfunction','active_menu'));
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
        $func = "cfunc_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $cmdfunction = CmdFunction::find($id);
        if($cmdfunction)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $cmdfunction->fill($data)->save();
            if($status){
                return redirect()->route('cmdfunction.index')->with('success','Cập nhật thành công');
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
        $func = "cfunc_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $cmdfunction = CmdFunction::find($id);
        if($cmdfunction)
        {
            $cfs = \App\Models\RoleFunction::where('cfunction_id',$id)->get();
            
            if(count($cfs) > 0)
            {
                return back()->with('error','Có role sử dụng cmdfunction này! Không thể xóa!');
            }
           
            $status = $cmdfunction->delete();
            if($status){
                return redirect()->route('cmdfunction.index')->with('success','Xóa cmdfunction thành công!');
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
