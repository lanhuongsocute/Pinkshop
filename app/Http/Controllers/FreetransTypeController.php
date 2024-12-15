<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FreetransType;
class FreetransTypeController extends Controller
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
        $func = "freet_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="freetranstype_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Loại thu chi </li>';
        $freetranstypes=FreetransType::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.freetranstype.index',compact('freetranstypes','breadcrumb','active_menu'));
    }

    public function freetranstypeSearch(Request $request)
    {
        $func = "freet_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="freetranstype_list";
            $searchdata =$request->datasearch;
            $freetranstypes = DB::table('freetrans_types')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetranstype.index').'">Loại thu chi</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            
            return view('backend.freetranstype.search',compact('freetranstypes','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('freetranstype.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function freetranstypeStatus(Request $request)
    {
        $func = "freet_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('freetrans_types')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('freetrans_types')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "freet_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="freetranstype_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetranstype.index').'">Loại thu chi</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo loại thu chi </li>';
        return view('backend.freetranstype.create',compact('breadcrumb','active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "freet_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'title'=>'string|required',
          
            'status'=>'nullable|in:active,inactive',
        ]);
        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $data = $request->all();
       
        
        $status = FreetransType::create($data);
        if($status){
            return redirect()->route('freetranstype.index')->with('success','Tạo loại thu chi thành công!');
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
        $func = "freet_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $freetranstype = FreetransType::find($id);
        if($freetranstype)
        {
            $active_menu="freetranstype_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetranstype.index').'">Loại thu chi</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh Loại thu chi </li>';
            return view('backend.freetranstype.edit',compact('breadcrumb','freetranstype','active_menu'));
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
        //
        $func = "freet_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $freetranstype = FreetransType::find($id);
        if($freetranstype)
        {
            $this->validate($request,[
                'title'=>'string|required',
                
                'status'=>'nullable|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $freetranstype->fill($data)->save();
            if($status){
                return redirect()->route('freetranstype.index')->with('success','Cập nhật thành công');
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
        $func = "freet_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        if(!$this->checkRole(2))
        {
            return redirect()->route('unauthorized');
        }
        $freetranstype = FreetransType::find($id);
        if($freetranstype)
        {
            $freetrans = \App\Models\FreeTransaction::where('type_id',$freetranstype->id)->get();
            if(count($freetrans )> 0)
            {
                return back()->with('error','Không thể xóa vì có thu chi thuộc loại này!');
            }
            else
            {
                $status = $freetranstype->delete();
                if($status){
                    return redirect()->route('freetranstype.index')->with('success','Xóa loại thu chi thành công!');
                }
                else
                {
                    return back()->with('error','Có lỗi xãy ra!');
                } 
            }
               
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
