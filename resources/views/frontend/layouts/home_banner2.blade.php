<section class="container">
    @foreach ($pro_banners as $pb)
        
 
        <div class="full-banner small-banner text-center p-center">
            <img src="{{ $pb->photo}}" alt="" class="bg-img blur-up lazyload">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="banner-contain app-detail">
                            <?php echo $pb->description ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>