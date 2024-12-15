<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
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
    public function roleFunction($id)
    {
        $func = "role_function";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $role = Role::find($id);
        if(!$role)
        {
            return back()->with('error','Không tìm thấy!');
        }
        
        $role_functions = DB::select("select cmd_functions.*, b.value from cmd_functions left join (select * from role_functions where role_id =".$id." ) as b on cmd_functions.id = b.cfunction_id where  cmd_functions.status = 'active'");
       
        $active_menu="role_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('role.index').'">roles</a></li>
        <li class="breadcrumb-item active" aria-current="page"> điều chỉnh roles </li>';
   
        return view('backend.roles.cfunction',compact('role','breadcrumb','active_menu','role_functions'));
 
    }
    public function roleSelectall($id)
    {
        $func = "role_function";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $role = Role::find($id);
        if(!$role)
        {
            return back()->with('error','Không tìm thấy!');
        }
        $cfunctions = \App\Models\CmdFunction::where('status','active')->get();
        if(count($cfunctions)==0)
        {
            return back()->with('error','Không tìm thấy!');
        }
        foreach ($cfunctions as $cfunction)
        {
            $role_function = \App\Models\RoleFunction::where('role_id',$id)
            ->where('cfunction_id',$cfunction->id)->first();
            if(!$role_function)
            {
                $data['role_id'] = $id;
                $data['cfunction_id'] = $cfunction->id;
                $data['value'] =1;
                $role_function =  \App\Models\RoleFunction::create($data);
            }
            else
            {
                $role_function->value =  1;
                $role_function->save();
            }
        }
        return back()->with('success','Đã cập nhật!');
    }
   
    public function roleFucntionStatus(Request $request)
    {
        $func = "role_function";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'role_id'=>'numeric|required',
             
        ]);
        $role = Role::find($request->role_id);
        if(!$role)
        {
            return response()->json(['msg'=>"Không tìm thấy dữ liệu",'status'=>false]);
        }
        
        $cfunction = \App\Models\CmdFunction::find($request->id);
        if(!$cfunction)
        {
            return response()->json(['msg'=>"Không tìm thấy dữ liệu",'status'=>false]);
        }
        $role_function = \App\Models\RoleFunction::where('role_id',$request->role_id)
            ->where('cfunction_id',$request->id)->first();
        if(!$role_function)
        {
            $data['role_id'] = $request->role_id;
            $data['cfunction_id'] = $request->id;
            $data['value'] = $request->mode=='true'?1:0;
            $role_function =  \App\Models\RoleFunction::create($data);
        }
        else
        {
            $role_function->value =  $request->mode=='true'?1:0;
            $role_function->save();
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }

    public function index()
    {
        //
        $func = "role_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="role_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> roles </li>';
        $roles=Role::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.roles.index',compact('roles','breadcrumb','active_menu'));
    }
    public function roleSearch(Request $request)
    {
        $func = "role_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="role_list";
            $searchdata =$request->datasearch;
            $roles = DB::table('roles')->where('title','LIKE','%'.$request->datasearch.'%')->orWhere('alias','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('role.index').'">roles</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.roles.search',compact('roles','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('role.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function roleStatus(Request $request)
    {
        $func = "role_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('roles')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('roles')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "role_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="role_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('role.index').'">roles</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo roles </li>';
        return view('backend.roles.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "role_add";
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
        $status = Role::create($data);
        if($status){
            return redirect()->route('role.index')->with('success','Tạo role thành công!');
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
        $func = "role_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $role = Role::find($id);
        if($role)
        {
            $active_menu="role_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('role.index').'">roles</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh roles </li>';
            return view('backend.roles.edit',compact('breadcrumb','role','active_menu'));
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
        $func = "role_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $role = Role::find($id);
        if($role)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $role->fill($data)->save();
            if($status){
                return redirect()->route('role.index')->with('success','Cập nhật thành công');
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
        //
        $func = "role_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $role = Role::find($id);
        if($role)
        {
            $users = User::where('role',$role->alias)->get();
            if($role->id <= 6)
            {
                return back()->with('error','Đây là các role mặc định, không thể xóa!');
            }
            if(count($users) > 0)
            {
                return back()->with('error','Có tài khoản thuộc role này! Không thể xóa!');
            }
           
            $status = $role->delete();
            if($status){
                return redirect()->route('role.index')->with('success','Xóa role thành công!');
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
