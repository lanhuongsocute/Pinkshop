<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BBanktransController extends Controller
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
        //
        $func = "bank_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

            $data['active_menu']='bbank';
            // $data['bbanktrans'] = \App\Models\BBanktrans::orderBy('id','DESC')->paginate($this->pagesize);
          
            $query = " (select full_name, id from users ) as b ";
            $query2 = " ( select  id,  title as bankname from   bankaccounts    ) as c ";
            // dd($query);
            $data['bbanktrans'] = \DB::table('b_banktrans')
            ->select ('b_banktrans.*' , 'b.full_name','c.bankname' )
            ->join(\DB::raw($query),'b_banktrans.user_id','=','b.id')
            ->join(\DB::raw($query2),'b_banktrans.bank_id','=','c.id')
            ->orderBy('id','desc')
            ->paginate($this->pagesize)->withQueryString();
           
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bbanktrans.index').'">Danh sách nhập quỹ đầu kỳ</a></li>
             ';
            return view('backend.bbanktrans.index',  $data);
     
         
    }
    
    
    public function create()
    {
        //
        $func = "bank_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="bbank";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bbanktrans.index').'">Danh sách nhập quỹ đầu kỳ</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo mới </li>';
        $banklist = \App\Models\Bankaccount::where('status','active')->orderBy('id','ASC')->get();
         
        return view('backend.bbanktrans.create',compact('breadcrumb','active_menu','banklist' ));
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $func = "bank_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'amount'=>'numeric|required|gt:0',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $data['user_id']= $user->id;
        $banktrans = \App\Models\BankTransaction::where('bank_id',$data['bank_id'])->get();
        if(count($banktrans) > 0)
            return back()->with('error','Tài khoản này đã có giao dịch, không thể nhập đầu kỳ');
        $bbanktrans = \App\Models\BBanktrans::where('bank_id',$data['bank_id'])->get();
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        if(count($bbanktrans)> 0)
        {
            $bank->total = $data['amount'];
            $bank->save();
            $bbanktran = $bbanktrans[0];
            $bbanktran->amount = $data['amount'];
            $bbanktran->user_id = $user->id;
            $bbanktran->save();
        }
        else
        {
            $bank->total = $data['amount'];
            $bank->save();
            $bbanktran = \App\Models\BBanktrans::create($data);
        }
       
          $content = 'lập phiếu nhập quỹ đầu kỳ'  ;
        // \App\Models\Log::insertLog($content,$user->id);
        \App\Models\Log::insertLogNew($content, $bbanktran->id,'bi',$user->id);
        return redirect()->route('bbanktrans.index')->with('success','Tạo thành công!');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
        $active_menu="bbank";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('bbanktrans.index').'">Danh sách nhập quỹ đầu kỳ</a></li>
        <li class="breadcrumb-item active" aria-current="page"> điều chỉnh </li>';
       
        $bbanktran = \App\Models\BBanktrans::find($id);
        $banklist = \App\Models\Bankaccount::where('status','active')->orderBy('id','ASC')->get();
      
        return view('backend.bbanktrans.edit',compact('breadcrumb','active_menu','bbanktran','banklist'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $func = "bank_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $func = "bank_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'amount'=>'numeric|required|gt:0',
            'bank_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $data['user_id']= $user->id;
        $banktrans = \App\Models\BankTransaction::where('bank_id',$data['bank_id'])->get();
        if(count($banktrans) > 0)
            return back()->with('error','Tài khoản này đã có giao dịch, không thể nhập đầu kỳ');
        $bbanktrans = \App\Models\BBanktrans::where('bank_id',$data['bank_id'])->get();
        $bank = \App\Models\Bankaccount::find($data['bank_id']);
        if(count($bbanktrans)> 0)
        {
            $bank->total = $data['amount'];
            $bank->save();
            $bbanktran = $bbanktrans[0];
            $bbanktran->amount = $data['amount'];
            $bbanktran->user_id = $user->id;
            $bbanktran->save();
        }
        return redirect()->route('bbanktrans.index')->with('success','Cập nhập thành công!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $func = "bank_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $bbanktran = \App\Models\BBanktrans::find($id);
        if ($bbanktran)
        {
            $bank = \App\Models\Bankaccount::find($bbanktran->bank_id);
            if(!$bank  )
                return back()->with('error','Tài khoản không tồn tại!');
            $banktrans = \App\Models\BankTransaction::where('bank_id',$bbanktran->bank_id)->get();
            if(count($banktrans) > 0)
                return back()->with('error','Tài khoản này đã có giao dịch, không thể xóa đầu kỳ');
            $bank->total = 0;
            $bank->save();
            $bbanktran->delete();
        
        }
        return redirect()->route('bbanktrans.index')->with('Xóa thành công');
    }
}
