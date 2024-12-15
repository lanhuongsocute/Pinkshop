<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\User;
use App\Models\WarehouseIn;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeliveryController extends Controller
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
        $func = "dlv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="delivery_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nhà vận chuyển </li>';
        $deliverys=User::where('role', 'delivery') 
            ->orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.delivery.index',compact('deliverys','breadcrumb','active_menu'));

    }
    public function deliverySort(Request $request)
    {
        $func = "dlv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'field_name'=>'string|required',
            'type_sort'=>'required|in:DESC,ASC',
        ]);
    
        $active_menu="delivery_list";
        $searchdata =$request->datasearch;
        $deliverys = DB::table('users')
        ->where('role', 'delivery')->orwhere('role','supcustomer')
        ->orderBy($request->field_name, $request->type_sort)
        ->paginate($this->pagesize)->withQueryString();;
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Nhà vận chuyển</a></li>
         ';
        return view('backend.delivery.index',compact('deliverys','breadcrumb','searchdata','active_menu'));
    }
    public function deliveryStatus(Request $request)
    {
        $func = "dlv_edit";
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
    public function deliveryJsearch(Request $request)
    {
        if($request->data  )
        { 
            $searchdata =$request->data;
             $deliverys = DB::table('users')
             ->select ('users.id','users.full_name as title' )
             ->where('full_name','LIKE','%'.$searchdata.'%')
             ->where(function($query)  
             {
                 $query->where('role', 'delivery')
                       ->orWhere('role', 'supcustomer');
             })
             ->where('status','active')
             ->get();
             
             return response()->json(['msg'=>$deliverys,'status'=>true]);
        }
        else
        {
            return response()->json(['msg'=>'','status'=>false]);
        }

    }
    public function deliverySearch(Request $request)
    {
        if($request->datasearch)
        {
            $active_menu="delivery_list";
            $searchdata =$request->datasearch;
            $deliverys = DB::table('users') 
            ->where(function($query) use ( $searchdata )
            {
                $query->where('phone','LIKE','%'.$searchdata.'%')
                      ->orWhere('full_name','LIKE','%'.$searchdata.'%');
            })
            ->where(function($query1)  
            {
                $query1->where('role', 'delivery')
                      ->orWhere('role', 'supcustomer');
            })
            ->paginate($this->pagesize)->withQueryString();
            // $query = "select * from users where role <>'admin' and (full_name like '%" 
            //             .$request->datasearch."%' or phone like '%".$request->datasearch."%')";
            // $users = DB::select($query)->paginate($this->pagesize)->withQueryString();;;
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Nhà vận chuyển</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('backend.delivery.search',compact('deliverys','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('delivery.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "dlv_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="delivery_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Nhà vận chuyển</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo nhà vận chuyển </li>';
        return view('backend.delivery.create',compact('breadcrumb','active_menu'));
  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "dlv_add";
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
        $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['email'] = $data['phone'].'@gmail.com';
        $data['password']=$data['phone'];
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'delivery';
        $status = User::create($data);
        if($status){
            return redirect()->route('delivery.index')->with('success','Tạo nhà vận chuyển thành công!');
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
        $func = "dlv_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $delivery = User::find($id);
        if($delivery)
        {
            $active_menu="delivery_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Nhà vận chuyển</a></li>
            <li class="breadcrumb-item active" aria-current="page"> xem công nợ vận chuyển </li>';
            $suptrans = \App\Models\Suptransaction::where('delivery_id',$id)
                ->orderBy('id','DESC')
                ->paginate($this->pagesize*2)->withQueryString();;
            return view('backend.delivery.show',compact('breadcrumb','active_menu','delivery','suptrans'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $func = "dlv_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $delivery = User::find($id);
        if($delivery)
        {
            $active_menu="delivery_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Nhà vận chuyển</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh nhà vận chuyển </li>';
            
            return view('backend.delivery.edit',compact('breadcrumb','delivery','active_menu' ));
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
        $func = "dlv_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

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
            if($status){
                return redirect()->route('delivery.index')->with('success','Cập nhật thành công');
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
        $func = "dlv_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $user = User::find($id);
        
        if($user)
        {
            $status = User::deleteUser($id);
            if($status){
                return redirect()->route('delivery.index')->with('success','Xóa thành công!');
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
    // public function deliverySavePaid(Request $request)
    // {
    //     $this->validate($request,[
    //         'id'=>'numeric|required',
    //         'paid_amount'=>'numeric|required',
    //         'bank_id'=>'numeric|required',
    //     ]);
    //     $data = $request->all();
    //     $delivery = User::find($data['id']);
    //     $user = auth()->user();
       
    //     if( $delivery)
    //     {
    //          ///create paid transaction
            
    //         if($data['paid_amount'] ==0 )
    //         {
    //             return back()->with('error','Số tiền trả không hợp lệ!');
    //         }
    //         $bankaccount = \App\Models\Bankaccount::find($data['bank_id']);
    //         if(!$bankaccount || $bankaccount->total < $data['paid_amount'])
    //         {
    //             return back()->with('error','Tài khoản không đủ tiền trả!');
    //         }
            
    //         $bank_doc = \App\Models\BankTransaction::insertBankTrans($user->id,$data['bank_id'],-1,$delivery->id,'si',$data['paid_amount']);
    //         $subtrans = \App\Models\SupTransaction::createSubTrans(0,$bank_doc->id,-1, $data['paid_amount'], $delivery->id); 
    //         $bank_doc->doc_id =  $subtrans->id;
    //         $bank_doc->save();
    //         //list all wi not paid order by time
    //         $warehouseins = \App\Models\WarehouseIn::where('delivery_id',$delivery->id)
    //         ->where('is_paid',false)->orderBy('id','ASC')->get();
    //         $paid_amount = $data['paid_amount'];
    //         foreach($warehouseins as $warehousein)
    //         {
    //             if($paid_amount >= ($warehousein->final_amount - $warehousein->paid_amount))
    //             {
    //                 $paid_amount -= ($warehousein->final_amount - $warehousein->paid_amount);
    //                 $warehousein->paid_amount = $warehousein->final_amount;
    //                 $warehousein->is_paid = true;
    //                 $warehousein->save();
                    
    //             }
    //             else
    //             {
    //                 $warehousein->paid_amount+= $paid_amount;
    //                 $warehousein->save();
    //                 $paid_amount = 0;
    //             }
    //             if($paid_amount == 0)
    //                 break;
    //         }
           
    //         ///create log /////////////
    //         $user = auth()->user();
    //         $content = 'paid money for delivery: '.$delivery->full_name.' total: '.$data['paid_amount'];
    //         \App\Models\Log::insertLog($content,$user->id);
            
    //         return redirect()->route('delivery.show',$delivery->id)->with('success','Đã nạp tiền nhà vận chuyển!');
            
    //     }
    //     else
    //     {
    //         return back()->with('error','Không tìm thấy dữ liệu');
    //     }
    // }
    // public function deliveryMakeBalance($id)
    // {
    //     $delivery = User::find($id );
    //     $user = auth()->user();
       
    //     if( $delivery)
    //     {
    //          //list all wi not paid order by time
    //         $warehouseins = \App\Models\WarehouseIn::where('delivery_id',$delivery->id)
    //         ->where('is_paid',false)->orderBy('id','ASC')->get();
    //         $unpaid_amount = 0;
    //         foreach($warehouseins as $warehousein)
    //         {
    //             $unpaid_amount += ($warehousein->final_amount - $warehousein->paid_amount);
    //         }
    //         if($unpaid_amount > $delivery->budget)
    //         {
    //             $paid_amount = $unpaid_amount -  $delivery->budget;
    //             foreach($warehouseins as $warehousein)
    //             {
    //                 if($paid_amount >= ($warehousein->final_amount - $warehousein->paid_amount))
    //                 {
    //                     $paid_amount -= ($warehousein->final_amount - $warehousein->paid_amount);
    //                     $warehousein->paid_amount = $warehousein->final_amount;
    //                     $warehousein->is_paid = true;
    //                     $warehousein->save();
                        
    //                 }
    //                 else
    //                 {
    //                     $warehousein->paid_amount+= $paid_amount;
    //                     $warehousein->save();
    //                     $paid_amount = 0;
    //                 }
    //                 if($paid_amount == 0)
    //                     break;
    //             }
    //             ///create log /////////////
    //             $user = auth()->user();
    //             $content = 'make balance for delivery: '.$delivery->full_name.' total: '.($unpaid_amount -  $delivery->budget) ;
    //             \App\Models\Log::insertLog($content,$user->id);
                
    //         }
    //         return redirect()->route('delivery.show',$delivery->id)->with('success','Đã khấu trừ công nợ nhà vận chuyển!');
    //     }
    //     else
    //     {
    //         return back()->with('error','Không tìm thấy dữ liệu');
    //     }
    // }
    // public function deliveryPaid($id)
    // {
    //     // return $id;
         
    //     $delivery = User::find($id);
         
    //     if( $delivery)
    //     {
    //          $bankaccounts = \App\Models\Bankaccount::where('status','active')->get();
    //          $active_menu="delivery_list";
             
    //          $breadcrumb = '
    //          <li class="breadcrumb-item"><a href="#">/</a></li>
    //          <li class="breadcrumb-item  " aria-current="page"><a href="'.route('delivery.index').'">Ds nhà vận chuyển</a></li>
    //          <li class="breadcrumb-item active" aria-current="page"> nạp tiền nhà vận chuyển </li>';
    //          return view('backend.delivery.paid',compact('delivery','breadcrumb','bankaccounts','active_menu'));
             
    //     }
    //     else
    //     {
    //         return back()->with('error','Không tìm thấy dữ liệu');
    //     }
    // }
}
