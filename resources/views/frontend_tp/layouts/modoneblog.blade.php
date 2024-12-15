<?php
    $blog = \App\Models\Blog::find(63);
?>
@if (isset($blog))
    <div class="card !bg-[#eff7fa] !rounded-[0.8rem] !mt-2 mb-[1rem] xl:!mb-[1rem] lg:!mb-[1rem] md:!mb-[1rem]">
        <div class="card-body xl:!p-[2.5rem] lg:!p-[2.5rem] md:!p-[2.5rem] xl:!py-12 xl:!px-20 p-[40px]">
            <div class="flex flex-wrap mx-[-15px] xl:mx-0 lg:mx-[-20px] mt-[-10px] items-center">
                <!-- bg -->
                <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] mt-[-50px] items-center">
                    <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[50px] max-w-full">
                        <figure class="!rounded-[.4rem] !shadow-[0_0.25rem_1.75rem_rgba(126,150,194,0.07)]">
                            
                            <img style=" " class="!rounded-[.4rem] object-cover" src="{{$blog->photo}}" 
                                srcset="{{$blog->photo}} 2x" alt="image">
                        </figure>
                    </div>
                    <!--/column -->
                    <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[50px] max-w-full">
                        <h2 class="!mb-3 !leading-[1.35]">{{$blog->title}}</h2>
                        <div style="max-height: 150px; overflow-y: auto;;">
                            @if ($blog->summary)
                                <?php echo $blog->summary ?>
                             @endif
                        </div>
                        <h2> Đăng ký tài khoản ngày để : </h2>
                        <ul class="pl-0 list-none bullet-bg bullet-soft-fuchsia">
                            <li class="relative pl-6 mt-[0.35rem]">
                                <i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>
                                    Nhận thông báo khuyến mãi
                                </li>
                            <li class="relative pl-6 mt-[0.35rem]"><i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>
                                    Mua sắm online
                            </li>
                            <li class="relative pl-6 mt-[0.35rem]"><i class="uil uil-check absolute left-0 w-4 h-4 text-[0.8rem] leading-none tracking-[normal] !text-center flex items-center justify-center bg-[#1be4f1] secondarytextcolor rounded-[100%] top-[0.2rem] before:content-['\e9dd'] before:align-middle before:table-cell"></i>
                                    Tra cứu thông tin bảo hành
                            </li>
                        </ul>
                        
                        <a href="{{route('front.page.view',$blog->slug)}}" class="btn btn-fuchsia text-white secondarybackgroundcolor  hover:text-white hover:secondarybackgroundcolor hover: focus:shadow-[rgba(92,140,229,1)] active:text-white active:secondarybackgroundcolor active: disabled:text-white disabled:secondarybackgroundcolor disabled: !mt-2">Xem thêm</a>
                    </div>
                    <!--/.card-body -->
                </div>
                <!-- bg -->
            <!--/.card -->
            </div>
        <!--/div -->
        </div>
    </div>
              

    
@endif