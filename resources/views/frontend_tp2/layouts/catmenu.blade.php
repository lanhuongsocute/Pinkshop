<?php
    $cats = \App\Models\Category::where('status','active')->where('parent_id',null)->orderBy('title','asc')->get();
?>
<div class="widget mt-1">
    <h4 class="widget-title !mb-3">DANH MỤC</h4>
    <ul class="pl-0 list-none">
    @foreach ($cats as $cat )
    <li class="!mb-1">
        <a href="{{route('front.product.cat',$cat->slug)}}" class="items-center rounded text-[#60697b]" 
            data-bs-toggle="collapse" data-bs-target="#clothing-collapse" aria-expanded="true">
            {{$cat->title }} 
        </a>
    </li>
    @endforeach
</div>