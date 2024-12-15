<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    //
    public function productView($slug)
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
   
       
            
        $product = \App\Models\Product::where('slug',$slug)->where('status','active')->first();
        // $products = \DB::select('select a.*, b.old_price from (select * from products where slug = '.$slug.' and status = "active") as a left join productextends b on a.id = b.product_id  ') ;
         $productData = DB::table('products')
            ->leftjoin('productextends', 'products.id', '=', 'productextends.product_id')
            ->where('products.slug', $slug)  ->where('products.status','active')
            ->select('products.*', 'productextends.*')
            ->first();
            // dd($productData);
            ///breadcrumb info
        if (isset($product) && $product)
        {   
            $cat = \App\Models\Category::where('id',$product->cat_id)->where('status','active')->first();
            $data['pagetitle']=" Sản phẩm " ;
            $data['links']= array();
            
            if($cat)
            {
                $link = new \App\Models\Links();
                $link->title=$cat->title;
                $link->url=route('front.product.cat',$cat->slug);
                array_push($data['links'],$link);
            }
            $link = new \App\Models\Links();
            $link->title=$product->title;
            $link->url='#';
            array_push($data['links'],$link);
            $data['productdata'] = $productData;
            $data['product'] = $product;
            if(!$product)
            {
                  return view($this->front_view.'.404',$data);
            }
            $data['page_up_title'] = $product->title;
            $product->hit = $product->hit  + 1;
            $product->save();
            $sql_tag_blog = "select c.* from (select * from tag_products where product_id = ".$product->id.") as b left join tags as c on b.tag_id = c.id where c.status = 'active'";
            $data['tags'] = DB::select($sql_tag_blog) ;

            $data['keyword'] = "";
            foreach($data['tags'] as $tag)
            {
                $data['keyword'].= $tag->title . ",";
            }
            $data['description'] = $product->summary;
            $photos = explode( ',', $product->photo);
            $data['ogimage']=$photos[0];
            

            $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
            $data['newpros'] =   DB::select($sql_new_blog) ;
          
            $sql_pop_blog = "SELECT * from products where status = 'active' and stock >= 0  order by hit desc LIMIT 6";
            $data['poppros'] =   DB::select($sql_pop_blog) ;
            $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0 and cat_id = ".$product->cat_id." order by rand() LIMIT 6";
            $data['randpros'] =   DB::select($sql_rand_pro) ;
            if($data['randpros'] < 6)
            {
                $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0   order by rand() LIMIT 6";
                $data['randpros'] =   DB::select($sql_rand_pro) ;
            }
           
            return view($this->front_view.'.product.single',$data);
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
    }
  
    public function productSearch(Request $request)
    {
        if($request->searchdata)
        {
            $searchdata =$request->datasearch;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

            $data['detail'] = \App\Models\SettingDetail::find(1);  
            $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
            ///breadcrumb info
            $data['pagetitle']="Kết quả tìm kiếm" ;
            ///
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title='Kết quả tìm kiếm';
            $link->url='#';
            array_push($data['links'],$link);

            $searchdata = $request->searchdata;  
            $data['products'] = DB::table('products')->where(function($query_sub)  use( $searchdata) {
                $query_sub->where('title','LIKE','%'.$searchdata.'%')
                ->orWhere('description','LIKE','%'.$searchdata.'%')
                ->orWhere('summary','LIKE','%'.$searchdata.'%');
            })->where('status','active')->paginate(20)->withQueryString(); 
            
            $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
            $data['newpros'] =   DB::select($sql_new_blog) ;
          
            $sql_pop_blog = "SELECT * from products where status = 'active' and stock >= 0  order by hit desc LIMIT 6";
            $data['poppros'] =   DB::select($sql_pop_blog) ;
            $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0 order by rand() LIMIT 6";
            $data['randpros'] =   DB::select($sql_rand_pro) ;
            return view($this->front_view.'.product.search',$data);
        }
        else
        {
            return back()->with('success','Không có thông tin tìm kiếm!');
        }

    }
    public function productHot( )
    {
         
            $data['detail'] = \App\Models\SettingDetail::find(1);  
            $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
            $data['pagetitle']="Sản phẩm quan tâm" ;

            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title='Sản phẩm quan tâm';
            $link->url='#';
            array_push($data['links'],$link);
             
            $data['products'] = DB::table('products')->where('status','active')
            ->orderBy('hit','DESC')
            ->paginate(20)->withQueryString(); 
            
            $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
            $data['newpros'] =   DB::select($sql_new_blog) ;
          
            $sql_pop_blog = "SELECT * from products where status = 'active' and stock >= 0  order by hit desc LIMIT 6";
            $data['poppros'] =   DB::select($sql_pop_blog) ;
            $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0 order by rand() LIMIT 6";
            $data['randpros'] =   DB::select($sql_rand_pro) ;
            return view($this->front_view.'.product.hot',$data);
        

    }
    public function categoryView($slug)
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
   
        if ($slug)
        {
            
            $cat = \App\Models\Category::where('slug',$slug)->where('status','active')->first();
             ///breadcrumb info
            $data['links']= array();
            if($cat)
            {
                $data['pagetitle']=" Danh mục ".$cat->title ;
                
                $cat_parent = \App\Models\Category::find($cat->parent_id);
                if($cat_parent)
                {
                    $link = new \App\Models\Links();
                    $link->title=$cat_parent->title;
                    $link->url=route('front.product.cat',$cat_parent->slug);
                    array_push($data['links'],$link);
                }
                
                $link = new \App\Models\Links();
                $link->title=$cat->title;
                $link->url='#';
                array_push($data['links'],$link);
            }
             ///
            
           
 
            $data['cat'] = $cat;
            if(!$cat)
            {
                  return view($this->front_view.'.404',$data);
            }
            // dd($cat);
            $data['products'] = Product::where(function($query_sub) use($cat)    
            {
                $query_sub->where('cat_id',$cat->id)
                      ->orwhere('parent_cat_id',$cat->id);
            })->where('stock','>=',0)->where('status','active')->orderBy('id','DESC')->paginate(15);
            // dd( $data['products']);
            $sql_new_blog = "SELECT * from products where status = 'active' and stock >= 0  order by id desc LIMIT 6";
            $data['newpros'] =   DB::select($sql_new_blog) ;
          
            $sql_pop_blog = "SELECT * from products where status = 'active' and stock >= 0  order by hit desc LIMIT 6";
            $data['poppros'] =   DB::select($sql_pop_blog) ;
            $sql_rand_pro = "SELECT * from products where status = 'active' and stock >= 0 order by rand() LIMIT 6";
            $data['randpros'] =   DB::select($sql_rand_pro) ;
            $data['subcats'] =  \App\Models\Category::where('parent_id',$cat->id)->where('status','active')->get();
            return view($this->front_view.'.product.category',$data);
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
    }
}
