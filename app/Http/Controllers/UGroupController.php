<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UGroup;

class UGroupController extends Controller
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
        $func = "ugroup_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ugroup_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> nhóm người dùng </li>';
        $ugroups=UGroup::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.ugroups.index',compact('ugroups','breadcrumb','active_menu'));
    }
    public function ugroupSearch(Request $request)
    {
        $func = "ugroup_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="ugroup_list";
            $searchdata =$request->datasearch;
            $ugroups = DB::table('u_groups')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('ugroup.index').'">nhóm người dùng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.ugroups.search',compact('ugroups','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('ugroup.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function ugroupStatus(Request $request)
    {
        $func = "ugroup_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($id == 1)
        {
            return back()->with('error','Không thể điều chỉnh');
        }
        if($request->mode =='true')
        {
            DB::table('u_groups')->where('id','<>',1)->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('u_groups')->where('id','<>',1)->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "ugroup_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ugroup_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('ugroup.index').'">Nhóm người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo nhóm người dùng </li>';
        return view('backend.ugroups.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "ugroup_add";
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
        
        $status = ugroup::create($data);
        if($status){
            return redirect()->route('ugroup.index')->with('success','Tạo ugroup thành công!');
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
        $func = "ugroup_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if($id == 1)
        {
            return back()->with('error','Không thể điều chỉnh');
        }
        $ugroup = UGroup::find($id);
        if($ugroup)
        {
            $active_menu="ugroup_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('ugroup.index').'">Nhóm người dùng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh nhóm người dùng </li>';
            return view('backend.ugroups.edit',compact('breadcrumb','ugroup','active_menu'));
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
        $func = "ugroup_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if($id == 1)
        {
            return back()->with('error','Không thể điều chỉnh');
        }
        $ugroup = UGroup::find($id);
        if($ugroup)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'description'=>'string|nullable',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $ugroup->fill($data)->save();
            if($status){
                return redirect()->route('ugroup.index')->with('success','Cập nhật thành công');
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
        $func = "ugroup_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $ugroup = UGroup::find($id);
        if($ugroup && $id != 1)
        {
            $status = UGroup::deleteUgroup($id);
            if($status){
                return redirect()->route('ugroup.index')->with('success','Xóa nhóm người dùng thành công!');
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
