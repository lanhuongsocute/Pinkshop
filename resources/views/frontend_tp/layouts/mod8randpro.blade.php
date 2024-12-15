<?php
    $rand_pros = \DB::select("SELECT * from products where   status = 'active' and `is_sold`=1 order by rand() LIMIT 8");;
    
?>

<section class="wrapper !bg-[#ffffff]">
    
    <div class="container pb-[1.5rem] xl:pb-10 lg:pb-10 md:pb-10 pt-14">
        <div class="post-header mt-[-50px]">
            <div class="">
            <h2><a href="{{route('front.product.hot')}}" class=" hover" rel="category"><i class="uil uil-box"></i> CÓ THỂ BẠN QUAN TÂM</a></h2>
            </div>
            <!-- /.post-category -->
        </div>
       
        <!--/.row -->
        <div class="itemgrid grid-view projects-masonry shop mb-5">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-0px] lg:mx-[-0px] md:mx-[-0px] mt-[-0px] xl:mt-[-0px] lg:mt-[-0px] md:mt-[-0px] isotope">
                @foreach ($rand_pros as $pro)
                <?php
                    $photos = explode( ',', $pro->photo);
                ?>
                    <div class="project item group md:w-6/12 lg:w-3/12 xl:w-3/12 w-full flex-[0_0_auto] xl:px-[10px] lg:px-[10px] md:px-[10px] px-[10px] mt-[10px] xl:mt-[10px] lg:mt-[10px] md:mt-[10px] max-w-full">
                        <figure style=" height:250px;  overflow:hidden; padding:0px" class=" !rounded-[.4rem] !mb-6">
                            <a href="{{route('front.product.view',$pro->slug)}}" title="{{$pro->title}}" alt ="{{$pro->title}}"
                                    class="title_color">
                                <img src="{{$photos[0]}}"   alt="{{$pro->title}}">
                            </a>
                            <a class="item-like opacity-0 absolute !w-[2.2rem] !h-[2.2rem] leading-[2.2rem] z-[1] transition-all duration-[0.3s] ease-in-out title_color shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.02)] text-[1rem] flex items-center justify-center rounded-[100%] right-0 bg-[#ffffff] top-4 group-hover:opacity-100 group-hover:right-4" 
                                href="javascript:void(0)" data-bs-toggle="white-tooltip" title="Thêm vào yêu thích">
                                <i data-id="{{ $pro->id}}" class="uil uil-heart before:content-['\eb66']"></i>
                            </a>
                           
                            <a href="javascript:void(0)" data-id="{{ $pro->id}}"  class="item-cart opacity-0 absolute bottom-[-2rem] w-full h-auto text-white text-center transition-all duration-[0.3s] ease-in-out text-[0.85rem] flex items-center justify-center font-bold m-0 p-[0.8rem] left-0 bg-[rgba(38,43,50,.8)] hover:bg-[rgba(38,43,50,.9)] hover:!text-white group-hover:opacity-100 group-hover:bottom-0">
                                <i  class="uil uil-shopping-bag font-normal mt-[-0.05rem] mr-1 before:content-['\ecba']"></i>
                                Thêm vào giỏ hàng
                            </a>
                            @if ($pro->feature == 1)
                                <span class=" flex items-center justify-center font-bold leading-[1.7] tracking-[-0.01rem] rounded-[100%] !bg-[rgba(209,107,134)] !opacity-100 text-white !w-[2.5rem] !h-[2.5rem] absolute uppercase text-[0.65rem]" style="top: 1rem; left: 1rem;">
                                    <span>Sale!</span>
                                </span>
                            @endif
                            
                        </figure>
                        <div  class="post-header">
                            
                            <h3 style="height:60px; overflow:hidden" class="   text-[1.1rem]"><a href="{{route('front.product.view',$pro->slug)}}" 
                                class="title_color">{{$pro->title}}</a>
                            </h3>
                            <p class="price !m-0">  <ins class="no-underline text-[#e2626b]">
                                <span class="amount">{{number_format($pro->price,0,'.',',')}} đ</span></ins>
                            </p>
                        </div>
                    </div>
                        <!-- /.item -->
                @endforeach
            </div>
        </div>
        <!-- /.row -->
    </div>
      <!-- /.container -->
    </section>