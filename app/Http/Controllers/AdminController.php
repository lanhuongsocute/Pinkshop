<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
    }
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string'
        ]);
        $auth = \Auth::user();
        // dd($request->get('current_password'));
            // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password)) 
        {
            return back()->with('error', "Current Password is Invalid");
        }
 
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) 
        {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }
 
        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }
     public function admin()
     {
          // $cmd_functions = \App\Models\CmdFunction::get();
          // echo '[';
          // foreach($cmd_functions as $cmd)
          // {
          //      echo '<br/>';
          //      echo '["alias"=>"'.$cmd->alias.'","title"=>"'.$cmd->title. '","status"=>"active"],';
          // }
          // echo ']';
          // return;
          $func = "admin_view";
          if(!$this->check_function($func))
          {
              return redirect()->route('home');
          }
          $data['breadcrumb'] = '
          <li class="breadcrumb-item"><a href="#">/</a></li>
          <li class="breadcrumb-item active" aria-current="page"> Bảng điều khiển</li>';
          $data['active_menu']="dashboard";
          $month = date('m');
          $year = date('Y');
          $day = date('d');
          $lastmonth = $month - 1;
          $lastyear = $year;
          if($lastmonth <= 0)
          {
                    $lastmonth = 12;
                    $lastyear = $year - 1;
          }
          $sql1 = "select sum(final_amount) as b_out from warehouse_outs_view where status = 'active' and v_month = ".$month." and v_year = ".$year;
          $sql2 = "select sum(final_amount) as b_out from warehouse_outs_view where status = 'active' and v_month = ".$lastmonth." and v_year = ".$lastyear;
          $sql11 = "select sum(final_amount) as b_out from warehouse_outs_view where status = 'returned' and v_month = ".$month." and v_year = ".$year;
          $sql22 = "select sum(final_amount) as b_out from warehouse_outs_view where status = 'returned' and v_month = ".$lastmonth." and v_year = ".$lastyear;
       
          
          $data['sum1'] = \DB::select($sql1)[0]->b_out;
          $data['sum2'] = \DB::select($sql2)[0]->b_out;
          $data['sum11'] = \DB::select($sql11)[0]->b_out;
          $data['sum22'] = \DB::select($sql22)[0]->b_out;
          $data['sum1'] = $data['sum1'] - $data['sum11'];
          $data['sum2'] = $data['sum2'] - $data['sum22'];

          $sql3 = "select sum(stock * price_in) as stock_value from products where stock > 0";
          $data['stock_value'] = \DB::select($sql3)[0]->stock_value;
          $sql4 = "select count(id) as usernumber from users where role ='customer' or role ='supcustomer'";
          $data['usernumber'] = \DB::select($sql4)[0]->usernumber;
          $sql5 = "select sum(total) as total from bankaccounts where status = 'active'";
          $data['cash'] = \DB::select($sql5)[0]->total;
          $sql6 = "select count(final_amount) as orders from warehouse_outs_view where status = 'active' and v_month = ".$month ." and v_year =".$year ." and v_day = ".$day;
          $data['number_order'] = \DB::select($sql6)[0]->orders;
          $sql7 = "select sum(final_amount) as total from warehouse_outs_view where status = 'active' and v_month = ".$month ." and v_year =".$year ." and v_day = ".$day;
          $data['total_order'] = \DB::select($sql7)[0]->total;

         // ---

          $sql6 = "select count(final_amount) as orders from warehouse_outs_view where status = 'returned' and v_month = ".$month ." and v_year =".$year ." and v_day = ".$day;
         $data['number_return'] = \DB::select($sql6)[0]->orders;
          $sql71 = "select sum(final_amount) as total from warehouse_outs_view where status = 'returned' and v_month = ".$month ." and v_year =".$year ." and v_day = ".$day;
          $data['total_return'] = \DB::select($sql71)[0]->total;
          // $data['total_order'] =  $data['total_order'] - $data['total_return'];

         // ---
          $sql8 = "select b.title, b.id, a.tongnhap, a.tongban from (SELECT sum(quantity) as tongnhap, product_id, sum(qty_sold) as tongban FROM `warehouse_in_details` where doc_id != 0 and month(created_at) = month(now()) or month(created_at)+1 = month(now()) group by product_id order by tongban desc limit 10) as a left join products as b on a.product_id = b.id";
          $data['hotproducts'] = \DB::select($sql8);
          $sql9 = "select b.title, b.id, b.stock, a.tongnhap, a.tongban from (SELECT sum(quantity) as tongnhap, product_id, sum(qty_sold) as tongban FROM `warehouse_in_details` where doc_id != 0 and month(created_at) = month(now()) or month(created_at)+1 = month(now()) group by product_id ) as a left join products as b on a.product_id = b.id where b.stock <= 1 order by a.tongban desc limit 10";
          $data['outproducts'] = \DB::select($sql9);
          $sql10 = "select id, full_name,budget, phone from users where budget < 0 order by budget  asc limit 10";
          $data['debtcus'] = \DB::select($sql10);
          $data['logs'] = \App\Models\Log::orderBy('id','desc')->limit(20)->get();
          return view ('backend.index', $data);
     }
   public function in_month_view()
   {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $query = " select a.a_in, b.b_out, b.v_day as time from (select sum(final_amount) as b_out, v_day from warehouse_outs_view where status = 'active' and v_year = ".$year." and v_month = ".$month." group by v_day) b left join (select sum(final_amount) as a_in, v_day from warehouse_ins_view where v_year = ".$year." and v_month = ".$month." group by v_day) a on b.v_day =a.v_day ";
        
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function in_day_view()
   {
        
        $query = "select sum( a.final_amount) as a_in, sum( b.final_amount) as b_out, a.v_hour as time from warehouse_ins_view_today a left join warehouse_outs_view_today b on a.v_hour = b.v_hour group by a.v_hour; ";
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function in_year_view()
   {
        $year = date('Y');
        $query = " select a.a_in,  b.b_out, "
        ." a.v_month from (select sum(final_amount) as a_in, v_month from warehouse_ins_view where status = 'active' and v_year =".$year." group by v_month) a "
        ." left join (select sum(final_amount) as b_out, v_month as time from warehouse_outs_view where status = 'active' and v_year =".$year." group by v_month) b "
        ." on a.v_month =b.v_month   ";
        
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function in_all_view()
   {
        $query = "select sum(a.final_amount) as a_in, sum(b.final_amount) b_out, a.v_year as time from (select * from warehouse_ins_view where status = 'active') as a left join (select * from warehouse_outs_view where status ='active') as b on a.v_year =b.v_year group by a.v_year; ";
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function out_month_view()
   {
        $year = date('Y');
        $month = date('m');
        $query = " select a.a_in,   (b.b_out - COALESCE(d.b_out, 0)) as b_out, "
        ." b.v_day as time from  (select sum(final_amount) as b_out, v_day   from warehouse_outs_view where status = 'active' and v_year =".$year." and v_month =".$month." group by v_day) b  "
        ." left join (select sum(final_amount) as a_in, v_day  from warehouse_ins_view where status = 'active' and v_year =".$year." and v_month =".$month." group by v_day) a "
        ." on b.v_day =a.v_day left join (select COALESCE(SUM(final_amount), 0) AS b_out, v_day   from warehouse_outs_view where status = 'returned' and v_year =".$year." and v_month =".$month." group by v_day) d on b.v_day =d.v_day ";
        
        $data = \DB::select($query);
        
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function out_day_view()
   {
    $query = "select sum( a.final_amount) as a_in, sum( b.final_amount) as b_out, b.v_hour as time from ( select * from warehouse_outs_view_today where status = 'active') as b left join (select * from  warehouse_ins_view_today where status='active') as a on b.v_hour = a.v_hour group by b.v_hour; ";
    $data = \DB::select($query);
    return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function out_year_view()
   {
        
        $year = date('Y');
        $query = " select a.a_in,  b.b_out, "
        ." b.v_month as time from (select sum(final_amount) as b_out, v_month from warehouse_outs_view where status = 'active' and v_year =".$year." group by v_month) b "
        ." left join (select sum(final_amount) as a_in, v_month   from warehouse_ins_view  where status = 'active' and v_year =".$year." group by v_month) a "
        ." on b.v_month =a.v_month   ";
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
   public function out_all_view()
   {
        
        $query = "select sum(a.final_amount) as a_in, sum(b.final_amount) b_out, b.v_year as time from (select * from warehouse_outs_view where status ='active') as b left join (select * from warehouse_ins_view where status='active') as a on a.v_year =b.v_year group by b.v_year; ";
        $data = \DB::select($query);
        return response()->json(['msg'=>$data,'status'=>true]);
   }
}
