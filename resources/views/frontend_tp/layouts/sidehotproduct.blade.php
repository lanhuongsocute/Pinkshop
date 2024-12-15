<div class="widget  ">
    <div class="post-header  ">
        <div class="mb-5 ">
            <h3>
                <a href="#"  class="secondarytextcolor hover" rel="category"> <span  style="color: black !important">Sản phẩm phổ biến</span></a> 
            </h3>
        </div>
    </div>
    <ul class="m-0 p-0 after:content-[''] after:block after:h-0 after:clear-both after:invisible">
        @foreach ($poppros as $pro )
        <?php
             $photos = explode( ',', $pro->photo);
        ?>
        <li class=" clear-both block overflow-hidden mb-5">
            <figure class="!rounded-[.4rem] float-left w-14 !h-[4.5rem]">
                <a href="{{route('front.product.view',$pro->slug)}}" >
                    <img class="!rounded-[.4rem]" src="{{$photos[0]}}" alt="{{$pro->title}}">
                </a>
            </figure>
            <div class="!relative ml-[4.25rem] mb-0">
                <h6 class="!mb-2"> <a class="title_color" href="{{route('front.product.view',$pro->slug)}}">{{$pro->title}}</a> </h6>
                <ul class="text-[0.7rem] text-[#e2626b] m-0 p-0 list-none">
                    <li class="post-date inline-block"> <span>{{number_format($pro->price,0,'.',',')}} đ</span></li>
                </ul>
                <!-- /.post-meta -->
            </div>
        </li>
        @endforeach
    </ul>
                <!-- /.image-list -->
</div>