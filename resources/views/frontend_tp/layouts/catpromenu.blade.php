<?php
    $cats = \App\Models\Category::where('status','active')->where('parent_id',null)->orderBy('title','asc')->get();
?>
<?php
  
  foreach ($cats as $cat)
  {
      $sql = "select count(id) as tong from products where cat_id = ".$cat->id;
      $re = \DB::select($sql);
      $cat->sobai = $re[0]->tong;
  }
  ?>
<div class="widget mt-[40px]">
        <h4 class="widget-title !mb-3">Danh mục</h4>
        <ul class="pl-0 list-none bullet-primary !text-inherit">
            @foreach ($cats as $cat )
            <li class="relative pl-[1rem] before:absolute  before:top-[-0.15rem] before:text-[1rem] before:content-['\2022'] before:left-0 before:font-SansSerif">
                <a class="text-inherit nav_color" href="{{route('front.product.cat',$cat->slug)}}">
                    {{$cat->title }} ({{$cat->sobai}})
                </a>
            </li>
            @endforeach   
        
        </ul>
</div>