<div class="container   ">
    <h3 class="h2 !mb-6 !text-center">Có Thể Bạn Quan Tâm</h3>
    <div class="swiper-container blog grid-view shop !mb-6" data-margin="30" data-dots="true" data-items-xl="4" data-items-md="2" data-items-xs="1">
        <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($randpros as $pro )
                    <div class="swiper-slide project item group">
                        <figure style=" height:250px; overflow:hidden; padding:0px" class="!rounded-[.4rem] !mb-6">
                            <?php
                                $photos = explode( ',', $pro->photo);
                                $cat = \App\Models\Category::find($pro->cat_id);
                            ?>
                            <a href="{{route('front.product.view',$pro->slug)}}">
                                <img src="{{$photos[0]}}" alt="{{$pro->title}}">
                            </a>
                            <a class="item-like opacity-0 absolute !w-[2.2rem] !h-[2.2rem] leading-[2.2rem] z-[1] transition-all duration-[0.3s] ease-in-out title_color shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.02)] text-[1rem] flex items-center justify-center rounded-[100%] right-0 bg-[#ffffff] top-4 group-hover:opacity-100 group-hover:right-4" 
                                href="javascript:void(0)" data-bs-toggle="white-tooltip" title="Add to wishlist"><i data-id="{{ $pro->id}}" class="uil uil-heart before:content-['\eb66']"></i></a>
                            <a href="javascript:void(0)" data-id="{{ $pro->id}}" class="item-cart opacity-0 absolute bottom-[-2rem] w-full h-auto text-white text-center transition-all duration-[0.3s] ease-in-out text-[0.85rem] flex items-center justify-center font-bold m-0 p-[0.8rem] left-0 bg-[rgba(38,43,50,.8)] hover:bg-[rgba(38,43,50,.9)] hover:!text-white group-hover:opacity-100 group-hover:bottom-0"><i class="uil uil-shopping-bag font-normal mt-[-0.05rem] mr-1 before:content-['\ecba']"></i> Thêm giỏ hàng</a>
                            @if ($pro->feature == 1)
                                <span class=" flex items-center justify-center font-bold leading-[1.7] tracking-[-0.01rem] rounded-[100%] !bg-[rgba(209,107,134)] !opacity-100 text-white !w-[2.5rem] !h-[2.5rem] absolute uppercase text-[0.65rem]" style="top: 1rem; left: 1rem;"><span>Sale!</span></span>
                            @endif
                        </figure>
                        <div class="post-header"  style=" height:100px; overflow:hidden; padding:0px">
                            <div class="flex flex-row items-center justify-between mb-2">
                                @if ($cat)
                                    <div class="uppercase tracking-[0.02rem] text-[0.7rem] font-bold text-[#9499a3] !mb-0">{{$cat->title}}</div>
                                @endif
                            </div>
                            <h2 class="post-title h3 text-[1.1rem]"><a href="{{route('front.product.view',$pro->slug)}}" class="title_color"> {{$pro->title}}</a></h2>
                                    
                        </div>
                    <!-- /.post-header -->
                    </div>
                    @endforeach
                   
                </div>
        </div>
    </div>
</div>