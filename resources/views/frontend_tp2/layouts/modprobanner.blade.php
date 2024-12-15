<?php
 
   
 
    $sql_rand_pro = "SELECT * from products where is_sold= 1 and status = 'active' and stock >= 0 order by rand() LIMIT 1";
    $rand_pros=   \DB::select($sql_rand_pro) ;
    $firstp="";
    if (count($rand_pros) > 0)
    {
        $rand_pro = $rand_pros[0];
        $pattern = '/<p>(.*?)<\/p>/i';
        if (preg_match($pattern, $rand_pro->description, $matches)) {
            $firstp =  $matches[1];
        }  
    }
  
   
?>
@if (isset($rand_pro))
    

    <div class="tab-content mt-6 xl:!mt-8 lg:!mt-8">
        <div  class="tab-pane fade show active" id="tab2-1" role="tabpanel">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] mt-[-50px] items-center">
                <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[50px] max-w-full">
                    <figure class="!rounded-[.4rem] !shadow-[0_0.25rem_1.75rem_rgba(126,150,194,0.07)]">
                        <?php
                            $photos = explode( ',', $rand_pro->photo);
                        ?> 
                        <img style="width:610px !important; height:410px !important;" class="!rounded-[.4rem] object-cover" src="{{$photos[0]}}" 
                            srcset="{{$photos[0]}} 2x" alt="image">
                    </figure>
                </div>
                <!--/column -->
                <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[50px] max-w-full">
                <h2 class="!mb-3 !leading-[1.35]">{{$rand_pro->title}}</h2>
                <ul class="pl-0 list-none bullet-bg bullet-soft-fuchsia">
                    <li class="relative pl-6 mt-[0.35rem]"><i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>Đã bán: {{$rand_pro->sold}}</li>
                    <li class="relative pl-6 mt-[0.35rem]"><i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>Đã xem: {{$rand_pro->hit}}</li>
                    <li class="relative pl-6 mt-[0.35rem]"><i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>Giá: {{ number_format( $rand_pro->price,0,'.',',')}} đ</li>
                </ul>
                <div style="height: 150px; overflow-y: auto;;">
                    @if ($rand_pro->summary)
                        <?php echo $rand_pro->summary ?>
                    @else
                        <?php echo substr(  $firstp,0,200);?>
                    @endif
                </div>
                <a href="{{route('front.product.view',$rand_pro->slug)}}" class="btn btn-fuchsia text-white secondarybackgroundcolor  hover:text-white hover:secondarybackgroundcolor hover: focus:shadow-[rgba(92,140,229,1)] active:text-white active:secondarybackgroundcolor active: disabled:text-white disabled:secondarybackgroundcolor disabled: !mt-2">Xem thêm</a>
            </div>
            <!--/column -->
        </div>
        <!--/.row -->
    </div>
        <!--/.tab-pane -->
       
@endif