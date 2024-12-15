<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShopingCart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class ShopingCartController extends Controller
{
    //
    
    public function viewCart()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
          ////
          $data['pagetitle']=" Giỏ hàng " ;
          $data['links']=array();
          $link = new \App\Models\Links();
          $link->title='Giỏ hàng';
          $link->url='#';
          array_push($data['links'],$link);
          ///
        $user = auth()->user();
        if( $user)
        {
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $data['products'] =   DB::select($sql ) ;
            $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
            $data['newpros'] =   DB::select($sql_new_blog) ;
         
            return view($this->front_view.'.cart.view', $data );
        }
        else
        {
            return view($this->front_view.'.auth.login', $data );
        }
    }
    public function checkout()
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
          ////
          $data['pagetitle']=" Đặt hàng " ;
         
          $data['links']=array();
          $link = new \App\Models\Links();
          $link->title='Đặt hàng';
          $link->url='#';
          array_push($data['links'],$link);
          ///
        $user = auth()->user();
        if( $user)
        {
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $data['products'] =   DB::select($sql ) ;

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
            $data['addressbooks']  = \App\Models\AddressBook::where('user_id',$user->id)->get() ;
            $data['paymentinfo'] = \App\Models\SettingDetail::find(1)->paymentinfo;
            return view($this->front_view.'.cart.checkout', $data );
        }
        else
        {
            return view($this->front_view.'.auth.login', $data );
        }
    }
    public function order(Request $request)
    {
        $user = auth()->user();
        if( $user)
        {
            $this->validate($request,[
                'ship_id'=> 'numeric|required',
                'invoice_id'=> 'numeric|required',
            ]);

            $data['vendor_id'] = $user->id;
            $data['customer_id'] = $user->id;
            ///save product detail ////////////
            ////average price///////////////////
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $products =   DB::select($sql) ;

            $data['wh_id'] = 0;
            $data['final_amount'] = 0;
            $count_item = 0;
            foreach ( $products  as $pro)
            {
                $data['final_amount'] += $pro->quantity*$pro->price; 
                $count_item += $pro->quantity ;
            }
            $data['discount_amount'] = 0;
            $data['paid_amount'] = 0;
            $data['is_paid'] = 0;
            $data['cost_extra'] = 0 ;
            $wo = \App\Models\Order::create($data);
            // return $wi;
            ////////////////////////////////////
            foreach ( $products  as $pro)
            {
                $product_detail['wo_id'] = $wo->id;
                $product_detail['product_id']= $pro->id ;
                $product_detail['quantity'] = $pro->quantity ;
                $product_detail['price'] =$pro->price ;
                $product = Product::find($pro->id );
                $start_date = date('Y-m-d H:i:s');
                if($product->expired)
                {
                    $strday = '+' . $product->expired*30 .' days';
                    $end_date = date("Y-m-d 23:59:59", strtotime( $strday, strtotime($start_date)));
                    $product_detail['expired_at'] = $end_date;
                }
                \App\Models\OrderDetail::create($product_detail);
                //decrease stock
                 
            }
            ///save front order
            $front_order['order_id'] = $wo->id;
            $front_order['user_id'] = $user->id;
            $front_order['invad_id'] = $request->invoice_id;
            $front_order['delad_id'] = $request->ship_id;
            \App\Models\FrontOrder::create($front_order);

            $sql = "delete from shoping_carts where user_id =".$user->id;
            DB::select($sql ) ;
            return redirect()->route('front.profile.order');
        }
        else
        {
            return redirect()->route('front.login');
        }
    }
    public function getList()
    {
        $user = auth()->user();
        if( $user)
        {
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $products =   DB::select($sql ) ;
            return response()->json([ 'status'=>true,'products'=>$products]);
        }
        else
        {
            return response()->json([ 'status'=>false,'products'=>null]);
        }
    }
    public function add(Request $request)
    {
        $this->validate($request,[
            'product'=> 'required',
        ]);
        $user = auth()->user();
        if($user)
        {
            $product = $request->product;
            // dd($product);
            $data['quantity'] =  $product['quantity'];
            $data['product_id'] =  $product['id'];
            $data['user_id'] = $user->id;
            $wish = ShopingCart::where('product_id',$data['product_id'])
                    ->where('user_id',$user->id)->first();
            if(!$wish) 
            {
                $wish = ShopingCart::create($data);
                $msg = "Thêm thành công";
            }
            else
            {
                $wish->quantity +=  $data['quantity'];
                $wish->save();
                $msg = "Đã thêm số lượng ".$data['quantity'];
            }
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
            .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $products =   DB::select($sql ) ;
            return response()->json(['msg'=>$msg,'status'=>true,'products'=>$products]);
        }
        else
        {
            return response()->json(['msg'=>"Bạn phải đăng nhập để thực hiện",'status'=>false]);
        }
        
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'product_id'=> 'numeric|required',
            'quantity'=> 'numeric|required',
        ]);
        $user = auth()->user();
        if($user)
        {
            $wish = ShopingCart::where('product_id',$request->product_id )
                    ->where('user_id',$user->id)->first();
            if($wish) 
            {
                if($request->quantity > 0)
                {
                    $wish->quantity =  $request->quantity ;
                    $wish->save();
                    $msg = "Đã cập nhật số lượng ".$request->quantity;
                }
                else
                {
                    $wish->delete();
                    $msg = "Đã cập xóa khỏi giỏ hàng";
                }
                
                
            }
            else
            {
                $msg = "Không tìm thấy ";
            }   
            $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
                .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
            $products =   DB::select($sql ) ;
            return response()->json(['msg'=>$msg,'status'=>true,'products'=>$products]);
        }
        else
        {
            return response()->json(['msg'=>"Bạn phải đăng nhập để thực hiện",'status'=>false]);
        }
        
    }
}
