<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function reportCongnoChitiet($id, Request $request)
     {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        if(isset($request->select_month))
            $data['select_month'] = $request->select_month;
        else
            $data['select_month'] = 0;

        if(isset($request->select_year))
            $data['select_year'] = $request->select_year;
        else
            $data['select_year'] = 0;

        if(isset($request->select_day))
            $data['select_day'] = $request->select_day;
        else
            $data['select_day'] = 0;

      
        
         //
         $user = \App\Models\User::find($id);
         if(!$user)
         {
            return back()->with('error','Không tìm thấy dữ liệu');
         }
         $data['active_menu']="report_list";
         $data['breadcrumb'] = '
         <li class="breadcrumb-item"><a href="#">/</a></li>
         <li class="breadcrumb-item active" aria-current="page"> Báo cáo công nợ </li>
          <li class="breadcrumb-item active" aria-current="page"> Chi tiết công nợ '.$user->full_name.' </li>
         ';
         if($data['select_year']!= 0 && $data['select_month'] != 0 && $data['select_day']!= 0)
         {
             $dateString = sprintf('%04d-%02d-%02d', $data['select_year'], $data['select_month'],  $data['select_day']);
            
                //  $data['sups'] = \DB::select("select * from sup_transactions where supplier_id =".$user->id 
                // .'   and datediff(created_at,"'.$dateString.'")> 0 order by id desc');
                $data['sups'] = \DB::select("select * from sup_transactions where supplier_id =".$user->id 
                .' and is_delete = 0 and datediff(created_at,"'.$dateString.'")> 0 order by id desc');
                
         }
        else
        {
            // $data['sups'] = \App\Models\SupTransaction::where('supplier_id',$user->id)
            //  ->orderBy('id','desc')->get();
            $data['sups'] = \App\Models\SupTransaction::where('supplier_id',$user->id)
            ->where('is_delete',0)->orderBy('id','desc')->get();
        }
         $data['user'] = $user;
         $congno = $user->budget;
        foreach($data['sups'] as $sup)
        {
            $sup->total1 = $congno;
            $congno += (-1) * $sup->amount * $sup->operation;
        }
        //  $data['thuloais'] = \DB::select($sql1);
        //  $data['chiloais'] = \DB::select($sql2);
        //  $data['thungays'] = \DB::select($sql4);
        //  $data['chingays'] = \DB::select($sql3);
        //  $data['tongthuchi'] = \DB::select($sql6)[0]->thuchi;
        //  $data['thuchingay'] = \DB::select($sql7);
        
         
         return view('backend.reports.chitietcongno',$data);
 
     }

    public function reportBenefit(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo doanh thu </li>';
       
        if(isset($request->time))
            $data['time'] = $request->time;
        else
            $data['time'] = 'week';
        $time=$data['time'];
        if(isset($request->select_month))
            $data['select_month'] = $request->select_month;
        else
            $data['select_month'] = 0;

        if(isset($request->select_year))
            $data['select_year'] = $request->select_year;
        else
            $data['select_year'] = 0;

        if(isset($request->select_day))
            $data['select_day'] = $request->select_day;
        else
            $data['select_day'] = 0;
        if($data['select_year'] ==0 && $data['select_month'] == 0 && $data['select_day'] == 0)
        {
            if($time == "today")
            {
                $sql1 = "select sum(benefit) as tongloinhuan from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql2 = "select count(id) as sodon from warehouseouts where status='active' and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql4 = "select sum(final_amount) as tongdoanhthu from warehouseouts where status='active' and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql3 = "select b.title as ngay, a.tongbansp, a.loinhuan from (select sum(benefit) as loinhuan , sum(price * quantity) as tongbansp, product_id from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at) group by product_id) as a left join products b on a.product_id = b.id;";
                $sql5 = "select b.title as ngay,   a.sodon from (select   count(product_id) as sodon, product_id from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at) group by product_id) as a left join products b on a.product_id = b.id;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where type_id <> 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)   ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where type_id <> 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)  group by ngay ";
                $sql8 = "select a.product_id, sum(a.benefit) as tongloinhuan , b.title , sum(a.quantity) as quantity from (select * from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at) ) as a left join products as b on a.product_id = b.id group by a.product_id, b.title";
       
            }
            if($time == "week")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql5="SELECT count(id) as sodon , day(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay;;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW(); ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay ";
                $sql8 = "select a.product_id, sum(a.benefit) as tongloinhuan , b.title , sum(a.quantity) as quantity from (select * from warehouseout_details WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW())  as a left join products as b on a.product_id = b.id group by a.product_id,b.title";
            }
            if($time == "30ngay")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql5="SELECT count(id) as sodon , day(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay;;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW(); ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay ";
                $sql8 = "select a.product_id, sum(a.benefit) as tongloinhuan , b.title , sum(a.quantity) as quantity from (select * from warehouseout_details WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW())  as a left join products as b on a.product_id = b.id group by a.product_id, b.title";
       
            }
            if($time == "hangthang")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                    WHERE wo_id != 0 and year(NOW()) = year(created_at) ";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)   ;";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, month(created_at) as ngay FROM warehouseout_details
                    WHERE  wo_id != 0 and year(NOW()) = year(created_at)  group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)   ;";
                $sql5="SELECT  count(id)  as sodon, month(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)  group by ngay;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions  WHERE type_id <> 0 and year(NOW()) = year(created_at) ";
                $sql7 = "select sum(operation*total) as thuchi , month(created_at) as ngay from free_transactions  WHERE type_id <> 0 and year(NOW()) = year(created_at) group by ngay";
                $sql8 = "select a.product_id, sum(a.benefit) as tongloinhuan , b.title , sum(a.quantity) as quantity from (select * from warehouseout_details  WHERE wo_id <> 0 and year(NOW()) = year(created_at))  as a left join products as b on a.product_id = b.id group by a.product_id, b.title";
       
            }
        }
        else
        {
            $where = "";
            if($data['select_year'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " year(a.created_at) = ".$data['select_year'];
            }
            if($data['select_month'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " month(a.created_at) = ".$data['select_month'];
            }
            if($data['select_day'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " day(a.created_at) = ".$data['select_day'];
            }
           
            $where2 = " where a.wo_id != 0 ";
            if ($where != "")
                $where2 .= " and ".$where;
            if ($where != "")
                $where = " where ".$where;
            $sql1 = "select sum(benefit) as tongloinhuan from warehouseout_details as a  ". $where2;
            $sql2 = "select count(id) as sodon from (select * from warehouseouts where status ='active') as a ". $where;;
            $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                as a ".$where2."  group by ngay;";
            $sql4="SELECT sum(final_amount) as tongdoanhthu FROM (select * from warehouseouts where status ='active') as a
                 ".$where."  ;";
            $sql5="SELECT  count(id)  as sodon, day(created_at) as ngay FROM (select * from warehouseouts where status ='active') as a
                 ".$where." group by ngay;";
            $sql6 = "select sum(operation*total) as thuchi  from (select * from free_transactions  where type_id <>0) as a ".$where;
            $sql7 = "select sum(operation*total) as thuchi, day(created_at) as ngay  from (select * from free_transactions  where type_id <>0)   as a  ".$where." group by ngay";
            $sql8 = "select d.product_id, sum(d.benefit) as tongloinhuan , b.title , sum(d.quantity) as quantity from (select * from warehouseout_details as a " .$where2. ")  as d left join products as b on d.product_id = b.id group by d.product_id, b.title";
       
        }
        
        $data['time'] = $time;
        $data['tongloinhuan'] = \DB::select($sql1)[0]->tongloinhuan;
        $data['sodon'] = \DB::select($sql2)[0]->sodon;
        $data['tongdoanhthu'] = \DB::select($sql4)[0]->tongdoanhthu;
        $data['thuchi'] = \DB::select($sql6)[0]->thuchi;
        $data['reportdetails'] = \DB::select($sql3);
        $data['reportsodons'] = \DB::select($sql5);
        $data['reportthuchis'] = \DB::select($sql7);
        $data['products'] = \DB::select($sql8);
        return view('backend.reports.benefit',$data);

    }
    public function reportBenefitProduct(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo doanh thu theo sản phẩm </li>';
       
        if(isset($request->time))
            $data['time'] = $request->time;
        else
            $data['time'] = 'week';
        $time=$data['time'];
        if(isset($request->select_month))
            $data['select_month'] = $request->select_month;
        else
            $data['select_month'] = 0;

        if(isset($request->select_year))
            $data['select_year'] = $request->select_year;
        else
            $data['select_year'] = 0;

        if(isset($request->select_day))
            $data['select_day'] = $request->select_day;
        else
            $data['select_day'] = 0;
        if($data['select_year'] ==0 && $data['select_month'] == 0 && $data['select_day'] == 0)
        {
            if($time == "today")
            {
                $sql1 = "select sum(benefit) as tongloinhuan from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql2 = "select count(id) as sodon from warehouseouts where status='active' and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql4 = "select sum(final_amount) as tongdoanhthu from warehouseouts where status='active' and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)";
                $sql3 = "select b.title as ngay, a.tongbansp, a.loinhuan from (select sum(benefit) as loinhuan , sum(price * quantity) as tongbansp, product_id from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at) group by product_id) as a left join products b on a.product_id = b.id;";
                $sql5 = "select b.title as ngay,   a.sodon from (select   count(product_id) as sodon, product_id from warehouseout_details where wo_id != 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at) group by product_id) as a left join products b on a.product_id = b.id;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where type_id <> 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)   ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where type_id <> 0 and month(now()) = month(created_at) and day(now()) = day(created_at) and year(now()) = year(created_at)  group by ngay ";
            }
            if($time == "week")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW();";
                $sql5="SELECT count(id) as sodon , day(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay;;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW(); ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND created_at <= NOW() group by ngay ";
            }
            if($time == "30ngay")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                    WHERE wo_id != 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW();";
                $sql5="SELECT count(id) as sodon , day(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay;;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW(); ";
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay  from free_transactions WHERE type_id <> 0 and created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND created_at <= NOW() group by ngay ";
            }
            if($time == "hangthang")
            {
                $sql1="SELECT sum(benefit) as tongloinhuan FROM warehouseout_details
                    WHERE wo_id != 0 and year(NOW()) = year(created_at) ";
                $sql2="SELECT count(id) as sodon FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)   ;";
                $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, month(created_at) as ngay FROM warehouseout_details
                    WHERE  wo_id != 0 and year(NOW()) = year(created_at)  group by ngay;";
                $sql4="SELECT sum(final_amount) as tongdoanhthu FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)   ;";
                $sql5="SELECT  count(id)  as sodon, month(created_at) as ngay FROM warehouseouts
                    WHERE status='active' and year(NOW()) = year(created_at)  group by ngay;";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions  WHERE type_id <> 0 and year(NOW()) = year(created_at) ";
                $sql7 = "select sum(operation*total) as thuchi , month(created_at) as ngay from free_transactions  WHERE type_id <> 0 and year(NOW()) = year(created_at) group by ngay";
            }
        }
        else
        {
            $where = "";
            if($data['select_year'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " year(a.created_at) = ".$data['select_year'];
            }
            if($data['select_month'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " month(a.created_at) = ".$data['select_month'];
            }
            if($data['select_day'] != 0)
            {
                if($where != "")
                {
                    $where .= " and ";
                }
                $where .= " day(a.created_at) = ".$data['select_day'];
            }
           
            $where2 = " where a.wo_id != 0 ";
            if ($where != "")
                $where2 .= " and ".$where;
            if ($where != "")
                $where = " where ".$where;
            $sql1 = "select sum(benefit) as tongloinhuan from warehouseout_details as a  ". $where2;
            $sql2 = "select count(id) as sodon from (select * from warehouseouts where status ='active') as a ". $where;;
            $sql3="SELECT sum(price*quantity) as tongbansp ,sum(benefit)  as loinhuan, day(created_at) as ngay FROM warehouseout_details
                as a ".$where2."  group by ngay;";
            $sql4="SELECT sum(final_amount) as tongdoanhthu FROM (select * from warehouseouts where status ='active') as a
                 ".$where."  ;";
            $sql5="SELECT  count(id)  as sodon, day(created_at) as ngay FROM (select * from warehouseouts where status ='active') as a
                 ".$where." group by ngay;";
            $sql6 = "select sum(operation*total) as thuchi  from (select * from free_transactions  where type_id <>0) as a ".$where;
            $sql7 = "select sum(operation*total) as thuchi, day(created_at) as ngay  from (select * from free_transactions  where type_id <>0)   as a  ".$where." group by ngay";
        }
        
        $data['time'] = $time;
        $data['tongloinhuan'] = \DB::select($sql1)[0]->tongloinhuan;
        $data['sodon'] = \DB::select($sql2)[0]->sodon;
        $data['tongdoanhthu'] = \DB::select($sql4)[0]->tongdoanhthu;
        $data['thuchi'] = \DB::select($sql6)[0]->thuchi;
        $data['reportdetails'] = \DB::select($sql3);
        $data['reportsodons'] = \DB::select($sql5);
        $data['reportthuchis'] = \DB::select($sql7);
        return view('backend.reports.benefit',$data);

    }

    public function reportThuchi(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo thu chi </li>';
       
        if(isset($request->time))
            $data['time'] = $request->time;
        else
            $data['time'] = 'week';
        $time=$data['time'];
        if(isset($request->select_month))
            $data['select_month'] = $request->select_month;
        else
            $data['select_month'] = 0;

        if(isset($request->select_year))
            $data['select_year'] = $request->select_year;
        else
            $data['select_year'] = 0;

        if(isset($request->select_day))
            $data['select_day'] = $request->select_day;
        else
            $data['select_day'] = 0;
        if($data['select_year'] ==0 && $data['select_month'] == 0 && $data['select_day'] == 0)
        {
            if($time == "today")
            {
                $where = "month(now()) = month(created_at) and day(now()) = day(created_at) and year(now())";
                $sql1 = "select a.* ,b.title as typetitle from (select sum(total) as thu, type_id from free_transactions where operation = 1 and  ". $where." group by type_id) as a  join freetrans_types b on a.type_id = b.id ";
                $sql2 = "select a.* ,b.title as typetitle from (select sum(total) as chi, type_id from free_transactions where operation = -1 and ".$where." group by type_id) as a  join freetrans_types b on a.type_id = b.id";
                $sql4 = "select sum(final_amount) as tongthu,day(created_at) as ngay from warehouseouts where status ='active' and ".$where ."  group by ngay ";
                $sql3 = "select sum(final_amount) as tongchi,day(created_at) as ngay from warehouse_ins where status ='active' and ". $where ."  group by ngay ";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where   ".$where;
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where  ".$where."  group by ngay ";
     
            }
            if($time == "week")
            {
                $where ="created_at >= DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) DAY)
                AND created_at < DATE_ADD(NOW(), INTERVAL 7 - WEEKDAY(NOW()) DAY)";
                $sql1 = "select a.* ,b.title as typetitle from (select sum(total) as thu, type_id from free_transactions where operation = 1 and  ". $where." group by type_id) as a  join freetrans_types b on a.type_id = b.id ";
                $sql2 = "select a.* ,b.title as typetitle from (select sum(total) as chi, type_id from free_transactions where operation = -1 and ".$where." group by type_id) as a  join freetrans_types b on a.type_id = b.id";
                $sql4 = "select sum(final_amount) as tongthu,day(created_at) as ngay from warehouseouts where status ='active' and ".$where ."  group by ngay ";
                $sql3 = "select sum(final_amount) as tongchi,day(created_at) as ngay from warehouse_ins where status ='active' and ". $where ."  group by ngay ";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where   ".$where;
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where  ".$where."  group by ngay ";
     
            }
            if($time == "30ngay")
            {
                $where = "created_at >= DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) DAY)
                AND created_at < DATE_ADD(NOW(), INTERVAL 30 - WEEKDAY(NOW()) DAY)";
                $sql1 = "select a.* ,b.title as typetitle from (select sum(total) as thu, type_id from free_transactions where operation = 1 and  ". $where." group by type_id) as a  join freetrans_types b on a.type_id = b.id ";
                $sql2 = "select a.* ,b.title as typetitle from (select sum(total) as chi, type_id from free_transactions where operation = -1 and ".$where." group by type_id) as a  join freetrans_types b on a.type_id = b.id";
                $sql4 = "select sum(final_amount) as tongthu,day(created_at) as ngay from warehouseouts where status ='active' and ".$where ."  group by ngay ";
                $sql3 = "select sum(final_amount) as tongchi,day(created_at) as ngay from warehouse_ins where status ='active' and ". $where ."  group by ngay ";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where   ".$where;
                $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where  ".$where."  group by ngay ";
     
            }
            if($time == "hangthang")
            {
                $where = "   year(NOW()) = year(created_at) ";
                $sql1 = "select a.* ,b.title as typetitle from (select sum(total) as thu, type_id from free_transactions where operation = 1 and  ". $where." group by type_id) as a  join freetrans_types b on a.type_id = b.id ";
                $sql2 = "select a.* ,b.title as typetitle from (select sum(total) as chi, type_id from free_transactions where operation = -1 and ".$where." group by type_id) as a  join freetrans_types b on a.type_id = b.id";
                $sql4 = "select sum(final_amount) as tongthu,month(created_at) as ngay from warehouseouts where status ='active' and ".$where ."  group by ngay ";
                $sql3 = "select sum(final_amount) as tongchi,month(created_at) as ngay from warehouse_ins where  status ='active' and ". $where ."  group by ngay ";
                $sql6 = "select sum(operation*total) as thuchi  from free_transactions where   ".$where;
                $sql7 = "select sum(operation*total) as thuchi , month(created_at) as ngay from free_transactions where  ".$where."  group by ngay ";
     
            }
        }
        else
        {
            $where = "";
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
        
            $sql1 = "select a.* ,b.title as typetitle from (select sum(total) as thu, type_id from free_transactions where operation = 1 and  ". $where." group by type_id) as a  join freetrans_types b on a.type_id = b.id ";
            $sql2 = "select a.* ,b.title as typetitle from (select sum(total) as chi, type_id from free_transactions where operation = -1 and ".$where." group by type_id) as a  join freetrans_types b on a.type_id = b.id";
            $sql4 = "select sum(final_amount) as tongthu,day(created_at) as ngay from warehouseouts where status ='active' and ".$where ."  group by ngay ";
            $sql3 = "select sum(final_amount) as tongchi,day(created_at) as ngay from warehouse_ins where status ='active' and ". $where ."  group by ngay ";
            $sql6 = "select sum(operation*total) as thuchi  from free_transactions where   ".$where;
            $sql7 = "select sum(operation*total) as thuchi , day(created_at) as ngay from free_transactions where  ".$where."  group by ngay ";
        }
        
        $data['time'] = $time;
        $data['thuloais'] = \DB::select($sql1);
        $data['chiloais'] = \DB::select($sql2);
        $data['thungays'] = \DB::select($sql4);
        $data['chingays'] = \DB::select($sql3);
        $data['tongthuchi'] = \DB::select($sql6)[0]->thuchi;
        $data['thuchingay'] = \DB::select($sql7);
        
        return view('backend.reports.thuchi',$data);

    }
    public function reportCongnokhach(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(isset($request->order_name))
            $data['order_name'] = $request->order_name;
        else
            $data['order_name'] = "budget";
        if(isset($request->order_type))
            $data['order_type'] = $request->order_type;
        else
            $data['order_type'] = "asc";
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo công nợ </li>';
        // $sql = "select c.budget, c.full_name, c.id, c.phone, c.address, d.tongloinhuan, d.tongdoanhthu from 
        // (select * from users where role = 'customer' or role='supcustomer') as c 
        // left join ( select sum(a.loinhuan) as tongloinhuan, sum(a.doanhthu) as tongdoanhthu,
        // b.customer_id from (select sum(benefit) as loinhuan,sum(price*quantity) as doanhthu ,
        // wo_id from warehouseout_details where  wo_id != 0 group by wo_id) as a
        // left join warehouseouts b ON a.wo_id = b.id group by customer_id ) as d 
        // on c.id = d.customer_id order by ".$data['order_name']." ".$data['order_type'];
        // $data['debtcus'] = \DB::select($sql);
                                               
        $data['debtcus'] = \App\Models\User::where('role','customer')->orWhere('role','supcustomer')->orderBy('budget','asc')->get();
        
        foreach($data['debtcus'] as $debt)
        {
            $sql = "select sum(final_amount) as tong from warehouseouts where status='active' and customer_id = ".$debt->id;
            $res = \DB::select($sql);
            $debt->tongban = $res[0]->tong;
            
            $sql = "select sum(b.benefit) as tong from (select * from warehouseouts where status='active' and  customer_id = ".$debt->id.") as a , warehouseout_details b where a.id = b.wo_id ";
            $res = \DB::select($sql);
            $debt->loinhuan = $res[0]->tong;
           
        }

        return view('backend.reports.congnokhach',$data);

    }

    public function reportCongnosup(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(isset($request->order_name))
            $data['order_name'] = $request->order_name;
        else
            $data['order_name'] = "budget";
        if(isset($request->order_type))
            $data['order_type'] = $request->order_type;
        else
            $data['order_type'] = "asc";
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo công nợ </li>';
        // $sql = "select f.*,g.id, g.full_name, g.phone, g.address, g.budget from (select sum(d.tongln) as tongln,
        //  sum(d.tongdt) as tongdt, sum(d.tongdaban) as tongdaban, e.supplier_id, sum(e.final_amount) as tong from 
        //  ( select sum(c.loinhuan) as tongln, sum(c.doanhthu) as tongdt,sum(c.daban) as tongdaban, c.doc_id from 
        //  (select a.product_id, a.quantity * (b.price_out - b.price_avg) as loinhuan, 
        //  (a.quantity*a.price) as doanhthu, doc_id, (a.qty_sold*b.price_out) as daban from (select * from warehouse_in_details where doc_id != 0) as a left join 
        //  products b on a.product_id = b.id where a.doc_type='wi') as c group by doc_id ) 
        //  as d left join (select * from warehouse_ins where status ='active' ) 
        //  as e on d.doc_id = e.id group by supplier_id) as f left join users g 
        //  on f.supplier_id = g.id order by  ".$data['order_name']." ".$data['order_type'];
        // $data['debtcus'] = \DB::select($sql);
        $data['debtcus'] = \App\Models\User::where('role','supplier')->orWhere('role','supcustomer')->orderBy('budget','desc')->get();
        $tongtonkho = 0;
        foreach($data['debtcus'] as $debt)
        {
            $sql = "select sum(final_amount) as tong from warehouse_ins where status='active' and supplier_id = ".$debt->id;
            $res = \DB::select($sql);
            $debt->tongnhap = $res[0]->tong;
            $sql = "select sum(b.price*b.qty_sold) as tong from (select * from warehouse_ins where  status='active' and supplier_id = ".$debt->id.") as a , warehouse_in_details b where a.id = b.doc_id ";
            $res = \DB::select($sql);
            $debt->tongban = $res[0]->tong;
            $sql = "select sum(b.benefit) as tong from (select * from warehouse_ins where status='active' and  supplier_id = ".$debt->id.") as a , warehouse_in_details b where a.id = b.doc_id ";
            $res = \DB::select($sql);
            $debt->loinhuan = $res[0]->tong;
            $tongtonkho += $debt->tongnhap - $debt->tongban ;
        }
       
        return view('backend.reports.congnosup',$data);

    }
    public function reportSanpham(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if(isset($request->order_name))
            $data['order_name'] = $request->order_name;
        else
            $data['order_name'] = "loinhuan";
        if(isset($request->order_type))
            $data['order_type'] = $request->order_type;
        else
            $data['order_type'] = "desc";
        //
        $cat_id  =0;
        if(isset($request->cat_id) && $request->cat_id > 0 )
            $cat_id =$request->cat_id;

        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo công nợ </li>';
        if($cat_id == 0)
        {
            $sql = "select d.loinhuan, d.soluongnhap,d.tiennhap,d.soluongxuat,d.tienxuat, (d.soluongnhap - d.soluongxuat) as tonkho , f.title, f.id from (select a.* , b.tienxuat, b.soluongxuat, b.loinhuan from (select product_id, sum(price * quantity) as tiennhap, 
            sum(quantity) as soluongnhap from warehouse_in_details where doc_id != 0 and doc_type='wi' group by product_id) as a 
            left join (select product_id, sum(price * quantity) as tienxuat, sum(quantity) as soluongxuat, 
            sum(benefit) as loinhuan from warehouseout_details where  wo_id != 0 group by product_id) as b on a.product_id = b.product_id) 
            as d left join products f on d.product_id = f.id order by  ".$data['order_name']." ".
            $data['order_type'];
        }
        else
        {
            $sql = "select d.loinhuan, d.soluongnhap,d.tiennhap,d.soluongxuat,d.tienxuat, (d.soluongnhap - d.soluongxuat) as tonkho , f.title, f.id from (select a.* , b.tienxuat, b.soluongxuat, b.loinhuan from (select product_id, sum(price * quantity) as tiennhap, 
            sum(quantity) as soluongnhap from warehouse_in_details where doc_id != 0 and doc_type='wi' group by product_id) as a 
            left join (select product_id, sum(price * quantity) as tienxuat, sum(quantity) as soluongxuat, 
            sum(benefit) as loinhuan from warehouseout_details where  wo_id != 0 group by product_id) as b on a.product_id = b.product_id) 
            as d left join (select * from products where cat_id = ".$cat_id.") as f on d.product_id = f.id order by  ".$data['order_name']." ".
            $data['order_type'];
        }
       
        $data['products'] = \DB::select($sql);
        $data['cat_id'] = $cat_id;
        $data['cats'] = \DB::select('select * from categories where status = "active"');
        return view('backend.reports.sanpham',$data);

    }
    public function reportQuy(Request $request)
    {
        $func = "report_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        
        //
        $data['active_menu']="report_list";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Báo cáo công nợ </li>';
        $sql = "SELECT sum(operation * total) as tong , month(created_at) as ngay from bank_transactions group by ngay;";
        $data['quys'] = \DB::select($sql);
        $data['accounts']= \DB::select("select * from bankaccounts where status = 'active'");
        $tongaccounts = 0;
        $preaccounts = 0;
        $tongtam = 0;
        foreach($data['accounts'] as $account)
        {
            $tongaccounts += $account->total;
        }
        foreach($data['quys'] as $quy)
        {
            $tongtam += $quy->tong;
        }
        $preaccounts =  $tongaccounts - $tongtam;
        foreach($data['quys'] as $quy)
        {
             $quy->tong +=  $preaccounts;
             $preaccounts = $quy->tong;
        }
        return view('backend.reports.quy',$data);
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
    public function MoneyReport(Request $request)
    {
        
        
    }
    public function MoneyReporttam(Request $request)
    {
    }
    
}
