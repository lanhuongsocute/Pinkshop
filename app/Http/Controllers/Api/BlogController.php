<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
class BlogController extends Controller
{
    //

    public function store(Request $request)
    {
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Đã lưu thành công!',
        // ], 200);
        set_time_limit(6000);
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!2',
            ], 200);
        }
        //
        $this->validate($request,[
            'title'=>'string|required',
            'photo'=>'string|nullable',
            'summary'=>'string|nullable',
            'content'=>'string|required',
            'photo'=>'string|nullable',
            'cat_id'=>'numeric|nullable',
            'status'=>'required|in:active,inactive',
        ]);
        $tag_ids = $request->tag_ids;
       
        $data = $request->all();
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
          /// ------end replace --///
         
          $slug = Str::slug($request->input('title'));
          $slug_count = Blog::where('slug',$slug)->count();
          if ( $slug_count > 0)
          {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
          }
          $data['title'] = $helpController->change_title($data['title']);
          $slug = Str::slug( $data['title']);
          $slug_count = Blog::where('slug',$slug)->count();
          if ( $slug_count > 0)
          {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có',
            ], 200);
          }
          
      
           
          $data['content'] = $helpController->removeImageStyle( $data['content'] );
          // ------end replace --///
       
      
        $data['slug'] = $slug;
        if($request->photo == null)
            $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
        else
            $data['photo']= $fileController->blogimageUpload( $data['photo']);
        $data['user_id'] = auth()->user()->id;
       
        $data['summary'] = $helpController->removeatag($data['summary'],'iframe' );
        $data['summary'] =  strip_tags($data['summary']);
        $data['summary'] = $helpController->change_content($data['summary']  );
        $data['content'] = $helpController->read_change_content($data['content']  );
        $data['content'] = $helpController->uploadImageInContent( $data['content'] );
        $blog = Blog::create($data);
        // $tagservice = new \App\Http\Controllers\TagController();
        // $tagservice->store_blog_tag($blog->id,$tag_ids);
        if($blog){
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu thành công!',
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!3',
            ], 200);
        }    
    }
    public function update_42($id,$cat)
    {
        set_time_limit(6000);
        $bblogs =   \App\Models\BotNews::where('url_id',$id)->where('base','=','')->where('isdelete',0)
            ->where('photo','<>','')->orderBy('id','desc')->get();
        
        // \DB::select($sql);
        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();
        $n = 1;
        $size_n = count($bblogs);
        foreach ($bblogs as $bblog)
        {
            $btitle = $bblog->base;
            if ($btitle == null || $btitle == '')
                $btitle = $bblog->title;
            $blogs = \App\Models\Blog::where('title','like','%'.$btitle)->get();
            echo '<br/>'.$n.'/'.$size_n.'<br/>count: '.count($blogs);
            if(count($blogs)==0  )
            {
                $n ++;
                echo '<br/>TITLE: '. $bblog->title ;
                $data['content'] = $bblog->content;
                $data['content'] = $helpController->removeHrefA($data['content'] );
                $data['content'] = $helpController->removeatag($data['content'],'iframe' );
                $data['content'] = $helpController->removeatag( $data['content'],'noscript' );
                $data['content'] = $helpController->remove_all_class($data['content'] );
                $data['content'] = $helpController->changeDataLazySrctoSrc($data['content'] );
                $data['content'] = $helpController->uploadImageInContent( $data['content']);
                $data['content'] = $helpController->removeAllatributeNSS($data['content'] );
                $data['content'] = $helpController->addImagetitle( $data['content'] ,$bblog->title );
                $data['content'] = str_replace('tandoanh.vn','',$data['content']);
                $data['content'] = str_replace('tandoanh','',$data['content']);
                $data['content'] = str_replace('Tân Doanh','',$data['content']);
                $data['content'] = $helpController->removeTagstyle($data['content'],'a','color: #ff0000;' );
                $data['content'] = str_replace('>> Xem thêm các bài viết liên quan:','',$data['content']);
                $data['content'] = str_replace('>> Xem ngay bài viết:','',$data['content']);
                $data['content'] = str_replace('Xem thêm','',$data['content']);
                $data['content'] = $helpController->removeAllatributeATag($data['content'],'figure'  );
                    
                $data['summary'] = $bblog->summary;
                
                // }
                $data['user_id']= auth()->user()->id; 
            //    echo $data['description'];
              
                if($bblog->photo == null)
                    $data['photo'] = asset('backend/assets/dist/images/profile-6.jpg');
                else
                {
                        $photo = $bblog->photo ;
                        if ($photo == "")
                            continue;
                        
                        $uploadedImagePath = $fileController->blogimageUpload( $photo);
                        if($uploadedImagePath!= "")
                            $data['photo'] = $uploadedImagePath ;
                      
                        if ($data['photo'] == '')
                        {
                            continue;
                            echo '<br/>---------------KHONG LOAD DC ANH <br/>';
                        }    
                }
                $data['title'] = $bblog->title;
                $data['title'] = $helpController->change_title($bblog->title);
                $data['cat_id'] = $cat;
                $data['user_id'] = 29;
                $slug = Str::slug($bblog->title);
                $slug_count = \App\Models\Blog::where('slug',$slug)->count();
                if($slug_count > 0)
                {
                    $bblog->base =$data['title'];
                    $bblog->save();
                    continue;
                }
                $data['summary'] = $helpController->removeatag($data['summary'],'iframe' );
                $data['summary'] =  strip_tags($data['summary']);
                $data['summary'] = $helpController->change_content($data['summary']  );
                $data['content'] = $helpController->read_change_content($data['content']  );
               
                $data['slug'] = $slug;
                $blog = \App\Models\Blog::create($data);
                echo '<br/> <br/>TITLE: '.$blog->title;
                $bblog->base =$blog->title; 
                $bblog->save();
                if ($n >= 3)
                    return;
            }
            else
            { 
                // foreach ($blogs as $blog)
                // {
                //     $blog->content = $helpController->removeAllatributeATag($blog->content,'figure'  );
                //     $blog->content = str_replace('>> Xem thêm các bài viết liên quan:','',$blog->content);
                //     $blog->content = str_replace('>> Xem ngay bài viết:','',$blog->content);
                //     $blog->save();
                // }

            }
        }
    }
}
