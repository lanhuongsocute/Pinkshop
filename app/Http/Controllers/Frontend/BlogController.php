<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //
    public function blogSaveRating(Request $request)
    {
        $this->validate($request,[
            'star_rating'=>'number|nullable',
        ]);
        $data = $request->all();
        $user = auth()->user();
        if(!$user)
        {
            return redirect()->route('front.login');
        }
        $messages = [
            'g-recaptcha-response.required' => 'Bạn phải bấm vào reCAPTCHA.',
            'g-recaptcha-response.captcha' => 'Captcha lỗi, xin thử lại sau.',
        ];

        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required|captcha'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->route('home')
                        ->withErrors($validator)
                        ->withInput();
        }

        $sql = "select * from rate_blogs where user_id =".$user->id;
        $rows = \DB::select($sql);
        if(count($rows) > 3)
        {
            return back()->with('error','Đã bình luận, không thể bình luận thêm!');
        }
        $sql = "select count(id) as tong from rate_blogs where blog_id =".$data['blog_id'];
        $rows = \DB::select($sql);
        $tong = $rows[0]->tong;
        $data['user_id'] = $user->id;
        \App\Models\RateBlog::create($data);
        $blog = \App\Models\Blog::find($data['blog_id']);
        $new_rate = ($tong*$blog->rate + $data['rate'])/($tong + 1);
        $blog->rate = $new_rate;
        $blog->save();
        return back()->with('success','Đã lưu!');
    }
    public function blogSaveComment(Request $request)
    {
        $this->validate($request,[
            'name'=>'string|required',
            'email'=>'string|required',
            'content'=>'string|required',
            
        ]);
        $messages = [
            'g-recaptcha-response.required' => 'Bạn phải bấm vào reCAPTCHA.',
            'g-recaptcha-response.captcha' => 'Captcha lỗi, xin thử lại sau.',
        ];

        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required|captcha'
        ], $messages);
        if ($validator->fails()) {
            return redirect('home')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        $data['status'] = 'active';
        if(auth()->user())
            $data['user_id'] = auth()->user()->id;
        
        \App\Models\Comment::create($data);
        return back()->with('success','Đã lưu!');
    }
    public function allCategoryView( )
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        $data['blogcategories'] = \App\Models\BlogCategory::where('status','active')->get();
        $data['pagetitle']=" Tất cả bài viết " ;
       
          
        $data['blogs'] = Blog::where('status','active')->where('cat_id','>','0')->orderBy('id','DESC')->paginate(8);
        $sql_new_blog = "SELECT * from blogs where status = 'active' and cat_id >0 order by id desc LIMIT 5";
        $data['newblogs'] =   DB::select($sql_new_blog) ;
        $sql_pop_blog = "SELECT * from blogs where status = 'active' and cat_id >0 order by hit desc LIMIT 5";
        $data['popblogs'] =   DB::select($sql_pop_blog) ;
        $data['links']= array();
        $link = new \App\Models\Links();
        $link->title='Bài viết';
        $link->url='#';
        array_push($data['links'],$link);
        $data['tags'] = \DB::select("select * from  tags  where status='active' order by rand() LIMIT 16 ");
        return view($this->front_view.'.blog.categories',$data);
        
    }
    public function tagView($slug)
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
   
        if ($slug)
        {
            
            $tag = \App\Models\Tag::where('slug',$slug)->where('status','active')->first();
            if(!$tag)
            {
                  return view($this->front_view.'.404',$data);
            }

            $data['products'] = DB::table('products')
            ->join('tag_products', 'products.id', '=', 'tag_products.product_id')
            ->where('tag_products.tag_id', $tag->id)
            ->where('products.status','active')
            ->paginate(6)->withQueryString(); 
            
            $data['blogs'] = DB::table('blogs')
            ->join('tag_blogs', 'blogs.id', '=', 'tag_blogs.blog_id')
            ->where('tag_blogs.tag_id', $tag->id)
            ->where('blogs.status','active')
            ->paginate(6)->withQueryString(); 
            
            $data['pagetitle']="Tag ".$tag->title;
            
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title='Tag '.$tag->title;
            $link->url='#';
            array_push($data['links'],$link);

            return view($this->front_view.'.blog.tags',$data);
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
    }

    public function categoryView($slug)
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
   
        if ($slug)
        {
            
            $cat = \App\Models\BlogCategory::where('slug',$slug)->where('status','active')->first();
            
            if(!$cat)
            {
                  return view($this->front_view.'.404',$data);
            }
            
            
            $data['pagetitle']="Danh mục ".$cat->title;
           
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title=$cat->title;
            $link->url='#';
            array_push($data['links'],$link);

            // dd($cat);
            $data['blogs'] = Blog::where('cat_id',$cat->id)->where('status','active')->orderBy('id','DESC')->paginate(8);
            $sql_new_blog = "SELECT * from blogs where status = 'active' and cat_id =".$cat->id." order by id desc LIMIT 6";
            $data['newblogs'] =   DB::select($sql_new_blog) ;
            $sql_pop_blog = "SELECT * from blogs where status = 'active' and cat_id =".$cat->id." order by hit desc LIMIT 6";
            $data['popblogs'] =   DB::select($sql_pop_blog) ;
            $data['tags'] = \DB::select("select * from  tags  where status='active' order by rand() LIMIT 16 ");
          
            return view($this->front_view.'.blog.category',$data);
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
    }
    public function chinhsachView($slug)
    {
        if ($slug)
        {
            $data['pagetitle']="Bài viết";
            
          

            $data['blog'] = Blog::where('slug',$slug)->first();
            $data['page_up_title'] = $data['blog']->title;
            $data['detail'] = \App\Models\SettingDetail::find(1);  
            $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title=$data['blog']->title;
            $link->url='#';
            array_push($data['links'],$link);
            if($data['blog'])
            {
                return view($this->front_view.'.blog.chinhsach',$data);
            }
            else
            {
                return view($this->front_view.'.404',$data);
            }
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
      
    }
    public function pageView($slug)
    {
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();

        if ($slug)
        {
            $data['blog'] = Blog::where('slug',$slug)->first();
            $cat = \App\Models\BlogCategory::find($data['blog']->cat_id);
           
            $data['pagetitle']="Tin tức";
          
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title = $cat->title;
            $link->url = route("front.category.view",$cat->slug);
            array_push($data['links'],$link);
            $link = new \App\Models\Links();
            $link->title = $data['blog']->title;
            $link->url='#';
            array_push($data['links'],$link);

            if($data['blog'])
            {
                $data['page_up_title'] = $data['blog']->title;
                $sql_new_blog = "SELECT * from blogs where status = 'active' and cat_id =".$data['blog']->cat_id." order by id desc LIMIT 6";
                $data['newblogs'] =   DB::select($sql_new_blog) ;
                $sql_pop_blog = "SELECT * from blogs where status = 'active' and cat_id =".$data['blog']->cat_id." order by hit desc LIMIT 6";
                $data['popblogs'] =   DB::select($sql_pop_blog) ;
                $sql_pre_blog = "SELECT * from blogs where status = 'active' and cat_id =".$data['blog']->cat_id." and id < ".$data['blog']->id."    LIMIT 1";
                $data['preblog'] =   DB::select($sql_pre_blog) ;
                $sql_next_blog = "SELECT * from blogs where status = 'active' and cat_id =".$data['blog']->cat_id." and id > ".$data['blog']->id."    LIMIT 1";
                $data['nextblog'] =   DB::select($sql_next_blog) ;
                 
                $sql_tag_blog = "select c.* from (select * from tag_blogs where blog_id = ".$data['blog']->id.") as b left join tags as c on b.tag_id = c.id where c.status = 'active'";
                $data['tags'] = DB::select($sql_tag_blog) ;

                $data['keyword'] = "";
                foreach($data['tags'] as $tag)
                {
                    $data['keyword'].= $tag->title . ",";
                }
                $data['description'] = $data['blog']->summary;
                $data['ogimage']=$data['blog']->photo;

                
                $data['blog']->hit =  $data['blog']->hit + 1;
                $data['blog']->save();
                return view($this->front_view.'.blog.blog',$data);
            }
            else
            {
                return view($this->front_view.'.404',$data);
            }
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
      
    }
    public function pageSearch(Request $request)
    {
        // dd($request->searchdata);
        $data['detail'] = \App\Models\SettingDetail::find(1);  
        $data['categories'] = \App\Models\Category::where('status','active')->where('parent_id',null)->get();
        if($request->searchdata)
        {
            // dd($request->searchdata);
            $searchdata =$request->datasearch;
            $sdatas = explode(" ",$searchdata);
            $searchdata = implode("%", $sdatas);

             ///breadcrumb info
            $data['pagetitle']="Kết quả tìm kiếm" ;
            ///
            $data['links']= array();
            $link = new \App\Models\Links();
            $link->title='Kết quả tìm kiếm';
            $link->url='#';
            array_push($data['links'],$link);

            $searchdata = $request->searchdata;  
            $data['blogs'] = DB::table('blogs')->where(function($query_sub)  use( $searchdata) {
                $query_sub->where('title','LIKE','%'.$searchdata.'%')
                ->orWhere('content','LIKE','%'.$searchdata.'%');
            })->where('status','active')->where('cat_id','<>','null')->paginate(20)->withQueryString(); 
            $sql_new_blog = "SELECT * from blogs where status = 'active' and cat_id != 'null' order by id desc LIMIT 6";
            $data['newblogs'] =   DB::select($sql_new_blog) ;
            $sql_pop_blog = "SELECT * from blogs where status = 'active' and cat_id != 'null' order by hit desc LIMIT 6";
            $data['popblogs'] =   DB::select($sql_pop_blog) ;
             
            return view($this->front_view.'.blog.categories',$data);
            
        }
        else
        {
            return view($this->front_view.'.404',$data);
        }
      
    }
}
