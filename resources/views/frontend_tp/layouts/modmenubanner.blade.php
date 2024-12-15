<?php
    $banners = \App\Models\Banner::where('condition','ads')->where('status','active')->get();
    $cats = \App\Models\Category::where('status','active')->where('parent_id',null)->orderBy('title','asc')->get();
?>
 
  
<div class="wrapper !bg-[#ffffff] hidden xl:block ">
    <div class="container py-[0rem] xl:!py-0 lg:!py-0 md:!py-0">
        <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
            <div class="  xl:w-9/12 lg:w-9/12 md:w-0/12 w-full flex-[0_0_auto] xl:px-[10px] lg:px-[5px] px-[5px] max-w-full xl:order-2 lg:order-2">
                <div class="!text-center">
                    <!-- /.row -->
                    <div class="itemgrid grid-view projects-masonry">
                        <div class="flex flex-wrap mx-[-15px] md:mx-[-15px] mt-[-30px] isotope">
                            <?php $i = 0;?>
                            @foreach ($banners as $banner )
                                <?php 
                                    $i++ ;
                                    if($i == 1)
                                    {
                                        $item_xl_w = 8;
                                        $item_lg_w = 12;
                                        $item_md_w = 12;
                                    }
                                    else
                                    {
                                        $item_xl_w = 4;
                                        $item_lg_w = 6;
                                        $item_md_w = 6;
                                    }
                                
                                ?>
                            
                            <div class="project item xl:w-{{$item_xl_w}}/12 lg:w-{{$item_lg_w}}/12 md:w-{{$item_md_w}}/12 w-full flex-[0_0_auto] px-[5px] mt-[15px] max-w-full drinks events">
                                <figure class="overlay overlay-1 rounded group relative">
                                    <a class=" relative block z-[3] cursor-pointer inset-0" 
                                        href="{{isset($banner->url) && $banner->url?$banner->url:$banner->photo}}" data-glightbox data-gallery="shots-group"> 
                                    <img src="{{$banner->photo}}" alt="image">
                                </a>
                                
                                </figure>
                            </div>
                            <!-- /.project -->
                            @endforeach
                        
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.grid -->
                </div>
            </div>
            <aside class="  xl:w-3/12 lg:w-3/12 md:w-0/12 w-full flex-[0_0_auto] xl:px-[5px] lg:px-[5px] px-[5px] max-w-full sidebar mt-0 xl:!mt-0 lg:!mt-0">
                <div class="widget mt-1">
                
                    <div class="widget-title !mb-3 secondarybackgroundcolor  text-white" style="padding:5px"><span>DANH MỤC</span></div>
                    <ul class="pl-0 list-none">
                    @foreach ($cats as $cat )
                    <li class="!mb-1">
                        <a href="{{route('front.product.cat',$cat->slug)}}" class="items-center rounded text-[#60697b]" 
                              aria-expanded="true">
                            {{$cat->title }} 
                        </a>
                    </li>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</div>
       
