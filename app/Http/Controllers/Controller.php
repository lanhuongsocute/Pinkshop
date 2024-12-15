<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct( )
    {
        $theme = \App\Models\Themesetting::where('selected',1)->first();
        $this->front_view=$theme->title;//"frontend_tp";
       
    }
    public function check_function($func)
    {
        $user= auth()->user();
        if($user->role == 'admin')
            return true;
        $row = \DB::select("select d.value from (select * from (select id as role_id from roles where alias ='".$user->role
        ."') as a join (select id as cfunction_id from cmd_functions where alias = '".$func
        ."') as b) as c left join (select * from role_functions where value = 1) as d on c.role_id = d.role_id and c.cfunction_id = d.cfunction_id");
        if(count($row)> 0 && $row[0]->value)
            return true;
        else
            return false;
    }
    public function checkRole($level)
    {
       
        $roles = ['admin','manager','vendor','supplier','customer','supcustomer'];
        $user = auth()->user();
        // echo 'check role:'.$user->role;
        if($level == 1)
        {
            if($user->role == 'admin' )
            {
               return 1;
            }
            else
                return 0;
        }
        if($level == 2)
        {
            if($user->role == 'admin' ||$user->role == 'manager' )
            {
               return 1;
            }
            else
                return 0;
        }
        if($level == 3)
        {
            if($user->role == 'admin' ||$user->role == 'manager' ||$user->role == 'vendor' )
            {
               return 1;
            }
            else
                return 0;
        }
        if($level == 4)
        {
            if($user->role == 'admin' ||$user->role == 'manager' ||$user->role == 'vendor'||$user->role == 'supplier' ||$user->role == 'supcustomer' )
            {
               return 1;
            }
            else
                return 0;
        }
        if($level == 5)
        {
            if($user->role == 'admin' ||$user->role == 'manager' ||$user->role == 'vendor'||$user->role == 'customer' ||$user->role == 'supcustomer' )
            {
               return 1;
            }
            else
                return 0;
        }
    }
    public function unauthorized()
    {
        $func = "admin_view";
        if(!$this->check_function($func))
        {
            return redirect()->route('home');
        }
        
        $active_menu="dashboard";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>';
         
        
        return view('backend.auth',compact('breadcrumb', 'active_menu' ));

    }
    public function absnumber($number)
    {

        if($number < 0)
            $number = -$number;
        return $number;
    }
}
