<?php
  $blogcats = \DB::select("select * from  blog_categories  where status='active'   ");
  foreach ($blogcats as $blogcat)
  {
      $sql = "select count(id) as tong from blogs where cat_id = ".$blogcat->id;
      $re = \DB::select($sql);
      $blogcat->sobai = $re[0]->tong;
  }
  ?>
<div class="widget mt-[40px]">
        <h4 class="widget-title !mb-3">Danh mục</h4>
        <ul class="pl-0 list-none bullet-primary !text-inherit">
            @foreach ($blogcats as $blogcat )
            <li class="relative pl-[1rem] before:absolute  before:top-[-0.15rem] before:text-[1rem] before:content-['\2022'] before:left-0 before:font-SansSerif">
                <a class="text-inherit nav_color" href="{{route('front.category.view',$blogcat->slug)}}">
                    {{$blogcat->title }} ({{$blogcat->sobai}})
                </a>
            </li>
            @endforeach   
        
        </ul>
</div>