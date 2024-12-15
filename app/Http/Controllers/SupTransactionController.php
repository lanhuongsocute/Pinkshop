<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupTransactionController extends Controller
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
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $func = "sup_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $suptrans = \App\Models\SupTransaction::find($id);
        if($suptrans)
        {
            if($suptrans->operation == -1)
            {
                $active_menu="sup_list";
                $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item  " aria-current="page"><a href="'.route('supplier.index').'">Nhà cung cấp</a></li>
                <li class="breadcrumb-item active" aria-current="page"> xem giao dịch nhà cung cung </li>';
                return view('backend.suppliers.viewsuptrans',compact('breadcrumb','active_menu', 'suptrans'));

             }   
            else
            {
                $active_menu="customer_list";
                $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item  " aria-current="page"><a href="'.route('customer.index').'">Khách hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page"> xem giao dịch nhà cung cung </li>';
                return view('backend.customers.viewsuptrans',compact('breadcrumb','active_menu', 'suptrans'));
            }    
        }
        else 
        echo 'ko';
    }
    public function suptransList()
    {
        $func = "sup_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

            $data['active_menu']='ft_list';
            // $data['suptrans'] = \App\Models\SupTransaction::where('doc_type','fi')->where('is_delete',0)->orderBy('id','DESC')->paginate($this->pagesize);
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"> Danh sách giao dịch nhập xuất </li>'
            ;
             $data['operation'] = 0;
            $data['final_balance'] = 0;
            $data['select_year'] = 0;
            $data['select_month'] = 0;
            $data['select_day'] = 0;
            $data['type_id'] = -1;
            
            $query = " (select full_name, id from users ) as b ";
            $query2 = " ( select d.id, e.title as bankname from bank_transactions d left join bankaccounts e on d.bank_id = e.id   ) as c ";
            // dd($query);
            $data['suptrans'] = \DB::table('sup_transactions')
            ->select ('sup_transactions.*' , 'b.full_name','c.bankname' )
            ->join(\DB::raw($query),'sup_transactions.supplier_id','=','b.id')
            ->join(\DB::raw($query2),'sup_transactions.doc_id','=','c.id')
           
            ->where('sup_transactions.doc_type','fi')->where('sup_transactions.is_delete',0)
            ->orderBy('id','desc')
            ->paginate($this->pagesize)->withQueryString();

            return view('backend.suptrans.list',  $data);
    }
    public function suptransSort()
    {
        $func = "sup_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

            $data['active_menu']='ft_list';
            // $data['suptrans'] = \App\Models\SupTransaction::where('doc_type','fi')->where('is_delete',0)->orderBy('id','DESC')->paginate($this->pagesize);
            $data['breadcrumb'] = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"> Danh sách giao dịch nhập xuất </li>'
            ;

            $this->validate($request,[
                'operation'=>'numeric|required',
                'type_id'=>'numeric|required',
                'select_year'=>'numeric|required',
                'select_month'=>'numeric|required',
                'select_day'=>'numeric|required',
            ]);
            $data = $request->all();
            $data['active_menu']="bt_list";
          
            $where = "";
            if($data['operation'] != 0)
                $where .= " operation = ".$data['operation'];
            
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
            
            $sql = " select * from free_transactions";

            if($where != "")
                $sql .= " where ".$where;
            $sql2 = "(" .$sql. " order by id asc) as b";
            $sql3 = " select sum(operation*total) as tong from free_transactions ";
            if($where != "")
                $sql3 .= " where ".$where;
        
                
            $data['operation'] = 0;
            $data['final_balance'] = 0;
            $data['select_year'] = 0;
            $data['select_month'] = 0;
            $data['select_day'] = 0;
            $data['type_id'] = -1;
            
            $query = " (select full_name, id from users ) as b ";
            $query2 = " ( select d.id, e.title as bankname from bank_transactions d left join bankaccounts e on d.bank_id = e.id   ) as c ";
            // dd($query);
            $data['suptrans'] = \DB::table('sup_transactions')
            ->select ('sup_transactions.*' , 'b.full_name','c.bankname' )
            ->join(\DB::raw($query),'sup_transactions.supplier_id','=','b.id')
            ->join(\DB::raw($query2),'sup_transactions.doc_id','=','c.id')
           
            ->where('sup_transactions.doc_type','fi')->where('sup_transactions.is_delete',0)
            ->orderBy('id','desc')
            ->paginate($this->pagesize)->withQueryString();

            return view('backend.suptrans.list',  $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
