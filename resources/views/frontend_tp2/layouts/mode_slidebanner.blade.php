<?php
    $banners = \App\Models\Banner::where('condition','banner')->where('status','active')->get();
   
?>
 
 

    
<div class="wrapper !bg-[#ffffff] hidden xl:block ">
    <div class="container py-[0rem] xl:!py-0 lg:!py-0 md:!py-0">
    <div class="swiper swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
       
    <div class="swiper-container dots-closer !mb-6 relative z-10 swiper-container-1" data-margin="30" data-dots="true"">
        <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                 @foreach ($banners as $banner)
                    <div class="swiper-slide">
                        <img src="{{$banner->photo}}" alt="Slide 1" class="w-full">
                    </div>
                @endforeach
            </div>
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        
    </div>
 
</div>
</div>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,

            // Autoplay parameters
            autoplay: {
                delay: 3000, // Delay between transitions (in milliseconds)
                disableOnInteraction: false, // Continue autoplay after user interactions
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Lazy loading parameters
            lazy: {
                loadPrevNext: true, // Load previous and next images
                loadPrevNextAmount: 1, // Amount of slides to load around
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },
        });
    </script>