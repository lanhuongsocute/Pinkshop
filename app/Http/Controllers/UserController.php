<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
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
    public function userViewProfile()
    {
        
        $active_menu="dasboard";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">profile </li>';
        
        $user=auth()->user();
        
        
        return view('backend.users.profile',compact('user','breadcrumb','active_menu'));

    }
    public function userUpdateProfile(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'address'=>'string|required',
        ]);
        $user = auth()->user();
        $data = $request->all();
        // dd($data);
        $auth = \Auth::user();
        if (isset($data['current_password']) && isset($data['new_password']))
        {
          
            // dd($request->get('current_password'));
                // The passwords matches
            if (!Hash::check($request->get('current_password'), $auth->password)) 
            {
                return back()->with('error', "Mật khẩu hiện tại không đúng!");
            }
            // dd(!Hash::check($request->get('current_password'), $auth->password));
            // Current password and new password same
            if (strcmp($request->get('current_password'), $request->new_password) == 0) 
            {
                return redirect()->back()->with("error", "Mật khẩu mới phải khác mật khẩu hiện tại.");
            }
     
            $user =  User::find($auth->id);
            $user->password =  Hash::make($request->new_password);
            $user->save();
        }
        $user->full_name = $data['full_name'];
        $user->address = $data['address'];
        if(isset($data['photo']) && $data['photo'] != "")
            $user->photo = $data['photo'];
        $user->save();
        return redirect()->route('user.profileview')->with('success','Cập nhật thành công!');
    }
    public function index()
    {
        //
        $func = "user_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ctm_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">người dùng </li>';
        
        $users=DB::table('users')->select('users.*','roles.title')
        ->leftJoin(\DB::raw('(select * from roles) as roles'),'users.role','=','roles.alias')
        ->where('users.role','<>','admin') ->where('users.role','<>','soft')
        ->paginate($this->pagesize);
        
        return view('backend.users.index',compact('users','breadcrumb','active_menu'));
    }
 
    
    public function userSort(Request $request)
    {
        $func = "user_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="ctm_list";
        $searchdata =$request->datasearch;
        $users = DB::table('users')->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Người dùng</a></li>
         ';
        return view('backend.users.index',compact('users','breadcrumb','searchdata','active_menu'));
    }

    public function userStatus(Request $request)
    {
        $func = "user_edit";
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
        //
        $func = "user_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="ctm_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Người dùng</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo người dùng </li>';
        $ugroups = UGroup::where('status','active')->orderBy('id','ASC')->get();
        $uroles= \App\Models\Role::where('status','active')->where('alias','<>','admin')->orderBy('id','ASC')->get();
        return view('backend.users.create',compact('breadcrumb','active_menu','ugroups','uroles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // 
        $func = "user_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'full_name'=>'string|required',
            'email'=>'email|nullable',
            'description'=>'string|nullable',
            'photo'=>'string|nullable',
            'phone'=>'string|required',
            'password'=>'string|nullable',
            'address'=>'string|required',
            'ugroup_id'=>'numeric|required',
             'role'=>'required|string',
            'status'=>'nullable|in:active,inactive',
        ]);
        // return $request->all();
        $data = $request->all();
        //check user with phone
        $olduser = User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return back()->with('error','Số điện thoại đã tồn tại!');

        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        if($request->photo != null)
        {
            $photos = explode(',', $data['photo']);
            if(count ($photos) > 0)
                $data['photo'] = $photos[0];
        }
        if($request->email == null)
            $data['email'] = $data['phone'].'@gmail.com';
        if($request->password == null)
            $data['password']=$data['phone'];
        $olduser = User::where('email',$data['email'])->get();
        if(count($olduser) > 0)
                return back()->with('error','Email đã tồn tại!');
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $status = User::c_create($data);
        if($status){
            return redirect()->route('user.index')->with('success','Tạo người dùng thành công!');
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
    public function userDetail (Request $request)
    {
        //
        if($request->id)
        {
            $user = User::find($request->id);
            if($user)
            {
                return response()->json(['msg'=>$user,'status'=>true]);
            }
        }
        return response()->json(['msg'=>'','status'=>false]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $func = "user_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $user = User::find($id);
        if($user)
        {
            $active_menu="ctm_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Người dùng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh người dùng </li>';
            $ugroups = UGroup::where('status','active')->orderBy('id','ASC')->get();
            $uroles= \App\Models\Role::where('status','active')->where('alias','<>','admin')->orderBy('id','ASC')->get();
      
            return view('backend.users.edit',compact('breadcrumb','user','active_menu','ugroups','uroles' ));
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
        // return $request->all();
        $func = "user_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $user = User::find($id);
        if($user)
        {
            $this->validate($request,[
                'full_name'=>'string|required',
                'email'=>'email|required',
                'description'=>'string|nullable',
                'photo'=>'string|nullable',
                'password'=>'string|nullable',
                'address'=>'string|required',
                'ugroup_id'=>'numeric|required',
                 'role'=>'required|in:admin,vendor,manager,customer,supplier,supcustomer',
                'status'=>'nullable|in:active,inactive',
            ]);
    
            $data = $request->all();
            if($request->photo_old == null)
            {
                $data['photo_old'] =   $user->photo;
            }
            if($data['photo'] == null || $data['photo']=="")
                $data['photo'] = $data['photo_old'] ;
            if($data['photo'] == null || $data['photo']=="")
                    $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');

            $olduser = User::where('email',$data['email'])->where('id','<>',$id)->get();
            if(count($olduser) > 0)
                return back()->with('error','Email đã tồn tại!');
            if(isset($data['phone']))
            {
                $olduser = User::where('phone',$data['phone'])->where('id','<>',$id)->get();
                if(count($olduser) > 0)
                    return back()->with('error','Điện thoại  đã tồn tại!');
            }
           
            if($request->password == null)            
                $data['password'] = $user->password;
            else
                $data['password'] = Hash::make($data['password']);
            $status = $user->fill($data)->save();
            \App\Models\User::c_update( $user);
            if($status){
                return redirect()->route('user.index')->with('success','Cập nhật thành công');
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
        $func = "user_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $user = User::find($id);
        
        if($user)
        {
            $status = User::deleteUser($id);
            if($status){
                return redirect()->route('user.index')->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','không thể xóa!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function userSearch(Request $request)
    {
        $func = "user_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="ctm_list";
            $searchdata =$request->datasearch;
            $users = DB::table('users')->where('role','<>','admin')
            ->where(function($query) use ( $searchdata )
            {
                $query->where('phone','LIKE','%'.$searchdata.'%')
                      ->orWhere('full_name','LIKE','%'.$searchdata.'%');
            })
            ->paginate($this->pagesize)->withQueryString();
            // $query = "select * from users where role <>'admin' and (full_name like '%" 
            //             .$request->datasearch."%' or phone like '%".$request->datasearch."%')";
            // $users = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Người dùng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.users.search',compact('users','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('user.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function moneyUserToStore($id)
    {
        // return $id;
        $func = "sup_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $supplier = User::find($id);
         
        if( $supplier)
        {
             $bankaccounts = \App\Models\Bankaccount::where('status','active')->get();
             $active_menu="user_list";
             
             $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
             <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Ds người dùng</a></li>
             <li class="breadcrumb-item active" aria-current="page"> nhận tiền nhà cung cấp </li>';
             return view('backend.users.usertostore',compact('supplier','breadcrumb','bankaccounts','active_menu'));
             
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function moneySaveUserToStore(Request $request)
    {
        $func1 = "sup_edit";
        $func2 = "cus_edit";
        if(!$this->check_function($func1)  && !$this->check_function($func2))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'numeric|required|gt:0',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $supplier = User::find($data['id']);
        $user = auth()->user();
       
        if( $supplier)
        {
             ///create paid transaction
            
            if($data['paid_amount'] ==0 )
            {
                return back()->with('error','Số tiền trả không hợp lệ!');
            }
            $bankaccount = \App\Models\Bankaccount::find($data['bank_id']);
            if(!$bankaccount  )
            {
                return back()->with('error','Không tìm thấy tài khoản' );
            }
            if (!isset($data['content']))
                $data['content'] = "";

            $bank_doc = \App\Models\BankTransaction::insertBankTrans($user->id,$data['bank_id'],1,$supplier->id,'si',$data['paid_amount']);
            $subtrans = \App\Models\SupTransaction::createSubTransContent($bank_doc->id,'fi',1, $data['paid_amount'], $supplier->id, $data['content']); 
            $bank_doc->doc_id =  $subtrans->id;
            $bank_doc->save();
            //list all wo not paid order by time
           
            $warehouseouts = \App\Models\Warehouseout::where('customer_id',$supplier->id)->where('status','active')
            ->where('is_paid',false)->orderBy('id','ASC')->get();
            $paid_amount = $data['paid_amount'];
            foreach($warehouseouts as $warehouseout)
            {
                if($paid_amount >= ($warehouseout->final_amount - $warehouseout->paid_amount))
                {
                    $paid_amount -= ($warehouseout->final_amount - $warehouseout->paid_amount);
                    $warehouseout->paid_amount = $warehouseout->final_amount;
                    $warehouseout->is_paid = true;
                    $warehouseout->save();
                    
                }
                else
                {
                    $warehouseout->paid_amount+= $paid_amount;
                    $warehouseout->save();
                    $paid_amount = 0;
                }
                if($paid_amount == 0)
                    break;
            }
            ///create log /////////////
            $user = auth()->user();
            $content = 'nhận tiền từ nhà cung cấp/khách hàng' ;
            // \App\Models\Log::insertLog($content,$user->id);
            \App\Models\Log::insertLogNew($content,$subtrans->id,'supwi',$user->id);
            return redirect()->route('user.showsup',$supplier->id)->with('success','Đã nạp tiền nhà cung cấp!');
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
       
    }
    public function moneyUsershow($id)
    {
        $func1 = "sup_edit";
        $func2 = "cus_edit";
        if(!$this->check_function($func1)  && !$this->check_function($func2))
        {
            return redirect()->route('unauthorized');
        }
        //
        $user = User::find($id);
        if($user)
        {
            $active_menu="user_add";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('user.index').'">Người dùng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> xem công nợ </li>';
            $suptrans = \App\Models\SupTransaction::where('supplier_id',$id)
                ->orderBy('id','DESC')
                ->paginate($this->pagesize*2)->withQueryString();;
             
            return view('backend.suptrans.show',compact('breadcrumb','active_menu','user','suptrans'));
        }
    }
    public function moneyStoreToUser($id)
    {
        // return $id;
        $func1 = "sup_edit";
        $func2 = "cus_edit";
        if(!$this->check_function($func1)  && !$this->check_function($func2))
        {
            return redirect()->route('unauthorized');
        }
        $supplier = User::find($id);
         
        if( $supplier)
        {
             $bankaccounts = \App\Models\Bankaccount::where('status','active')->get();
             $active_menu="sup_list";
             
             $breadcrumb = '
             <li class="breadcrumb-item"><a href="#">/</a></li>
             <li class="breadcrumb-item  " aria-current="page"><a href="'.route('supplier.index').'">Ds nhà cung cấp</a></li>
             <li class="breadcrumb-item active" aria-current="page"> chuyển tiền nhà cung cấp </li>';
             return view('backend.users.storetouser',compact('supplier','breadcrumb','bankaccounts','active_menu'));
             
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }

    public function moneySaveStoreToUser(Request $request)
    {
        $func1 = "sup_edit";
        $func2 = "cus_edit";
        if(!$this->check_function($func1)  && !$this->check_function($func2))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'id'=>'numeric|required',
            'paid_amount'=>'numeric|required|gt:0',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $supplier = User::find($data['id']);
        $user = auth()->user();
       
        if( $supplier)
        {
             ///create paid transaction
            
            if($data['paid_amount'] ==0 )
            {
                return back()->with('error','Số tiền trả không hợp lệ!');
            }
            $bankaccount = \App\Models\Bankaccount::find($data['bank_id']);
            if(!$bankaccount || $bankaccount->total < $data['paid_amount'])
            {
                return back()->with('error','Tài khoản không đủ tiền trả!');
            }
            if (!isset($data['content']))
                $data['content'] = "";
            $bank_doc = \App\Models\BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$supplier->id,'si',$data['paid_amount']);
            $subtrans = \App\Models\SupTransaction::createSubTransContent($bank_doc->id,'fi',-1, $data['paid_amount'], $supplier->id,$data['content']); 
            $bank_doc->doc_id =  $subtrans->id;
            $bank_doc->save();
            //list all wi not paid order by time
            $warehouseins = \App\Models\WarehouseIn::where('supplier_id',$supplier->id)->where('status','active')
            ->where('is_paid',false)->orderBy('id','ASC')->get();
            
            $paid_amount = $data['paid_amount'];
            foreach($warehouseins as $warehousein)
            {
                echo $warehousein->final_amount .'-'. $warehousein->paid_amount.': '.($warehousein->final_amount - $warehousein->paid_amount); 
                echo '<br/>$paid_amount: '.($paid_amount); 
                if($paid_amount >= ($warehousein->final_amount - $warehousein->paid_amount))
                {
                    $paid_amount -= ($warehousein->final_amount - $warehousein->paid_amount);
                    $warehousein->paid_amount = $warehousein->final_amount;
                    $warehousein->is_paid = true;
                    $warehousein->save();
                    echo 'whpaid_amount :'.$warehousein->paid_amount;
                    echo '<br/>paid_amount :'.$paid_amount ;
                    
                }
                else
                {
                    $warehousein->paid_amount+= $paid_amount;
                    $warehousein->save();

                    $paid_amount = 0;
                    echo 'whpaid_amount :'.$warehousein->paid_amount;
                    echo '<br/>paid_amount :'.$paid_amount ;
                }
              
                if($paid_amount == 0)
                    break;
            }
           
            ///create log /////////////
            $user = auth()->user();
            $content = 'trả tiền cho nhà cung cấp/khách hàng' ;
            // \App\Models\Log::insertLog($content,$user->id);
            \App\Models\Log::insertLogNew($content,$subtrans->id,'supwi',$user->id);
            return redirect()->route('user.showsup',$supplier->id)->with('success','Đã lưu!');
            
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
}
