<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Bankaccount;
use App\Models\BankTransaction;
use App\Models\FreeTransaction;
class BankController extends Controller
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
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="bank_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Tài khoản </li>';
        $bankaccounts=Bankaccount::orderBy('id','DESC')->paginate($this->pagesize);
        return view('backend.bankaccounts.index',compact('bankaccounts','breadcrumb','active_menu'));
  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $func = "bank_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="bank_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.index').'">bankaccounts</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo tài khoản </li>';
        return view('backend.bankaccounts.create',compact('breadcrumb','active_menu'));
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $func = "bank_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
          // return $request->all();
          $this->validate($request,[
            'title'=>'string|required',
            'banknumber'=>'string|nullable',
            'status'=>'required|in:active,inactive',
            'total'=>'numeric|required',
        ]);
        $data = $request->all();
        // $data ['total'] = 0;
        
        $status = Bankaccount::create($data);
        if($status){
            $user = auth()->user();
            $content = 'tạo tài khoản '.$data['title'] ;
            \App\Models\Log::insertLogNew($content,$status->id,'bcreate',$user->id);
          

            return redirect()->route('bankaccount.index')->with('success','Tạo bankaccount thành công!');
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
        $func = "bank_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $bankaccount = Bankaccount::find($id);
        if($bankaccount)
        {
            $active_menu="bank_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.index').'">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh tài khoản </li>';
            return view('backend.bankaccounts.edit',compact('breadcrumb','bankaccount','active_menu'));
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
        $func = "bank_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $bankaccount = Bankaccount::find($id);
        if($bankaccount)
        {
            $this->validate($request,[
                'title'=>'string|required',
                'banknumber'=>'string|nullable',
                'status'=>'required|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $bankaccount->fill($data)->save();
            if($status){
                // $content = 'edit bankaccount title: '.$data['title'] ;
                $user = auth()->user();
                // \App\Models\Log::insertLog($content,$user->id);
                $content = 'điều chỉnh tài khoản' ;
                \App\Models\Log::insertLogNew($content, $bankaccount->id,'btrans',$user->id);
              
                return redirect()->route('bankaccount.index')->with('success','Cập nhật thành công');
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
        $func = "bank_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $bankaccount = Bankaccount::find($id);
        
        if($bankaccount)
        {
            $status = Bankaccount::deleteBankaccount($id);
            if($status){
                $user = auth()->user();
                 
                $content = 'xóa tài khoản' ;
                \App\Models\Log::insertLogNew($content,$id,'btrans',$user->id);
              
                return redirect()->route('bankaccount.index')->with('success','Xóa thành công!');
            }
            else
            {
                return back()->with('error','Có dữ liệu liên quan tài khoản ngân hàng nên không thể xóa!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function bankaccountStatus(Request $request)
    {
        $func = "bank_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->mode =='true')
        {
            DB::table('bankaccounts')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('bankaccounts')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        $user = auth()->user();
        $content = 'điều chỉnh tài khoản' ;
        \App\Models\Log::insertLogNew($content,$request->id,'btrans',$user->id);
      
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    public function banktransView()
    {
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $data['active_menu']="bt_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách giao dịch tài khoản </li>';
        $data['banktrans']=BankTransaction::orderBy('id','DESC')->paginate($this->pagesize);
        $data['bankaccounts']=Bankaccount::orderBy('id','DESC')->paginate($this->pagesize);
        $data['end_balance'] = 0;
        $data['pre_balance'] = 0;
        $data['select_year'] = 0;
        $data['select_month'] = 0;
        $data['select_day'] = 0;
        $data['bank_id'] = 0;
        return view('backend.bankaccounts.transview', $data);
  
    }
    public function banktransSort(Request $request)
    {
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'bank_id'=>'numeric|required',
            'select_year'=>'numeric|required',
            'select_month'=>'numeric|required',
            'select_day'=>'numeric|required',
        ]);
        $data = $request->all();
        $data['active_menu']="bt_list";
        $sql = " select * from bank_transactions";
        $where = "";
        if($data['bank_id'] != 0)
            $where .= " bank_id = ".$data['bank_id'];
        if($data['select_year'] != 0)
        {
            if($where != "")
            {
                $where .= " and ";
            }
            $where .= " year(created_at) = ".$data['select_year'];
        }
        if($data['select_month'] != 0)
        {
            if($where != "")
            {
                $where .= " and ";
            }
            $where .= " month(created_at) = ".$data['select_month'];
        }
        if($data['select_day'] != 0)
        {
            if($where != "")
            {
                $where .= " and ";
            }
            $where .= " day(created_at) = ".$data['select_day'];
        }
        // year(created_at) as v_year,
        // month(created_at) as v_month,
        // day(created_at) as v_day,
        if($where != "")
            $sql .= " where ".$where;
        $sql2 = "(" .$sql. " order by id asc) as b";
        $sqlpre = $sql. " order by id asc limit 1";
        $sqlend = $sql. " order by id desc limit 1";
        $data['banktrans'] = DB::table('bank_transactions')
        ->select('bank_transactions.*')
        ->join(\DB::raw($sql2),'bank_transactions.id','=','b.id')
        ->paginate($this->pagesize)->withQueryString();
        $data['bankaccounts']=Bankaccount::orderBy('id','DESC')->paginate($this->pagesize);
        
        $data['endbalance'] = \DB::select($sqlend);
        $data['prebalance'] = \DB::select($sqlpre);
        $data['end_balance'] = 0;
        $data['pre_balance'] = 0;
        if(count($data['endbalance']) > 0)
        {
            $data['end_balance'] = $data['endbalance'][0]->pre_balance;
            $data['pre_balance'] = $data['prebalance'][0]->pre_balance + $data['prebalance'][0]->total;
        }
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.viewtrans').'">Ds giao dịch tài khoản</a></li>';
        return view('backend.bankaccounts.transview',$data);
        
    }
    public function bankaccountTransfer($id)
    {
        $func = "bank_trans";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $bankaccount = Bankaccount::find($id);
        if($bankaccount)
        {
            $active_menu="bank_t";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.index').'">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page"> chuyển khoản </li>';
            $banklist = Bankaccount::where('id','<>',$bankaccount->id)
                    ->where('status','active')->orderBy('id','ASC')->get();
            return view('backend.bankaccounts.transfer',compact('breadcrumb','bankaccount','active_menu','banklist'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function banktransShow($id )
    {
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="bank_list";
        $banktrans = Banktransaction::find($id);
        if($banktrans )
        {
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.viewtrans').'">Danh sách giao dịch</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Chi tiết </li>';
            return view('backend.bankaccounts.show',compact('breadcrumb','active_menu','banktrans'));
     
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
        
    }
    public function bankaccountTransferSave(Request $request)
    {
        $func = "bank_trans";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'firstbank_id'=>'numeric|required',
            'secondbank_id'=>'numeric|required',
            'total'=>'numeric|required',
        ]);
        $data = $request->all();

        // return $data;
        $firstbank = Bankaccount::find($data['firstbank_id']);
        $secondbank = bankaccount::find($data['secondbank_id']);
        if($data['total'] > $firstbank->total)
        {
            return back()->with('error','Số tiền chuyển lớn hơn tiền đang có');
        }
        if($firstbank && $secondbank)
        {
            $user = auth()->user();
            //tao phieu xuat tien và phieu nhan tien
            $fts1= FreeTransaction::addFreeTrans($data['total'],$data['firstbank_id'],-1,'transfer',$user->id);
            BankTransaction::insertBankTrans($user->id,$data['firstbank_id'],-1,$fts1->id,'fi',$data['total'],$firstbank->total);
            $fts2= FreeTransaction::addFreeTrans($data['total'],$data['secondbank_id'],1,'transfer',$user->id );
            BankTransaction::insertBankTrans($user->id,$data['secondbank_id'],1,$fts2->id,'fi',$data['total'],$secondbank->total);
            
            
            $content = 'chuyển tiền tài khoản' ;
            \App\Models\Log::insertLogNew($content,$fts1->id,'btrans',$user->id);
            $content = 'nhận tiền tài khoản' ;
            \App\Models\Log::insertLogNew($content,$fts2->id,'btrans',$user->id);
            return redirect()->route('bankaccount.index')->with('success','Chuyển khoản thành công!');
     
        }
    }
}
