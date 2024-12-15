<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ProfileController extends  Controller
{
    //
    public function __construct( )
    {
        $this->middleware('auth');
        // $this->front_view=env('FRONT_THEME');//"frontend_tp";
        $theme = \App\Models\Themesetting::where('selected',1)->first();
        $this->front_view=$theme->title;//"frontend_tp";
    }
    public function order()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $user  = auth()->user();
        ////
        $data['pagetitle']=" Thông tin tài khoản " ;
        $data['pagebreadcrumb'] ='<nav aria-label="breadcrumb" class="theme-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="'.route('home').'">Trang chủ</a></li>';
        
        $data['pagebreadcrumb'] .='  </ol> </nav>';
        ///
        $data['profile'] = $user ;
        $sql_total_order = "select count(id) as total from warehouseouts where customer_id = ".$user->id." and status = 'active'   ";
        $data['totalorder']  = DB::select($sql_total_order)[0]->total ;
        $sql_total_preorder = "select count(id) as total from orders where customer_id = ".$user->id."  and status='pending'  ";
        $data['totalpendorder']  = DB::select($sql_total_preorder)[0]->total ;
        $sql_total_wishlist = "select count(id) as total from wishlists where user_id = ".$user->id."    ";
        $data['totalwishlist']  = DB::select($sql_total_wishlist)[0]->total ;
        $data['defaut_setting']  = \App\Models\UserSetting::where('user_id',$user->id)->first();
        if($data['defaut_setting'])
        {
            if($data['defaut_setting']->ship_id)
            {
                $data['shipaddress']  = \App\Models\AddressBook::find($data['defaut_setting']->ship_id) ;
            }
            if($data['defaut_setting']->invoice_id)
            {
                $data['invoiceaddress']  = \App\Models\AddressBook::find($data['defaut_setting']->invoice_id) ;
            }
        }
          
        return view($this->front_view.'.profile.view',$data);
        
    }
    //view wishlist
    public function viewWishlist()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
          ////
          $data['pagetitle']=" Sản phẩm yêu thích " ;
          $data['links']=array();
          $link = new \App\Models\Links();
          $link->title='Danh sách sản phẩm yêu thích';
          $link->url='#';
          array_push($data['links'],$link);
          ///
        $user  = auth()->user();
        $data['profile'] = $user ;
        $sql_total_order = "select count(id) as total from warehouseouts where customer_id = ".$user->id." and status = 'active'   ";
        $data['totalorder']  = DB::select($sql_total_order)[0]->total ;
        $sql_total_preorder = "select count(id) as total from orders where customer_id = ".$user->id."  and status='pending'  ";
        $data['totalpendorder']  = DB::select($sql_total_preorder)[0]->total ;
        $sql_total_wishlist = "select count(id) as total from wishlists where user_id = ".$user->id."    ";
        $data['totalwishlist']  = DB::select($sql_total_wishlist)[0]->total ;
        
        $sql  = "select  d.* from (SELECT * from wishlists where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
        $data['products'] =   DB::select($sql ) ;
        return view($this->front_view.'.profile.wishlist',$data);
    }
    //view addres book
    public function addressbook()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
          ////
          $data['pagetitle']=" Address Book " ;
          $data['links']=array();
          $link = new \App\Models\Links();
          $link->title='Danh sách địa chỉ';
          $link->url='#';
          array_push($data['links'],$link);
          ///
        $user  = auth()->user();
        $data['profile'] = $user ;
        $sql_total_order = "select count(id) as total from warehouseouts where customer_id = ".$user->id." and status = 'active'   ";
        $data['totalorder']  = DB::select($sql_total_order)[0]->total ;
        $sql_total_preorder = "select count(id) as total from orders where customer_id = ".$user->id."  and status='pending'  ";
        $data['totalpendorder']  = DB::select($sql_total_preorder)[0]->total ;
        $sql_total_wishlist = "select count(id) as total from wishlists where user_id = ".$user->id."    ";
        $data['totalwishlist']  = DB::select($sql_total_wishlist)[0]->total ;
        $data['defaut_setting']  = \App\Models\UserSetting::where('user_id',$user->id)->first();
        $data['addressbooks']  = \App\Models\AddressBook::where('user_id',$user->id)->get();
        return view($this->front_view.'.profile.addressbook',$data);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'address'=>'string|required',
            'photo'=>'string|nullable',
            'description'=>'string|nullable',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $status = $user->fill($data)->save();
        
        if($status)
        {
            return back()
            ->withSuccess('Bạn đã cập nhật thành công');
        }   
        else
        {
            return back()
            ->withError('Lỗi xãy ra');
        }
    }

    public function viewDasboard()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $user  = auth()->user();
         ////
         $data['pagetitle']=" Thông tin tài khoản " ;
         $data['links']=array();
         $link = new \App\Models\Links();
         $link->title='Thông tin tài khoản';
         $link->url='#';
         array_push($data['links'],$link);
         ///
        $data['profile'] = $user ;
        $sql_total_order = "select count(id) as total from warehouseouts where customer_id = ".$user->id." and status = 'active'   ";
        $data['totalorder']  = DB::select($sql_total_order)[0]->total ;
        $sql_total_preorder = "select count(id) as total from orders where customer_id = ".$user->id."  and status='pending'  ";
        $data['totalpendorder']  = DB::select($sql_total_preorder)[0]->total ;
        $sql_total_wishlist = "select count(id) as total from wishlists where user_id = ".$user->id."    ";
        $data['totalwishlist']  = DB::select($sql_total_wishlist)[0]->total ;
        $data['defaut_setting']  = \App\Models\UserSetting::where('user_id',$user->id)->first();
        if($data['defaut_setting'])
        {
            if($data['defaut_setting']->ship_id)
            {
                $data['shipaddress']  = \App\Models\AddressBook::find($data['defaut_setting']->ship_id) ;
            }
            if($data['defaut_setting']->invoice_id)
            {
                $data['invoiceaddress']  = \App\Models\AddressBook::find($data['defaut_setting']->invoice_id) ;
            }
        }
          
        return view($this->front_view.'.profile.view',$data);
        
    }
    public function createEdit()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $user  = auth()->user();
         ////
         $data['pagetitle']=" Điều chỉnh thông tin tài khoản " ;
         $data['pagebreadcrumb'] ='<nav aria-label="breadcrumb" class="theme-breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="'.route('home').'">Trang chủ</a></li>';
         
         $data['pagebreadcrumb'] .='  </ol> </nav>';
         ///
        $data['profile'] = $user ;
        return view($this->front_view.'.profile.edit',$data);
    }
    public function setDefaultInvoice(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);
        $id = $request->id;
        $user  = auth()->user();
        $default_setting   = \App\Models\UserSetting::where('user_id',$user->id)->first();
        $address = \App\Models\AddressBook::find($id);
        if(!$address)
        {
            return response()->json([ 'status'=>false,'msg'=>'Không tìm thấy dữ liệu']);
        }
        if(!$default_setting)
        {
            $default_setting = new \App\Models\UserSetting();
            $default_setting->user_id = $user->id;
         
        }
        $default_setting->invoice_id = $id;
        $default_setting->save();
        return response()->json([ 'status'=>true,'msg'=>'Cập nhật thành công!']);
    }
    public function deleteAddress($id)
    {
        $user = auth()->user();
        $address = \App\Models\AddressBook::find($id);
        if(!$address)
        {
            return response()->json([ 'status'=>false,'msg'=>'Không tìm thấy dữ liệu']);
        }
        $default_setting   = \App\Models\UserSetting::where('user_id',$user->id)->first();
        if($default_setting->ship_id == $id)
        {
                $default_setting->ship_id= null;
        }
        if($default_setting->invoice_id == $id)
        {
            $default_setting->invoice_id= null;
        }
        $default_setting->save();
        $address->delete();
        return back()->with('success', "Xóa thành công!");
    }
    public function setDefaultShip(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);
        $id = $request->id;
        $user  = auth()->user();
        $default_setting   = \App\Models\UserSetting::where('user_id',$user->id)->first();
        $address = \App\Models\AddressBook::find($id);
        if(!$address)
        {
            return response()->json([ 'status'=>false,'msg'=>'Không tìm thấy dữ liệu']);
        }
        if(!$default_setting)
        {
            $default_setting = new \App\Models\UserSetting();
            $default_setting->user_id = $user->id;
         
        }
        $default_setting->ship_id = $id;
        $default_setting->save();
        return response()->json([ 'status'=>true,'msg'=>'Cập nhật thành công!']);
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
    public function addInvoice(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'phone'=>'string|required',
            'address'=>'string|required',
             
        ]);
        
        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $address = \App\Models\AddressBook::create($data);
        if(!$address) 
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
        $default_setting   = \App\Models\UserSetting::where('user_id',$user->id)->first();
        if(!$default_setting)
        {
            $default_setting = new \App\Models\UserSetting();
            $default_setting->user_id = $user->id;
            $default_setting->save();
        }
        if(isset($data['default']) && $data['default']==1)
        {
            $default_setting->invoice_id = $address->id;
            $default_setting->save();
        }
        return back()
        ->withSuccess('Bạn đã đăng ký thành công và đăng nhập');
    }
    public function addShip(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'phone'=>'string|required',
            'address'=>'string|required',
             
        ]);
        
        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $address = \App\Models\AddressBook::create($data);
        if(!$address) 
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
        $default_setting   = \App\Models\UserSetting::where('user_id',$user->id)->first();
        if(!$default_setting)
        {
            $default_setting = new \App\Models\UserSetting();
            $default_setting->user_id = $user->id;
            $default_setting->save();
        }
        if(isset($data['default']) && $data['default']==1)
        {
            $default_setting->ship_id = $address->id;
            $default_setting->save();
        }
        return back()
        ->withSuccess('Bạn đã thêm thành công');
    }
    
    public function updateName(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'address'=>'string|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $status = $user->fill($data)->save();
        
        if($status)
        {
            return back()
            ->withSuccess('Bạn đã cập nhật thành công');
        }   
        else
        {
            return back()
            ->withError('Lỗi xãy ra');
        }
    }
    public function updateDescription(Request $request)
    {
        $this->validate($request,[
            'description'=>'string|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        $status = $user->fill($data)->save();
        
        if($status)
        {
            return back()
            ->withSuccess('Bạn đã thêm thành công');
        }   
        else
        {
            return back()
            ->withError('Lỗi xãy ra');
        }
    }
    public function updateTax(Request $request)
    {
        $this->validate($request,[
            'taxname'=>'string|required',
            'taxcode'=>'string|required',
            'taxaddress'=>'string|required',
             
        ]);
        $data = $request->all();
        $user = auth()->user();
        $status = $user->fill($data)->save();
        if($status)
        {
            return back()
            ->withSuccess('Bạn đã thêm thành công');
        }   
        else
        {
            return back()
            ->withError('Lỗi xãy ra');
        }
    }

    public function viewOrder()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
          ////
        $data['pagetitle']="Đơn hàng" ;
        $data['links']=array();
        $link = new \App\Models\Links();
        $link->title='Đặt hàng';
        $link->url='#';
        array_push($data['links'],$link);
       
        $user = auth()->user();
        if(! $user)
        {
            return redirect()->route('front.login');
        }
        $sql  = "SELECT * from orders where customer_id = " .$user->id."  ";
        $data['orders'] =   DB::select($sql ) ;
        foreach($data['orders'] as $order)
        {
            $details = \DB::select('select a.*, b.title, b.photo from (select * from order_details where wo_id ='.$order->id.' ) as a left join products b on a.product_id = b.id'); 
            $order->details = $details;
        }

        $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
        // return view($this->front_view.'.product.category',$data);
        return view($this->front_view.'.profile.order', $data );
    }

}
