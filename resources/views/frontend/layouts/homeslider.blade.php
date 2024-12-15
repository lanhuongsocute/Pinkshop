<section class="small-section small-slider pt-res-0">
        <div class="container">
            <div class="slider-animate home-slider">
                @foreach ($banners as $banner)
                    <div>
                        <div class="home">
                            <img src=" {{$banner->photo}}" alt="" class="bg-img blur-up lazyload">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="slider-contain px-padding">
                                            <div>
                                            <?php echo $banner->description ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>