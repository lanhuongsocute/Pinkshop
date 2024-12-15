<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
class IndexController extends Controller
{
    //
    public function themeUpdate(Request $request)
    {
        $this->validate($request,[
            'theme_id'=>'numeric|required',
            
        ]);
        $theme_id = $request->theme_id;
        $sql = "update themesettings set selected = 0";
        DB::select($sql);
        $sql = "update themesettings set selected = 1 where id = ".$theme_id;
        DB::select($sql);
        return redirect()->route('home');
    }
    public function savecontact()
    {
        
        return redirect()->route('home')->with('success', 'Gửi thành công.');
    }
    public function  contact()
    {
        
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        
        $data['links']=array();
        $link = new \App\Models\Links();
        $link->title='Liên hệ';
        $link->url='#';
        array_push($data['links'],$link);
        return view($this->front_view.'.profile.contact', $data );
         
    }
    public function home2()
    {
        return view($this->front_view.'.index2' );
         
    }
    public function home()
    {
         
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $data['banners'] = \App\Models\Banner::where('status','active')->where('condition','banner')->get();
        $data['pro_banners'] = \App\Models\Banner::where('status','active')->where('condition','promo')->get();
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0 and products.type = 'normal' order by rand() LIMIT 5";
        $data['rand_pros'] =   DB::select($sql_rand_pro) ;
        $sql_new_pro = "SELECT * from products where status = 'active' and stock >= 0 and products.type = 'normal' order by id desc LIMIT 10";
        $data['new_pros'] =   DB::select($sql_new_pro) ;
        $sql_hit_pro = "SELECT * from products where status = 'active' and stock >= 0 and products.type = 'normal' order by hit desc LIMIT 10";
        $data['hit_pros'] =   DB::select($sql_hit_pro) ;
        $sql_blog_pro = "SELECT * from blogs where status = 'active' and cat_id > 0   order by id desc LIMIT 5";
        $data['blogs'] =   DB::select($sql_blog_pro) ;
        return view($this->front_view.'.index', $data );
    }
    public function viewLogin()
    {
        $data['plink'] =   url()->previous();
        $data['pagetitle']="Đăng nhập";
        $data['links']= array();
        $link = new \App\Models\Links();
        $link->title='Đăng nhập';
        $link->url='#';
        array_push($data['links'],$link);
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
   
        if(auth()->user())
        {
            return view($this->front_view.'.index', $data );
        }
        else
        {
            return view($this->front_view.'.auth.login', $data );
        }
    }
    public function viewRegister()
    {
        $data['pagetitle']="Đăng ký";
        
        $data['links']= array();
        $link = new \App\Models\Links();
        $link->title='Đăng ký';
        $link->url='#';
        array_push($data['links'],$link);
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $data['banners'] = \App\Models\Banner::where('status','active')->where('condition','banner')->get();
      
        if(auth()->user())
        {
            return redirect()->route('home');
        }
        else
        {
            return view($this->front_view.'.auth.register', $data );
        }
    }
    public function login(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
           
        ]);
       
        if(auth()->user())
        {
            return redirect()->route('home');
        }
        $olduser = \App\Models\User::where('phone',$request->email )->orWhere('email',$request->email )->first();
        if($olduser)
        {
            if (auth()->attempt(array('email' => $olduser->email, 'password' => $request->password))) {
                if (auth()->user()) {
                    if (isset($request->plink) && $request->plink!= '' )
                    {
                        return redirect($request->plink);
                    }
                    else
                        return redirect()->route('home');
                }
            } else {
                return redirect()->route('front.login')
                    ->with('error', 'Email hoặc số điện thoại hoặc mật khẩu không đúng..');
            }
        }
        else {
            return redirect()->route('front.login')
                ->with('error', 'Email hoặc số điện thoại hoặc mật khẩu không đúng.');
        }
       
    }
    public function saveUser(Request $request)
    {
        $this->validate($request,[
            'full_name'=>'string|required',
            'description'=>'string|nullable',
            'phone'=>'string|required',
            'email'=>'string|required',
            'password'=>'string|required',
            'address'=>'string|required',
             'ketqua'=>'string|required',
        ]);
        
        // $messages = [
        //     'g-recaptcha-response.required' => 'You must check the reCAPTCHA.',
        //     'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        // ];

        // $validator = Validator::make($request->all(), [
        //     'g-recaptcha-response' => 'required|captcha'
        // ], $messages);
        // if ($validator->fails()) {
        //     return redirect( )->route('home')
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }
        $data = $request->all();
        //check user with phone
        if($data['ketqua'] != 'hai')
            return back()->with('error','Nhập liệu không đúng!');
       
        $olduser =\App\Models\User::where('phone',$data['phone'])->get();
        if(count($olduser) > 0)
            return back()->with('error','Số điện thoại đã tồn tại!');
        $olduser = \App\Models\User::where('email',$data['email'])->get();
            if(count($olduser) > 0)
                return back()->with('error','Email đã tồn tại!');
        $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $data['phone'];
        $data['role'] = 'customer';
        $status = \App\Models\User::c_create($data);
        if(!$status) 
        {
            return back()->with('error','Something went wrong!');
        }    
        $credentials = $request->only('email', 'password');
        \Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('home')
        ->withSuccess('Bạn đã đăng ký thành công và đăng nhập');
    }
    
}
