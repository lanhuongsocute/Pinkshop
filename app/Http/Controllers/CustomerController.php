<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\User;
use App\Models\WarehouseIn;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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
    public function BoughtProducts($id)
    {
        $func = "cus_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $user = \App\Models\User::find($id);
        if(!$user)
        {
           return back()->with('error','Không tìm thấy dữ liệu');
        }
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
         ';
           
        $data['wos'] = \App\Models\Warehouseout::where('customer_id',$user->id)->where('status','active')
            ->orderBy('id','desc')->get();

        foreach ($data['wos'] as $wo)
        {
             
            $query = "(select id,photo, title,type,summary,description from products ) as p";
            $products = DB::table('warehouseout_details')
            ->select ('warehouseout_details.price','warehouseout_details.product_id','warehouseout_details.quantity', 'p.title','p.photo','p.id','p.type','p.summary','p.description' )
            ->where('wo_id',$wo->id)->where('doc_type','wo')
            ->leftJoin(\DB::raw($query),'warehouseout_details.product_id','=','p.id')
            ->orderBy('id','ASC')->get();
            foreach($products as $product)
            {
                
                $i = 0;
                $series = "";
                $iproductseris = \App\Models\WarehouseoutDetailSeries::where('wo_id',$wo->id)->where('product_id',$product->id)->where('doc_type','wo')->get();
                // $series = "";
                foreach ($iproductseris as $productseri)
                {
                    if ($i > 0)
                        $series .= ',';
                    $series .= $productseri->seri;
                    $i ++;
                }
                $product->series=$series;
    
            }
            $wo->details = $products;
        }
        $data['user'] = $user;
        return view('backend.customers.chitietmuahang',$data);
    }
    public function index()
    {
        $func = "cus_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="customer_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Khách hàng </li>';
        $customers=User::where('role', 'customer')->orwhere('role','supcustomer')
            ->orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.customers.index',compact('customers','breadcrumb','active_menu'));

    }
    public function customerListWOut(Request $request)
    {
        $func = "cus_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'customer_id'=>'string|required',
        ]);
        $warehouseouts = DB::table('warehouseouts')
        ->where('customer_id', $request->customer_id)
        ->orderBy('id',  'desc')
        ->paginate($this->pagesize)->withQueryString(); 

        $active_menu="customer_list";
        
    
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
         ';
        return view('backend.customers.warehouseout',compact('warehouseouts','breadcrumb','active_menu'));
   
    }
    public function customerSort(Request $request)
    {
        $func = "cus_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="customer_list";
        $searchdata =$request->datasearch;
        $customers = DB::table('users')
        ->where('role', 'customer')->orwhere('role','supcustomer')
        ->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
         ';
        return view('backend.customers.index',compact('customers','breadcrumb','searchdata','active_menu'));
    }

    public function customerStatus(Request $request)
    {
        $func = "cus_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('users')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('users')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "cus_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="customer_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo khách hàng </li>';
        return view('backend.customers.create',compact('breadcrumb','active_menu'));
  
    }
    public function customerJsearch(Request $request)
    {
        if($request->data  )
        { 
            $searchdata =$request->data;
             $customers = DB::table('users')
             ->select ('users.id','users.full_name as title' )
             ->where(function($query1)  use ($searchdata) 
             {
                 $query1 ->where('full_name','LIKE','%'.$searchdata.'%')
                 ->orwhere('phone','LIKE','%'.$searchdata.'%');
             })
            
             ->where(function($query)  
             {
                 $query->where('role', 'customer')
                       ->orWhere('role', 'supcustomer');
             })
             
             ->get();
             
             return response()->json(['msg'=>$customers,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }

    }
    public function customerSearch(Request $request)
    {
        if($request->datasearch)
        {
            $active_menu="customer_list";
            $searchdata =$request->datasearch;
            $customers = DB::table('users') 
            ->where(function($query) use ( $searchdata )
            {
                $query->where('phone','LIKE','%'.$searchdata.'%')
                      ->orWhere('full_name','LIKE','%'.$searchdata.'%');
            })
            ->where(function($query1)  
            {
                $query1->where('role', 'customer')
                      ->orWhere('role', 'supcustomer');
            })
            ->paginate($this->pagesize)->withQueryString();
            // $query = "select * from users where role <>'admin' and (full_name like '%" 
            //             .$request->datasearch."%' or phone like '%".$request->datasearch."%')";
            // $users = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.customers.search',compact('customers','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('customer.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function customerAdd(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'phone'=>'string|required',
            'address'=>'string|required',
            
        ]);
        // return $request->all();
        $data = $request->all();
        $olduser = User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return response()->json(['msg'=>"số điện thoại đã tồn tại",'status'=>false]);
        $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['email'] = $data['phone'].'@gmail.com';
        $data['password']=$data['phone'];
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'customer';
        $data['status'] = 'inactive';
        $data['ugroup_id'] = 1;
        $status = User::c_create($data);
        if($status){
            return response()->json(['msg'=>$status,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>$status,'status'=>false]);
        }    
    }
    public function store(Request $request)
    {
        $func = "cus_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $this->validate($request,[
            'full_name'=>'string|required',
            'description'=>'string|nullable',
            'phone'=>'string|required',
            'address'=>'string|required',
            'status'=>'nullable|in:active,inactive',
        ]);
        // return $request->all();
        $data = $request->all();
        //check user with phone
        $olduser = User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return back()->with('error','Số điện thoại đã tồn tại!');
        $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['email'] = $data['phone'].'@gmail.com';
        $data['password']=$data['phone'];
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'customer';
        $status = User::c_create($data);
        if($status){
            return redirect()->route('customer.index')->with('success','Tạo khách hàng thành công!');
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
        $func = "cus_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $user = User::find($id);
        if($user)
        {
            $active_menu="customer_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">xem công nợ khách hàng</li>';
            $suptrans = \App\Models\SupTransaction::where('supplier_id',$id)
                ->orderBy('id','DESC')
                ->paginate($this->pagesize*2)->withQueryString();;
            
            return view('backend.suptrans.show',compact('breadcrumb','active_menu','user','suptrans'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $func = "cus_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $customer = User::find($id);
        if($customer)
        {
            $active_menu="sup_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh khách hàng </li>';
            
            return view('backend.customers.edit',compact('breadcrumb','customer','active_menu' ));
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
        $func = "cus_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $user = User::find($id);
        if($user)
        {
            $this->validate($request,[
                'full_name'=>'string|required',
                'description'=>'string|nullable',
                'address'=>'string|required',
                'status'=>'nullable|in:active,inactive',
            ]);
    
            $data = $request->all();
            $status = $user->fill($data)->save();
            \App\Models\User::c_update($user);
            if($status){

                return redirect()->route('customer.index')->with('success','Cập nhật thành công');
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
        $func = "cus_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $user = User::find($id);
        
        if($user)
        {
            $status = User::deleteUser($id);
            if($status){
                return redirect()->route('customer.index')->with('success','Xóa thành công!');
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
