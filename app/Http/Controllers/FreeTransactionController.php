<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreeTransaction;
class FreeTransactionController extends Controller
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
        $func = "ftrans_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

            $data['active_menu']='ft_list';
            $data['freetrans'] = FreeTransaction::orderBy('id','DESC')->paginate($this->pagesize);
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetransaction.index').'">Danh sách thu chi</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Chi tiết </li>';
            $data['types'] = \App\Models\FreetransType::where('status','active')->orderBy('id','ASC')->get();
            $data['operation'] = 0;
            $data['final_balance'] = 0;
            $data['select_year'] = 0;
            $data['select_month'] = 0;
            $data['select_day'] = 0;
            $data['type_id'] = -1;
            $sum_final = \DB::select("select sum(operation*total) as tong from free_transactions");
            if(count($sum_final) > 0)
            {
                $data['final_balance'] =$sum_final[0]->tong;
               
            }
            
            return view('backend.freetransactions.index',  $data);
     
         
    }
    public function freetransSort(Request $request)
    {
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'operation'=>'numeric|required',
            'type_id'=>'numeric|required',
            'select_year'=>'numeric|required',
            'select_month'=>'numeric|required',
            'select_day'=>'numeric|required',
        ]);
        $data = $request->all();
        $data['active_menu']="bt_list";
        $sql = " select * from free_transactions";
        $where = "";
        if($data['operation'] != 0)
            $where .= " operation = ".$data['operation'];
        if($data['type_id'] != -1)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " type_id = ".$data['type_id'];
            }
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
        $sql3 = " select sum(operation*total) as tong from free_transactions ";
        if($where != "")
            $sql3 .= " where ".$where;
        
        $data['freetrans'] = \DB::table('free_transactions')
        ->select('free_transactions.*')
        ->join(\DB::raw($sql2),'free_transactions.id','=','b.id')
        ->paginate(20)->withQueryString();
        $data['types'] = \App\Models\FreetransType::where('status','active')->orderBy('id','ASC')->get();
           
        $data['tong'] = \DB::select($sql3);
      
        $data['final_balance'] = 0;
         
        if(count($data['tong']) > 0)
        {
            $data['final_balance'] =$data['tong'][0]->tong;
           
        }
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bankaccount.viewtrans').'">Ds giao dịch tài khoản</a></li>';
        return view('backend.freetransactions.index',$data);
        
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $func = "ftrans_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="ft_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetransaction.index').'">Danh sách thu chi</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo thu chi </li>';
        $banklist = \App\Models\Bankaccount::where('status','active')->orderBy('id','ASC')->get();
        $typelist = \App\Models\FreetransType::where('status','active')->orderBy('id','ASC')->get();
        
        
        return view('backend.freetransactions.create',compact('breadcrumb','active_menu','banklist','typelist'));
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "ftrans_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'content'=>'string|required',
            'total'=>'numeric|required|gt:0',
            'bank_id'=>'numeric|required',
            'type_id'=>'numeric|required',
            'operation'=>'required|in:1,-1',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $data['user_id']= $user->id;
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        if($bank->total + $data['operation']*$data['total']< 0)
            return back()->with('error','Không đủ tiền trong tài khoản để lập phiếu!');
        $fts= FreeTransaction::addFreeTrans_d($data );
        \App\Models\BankTransaction::insertBankTrans($user->id,$data['bank_id'],$data['operation'],$fts->id,'fi',$data['total']);
        $content = 'phiếu thu chi '  ;
        // \App\Models\Log::insertLog($content,$user->id);
        \App\Models\Log::insertLogNew($content,$fts->id,'fi',$user->id);
        return redirect()->route('freetransaction.index')->with('success','Tạo thu chi thành công!');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $func = "ftrans_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ft_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetransaction.index').'">Danh sách thu chi</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo thu chi </li>';
        $freetrans = \App\Models\FreeTransaction::find($id);
        
        return view('backend.freetransactions.show',compact('breadcrumb','active_menu','freetrans'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $func = "onlyadmin";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="ft_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('freetransaction.index').'">Danh sách thu chi</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo thu chi </li>';
        $freetrans = \App\Models\FreeTransaction::find($id);
        $typelist = \App\Models\FreetransType::where('status','active')->orderBy('id','ASC')->get();
      
        return view('backend.freetransactions.edit',compact('breadcrumb','active_menu','freetrans','typelist'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "onlyadmin";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'content'=>'string|required',
            'type_id'=>'numeric|required',
             
        ]);
        $data = $request->all();
        $fts = FreeTransaction::find($id);
        if(!$fts)
        {
            return back()->with('error','không tìm thấy dữ liệu');
        }
        
        $fts->fill($data)->save();
        return redirect()->route('freetransaction.index')->with('success','Tạo thu chi thành công!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "onlyadmin";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'content'=>'string|required',
            'type_id'=>'numeric|required',
             
        ]);
        $data = $request->all();
        $fts = FreeTransaction::find($id);
        if(!$fts)
        {
            return back()->with('error','không tìm thấy dữ liệu');
        }
        
        $fts->fill($data)->save();
        return redirect()->route('freetransaction.index')->with('success','Tạo thu chi thành công!');
        
    }
}
