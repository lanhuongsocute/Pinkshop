<?php

namespace App\Http\Controllers\Frontend;
use App\Models\User;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    //
    public function __construct( )
    {
        // $this->middleware('auth');
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
            
            $data['product_id'] =  $product['id'];
            $data['user_id'] = $user->id;
            $wish = Wishlist::where('product_id',$data['product_id'])->first();
            if(!$wish) 
            {
                $wish = Wishlist::create($data);
                return response()->json(['msg'=>"Thêm thành công",'status'=>true]);
            }
            else
            {
                return response()->json(['msg'=>"Đã có trong danh sách",'status'=>true]);
            }
        }
        else
        {
            return response()->json(['msg'=>"Bạn phải đăng nhập để thực hiện",'status'=>false]);
        }
        
    }
    public function remove($id)
    {
         
        $user = auth()->user();
        if($user)
        {
            $wish = Wishlist::where('product_id',$id)->first();
            if( $wish) 
            {
                $wish->delete();
                return back()->with('success', "Xóa thành công!");
            }
            else
            {
                return back()->with('error', "Không tìm thấy!");
            }
        }
        else
        {
            return response()->json(['msg'=>"Bạn phải đăng nhập để thực hiện",'status'=>false]);
        }
        
    }
}
