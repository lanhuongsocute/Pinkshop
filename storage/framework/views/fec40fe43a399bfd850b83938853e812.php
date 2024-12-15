
<?php $__env->startSection('css'); ?>
<style>
    .theme-card{
        margin-bottom:30px;
    }
</style> 
     
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
      <!-- section start -->
        <?php
            $photos = explode( ',', $product->photo);
            $word = 0;
            if ($productdata->price < $productdata->old_price )
              $word = round(($productdata->old_price - $productdata->price)*100 /$productdata->old_price);
        ?>
      <section class="section-b-space">
        <div class="collection-wrapper">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-9 col-sm-12">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter"
                                                aria-hidden="true"></i> Sidebar</span></div>
                                </div>
                            </div>
                            
                          <!-- product -->
                            <div class="row">
                                  <!--photo-->
                                <div class="col-lg-6">
                                    <div class="product-slick">
                                        <?php if(count($photos)== 0): ?>
                                        <div><img src="<?php echo e(asset('frontend/assets/images/pro3/1.jpg')); ?>" alt=""
                                                class="img-fluid blur-up lazyload image_zoom_cls-0"></div>
                                        <?php else: ?>
                                            <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div><img src="<?php echo e($photo); ?>" alt="<?php echo e($product->title); ?>"
                                                class="img-fluid blur-up lazyload image_zoom_cls-1"></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 p-0">
                                            <div class="slider-nav">
                                                <?php if(count($photos)== 0): ?>
                                                    <div><img src="<?php echo e(asset('frontend/assets/images/pro3/1.jpg')); ?>" alt=""
                                                            class="img-fluid blur-up lazyload"></div>
                                                <?php else: ?>
                                                    <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div  ><img src="<?php echo e($photo); ?>" alt="<?php echo e($product->title); ?>"
                                                            class="img-fluid blur-up lazyload"></div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  <!--photo-->
                                <div class="col-lg-6 rtl-text">
                                    <div class="product-right">
                                        <div class="product-count">
                                            <ul>
                                                <li>
                                                    <img src="<?php echo e(asset('frontend/assets/images/fire.gif')); ?>" class="img-fluid" alt="image">
                                                    <span class="lang">đã bán</span>
                                                    <span class="p-counter"><?php echo e($product->sold); ?></span>
                                                    
                                                </li>
                                                <li>
                                                    <img src="<?php echo e(asset('frontend/assets/images/person.gif')); ?>" class="img-fluid user_img"
                                                        alt="image">
                                                   
                                                    <span class="lang">đã xem</span>
                                                    <span class="p-counter"><?php echo e($product->hit); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <h2><?php echo e($product->title); ?></h2>
                                       
                                        <div class="label-section">
                                            
                                        </div>
                                        <h3 class="price-detail"> <?php echo e(number_format($product->price,0,".",",")); ?> <del> <?php echo e($productdata->old_price?number_format($productdata->old_price,0,'.',','):''); ?></del><span><?php echo e($word>0?"giảm ".$word.'%':''); ?> </span></h3>
                                        
                                        <div id="selectSize"
                                            class="addeffect-section product-description border-product">
                                            <h6 class="product-title">Số lượng</h6>
                                            <div class="qty-box">
                                                <div class="input-group"><span class="input-group-prepend"><button
                                                            type="button" class="btn quantity-left-minus"
                                                            data-type="minus" data-field=""><i
                                                                class="ti-angle-left"></i></button> </span>
                                                    <input type="text" id ="quantity" name="quantity" class="form-control input-number"
                                                        value="1"> <span class="input-group-prepend"><button
                                                            type="button" class="btn quantity-right-plus"
                                                            data-type="plus" data-field=""><i
                                                                class="ti-angle-right"></i></button></span>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="product-buttons">
                                            <a href="javascript:void(0)" id="cartEffect" data-id="<?php echo e($product->id); ?>"
                                                class="btn btn-solid hover-solid btn-animation"><i
                                                    class="fa fa-shopping-cart me-1" aria-hidden="true"></i> Thêm giỏ hàng</a> 
                                            <a href="javascript:void(0)" data-id="<?php echo e($product->id); ?>" id="addWishlist" class="btn btn-solid"><i
                                                    class="fa fa-bookmark fz-16 me-2"
                                                    aria-hidden="true"></i>Thêm yêu thích</a></div>
                                        <div class="product-count">
                                            <ul>
                                                <li>
                                                    <img src="<?php echo e(asset('frontend/assets/images/icon/truck.png')); ?>" class="img-fluid"
                                                        alt="image">
                                                    <span class="lang">Giá ship tùy theo khu vực</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- tags -->
                                          
                                        <!-- end tags -->
                                        <!-- <div class="border-product">
                                            <h6 class="product-title">share it</h6>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li><a href="#"><i class="fa fa-facebook-f"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-rss"></i></a></li>
                                                </ul>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                             <!--product-->
                        </div>
                        <section class="tab-product m-0">
                            <div class="row">
                                <div class="col-sm-12 col-lg-12">
                                    <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" id="top-home-tab"
                                                data-bs-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i
                                                    class="icofont icofont-ui-home"></i>Mô tả</a>
                                            <div class="material-border"></div>
                                        </li>
                                        <!-- <li class="nav-item"><a class="nav-link" id="profile-top-tab"
                                                data-bs-toggle="tab" href="#top-profile" role="tab"
                                                aria-selected="false"><i
                                                    class="icofont icofont-man-in-glasses"></i>Specification</a>
                                            <div class="material-border"></div>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" id="contact-top-tab"
                                                data-bs-toggle="tab" href="#top-contact" role="tab"
                                                aria-selected="false"><i class="icofont icofont-contacts"></i>Video</a>
                                            <div class="material-border"></div>
                                        </li> -->
                                        <li class="nav-item"><a class="nav-link" id="review-top-tab"
                                                data-bs-toggle="tab" href="#top-review" role="tab"
                                                aria-selected="false"><i class="icofont icofont-contacts"></i></a>
                                            <div class="material-border"></div>
                                        </li>
                                    </ul>
                                    <div class="tab-content nav-material" id="top-tabContent">
                                        <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                            aria-labelledby="top-home-tab">
                                            <div class="product-tab-discription">
                                           
                                                <?php echo $product->description; ?>
                                            </div>
                                        </div>
                                        <?php echo $__env->make('frontend.layouts.tag', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <!-- <div class="tab-pane fade" id="top-profile" role="tabpanel"
                                            aria-labelledby="profile-top-tab">
                                            
                                        </div>
                                        <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                            aria-labelledby="contact-top-tab">
                                           
                                        </div> -->
                                        <div class="tab-pane fade" id="top-review" role="tabpanel"
                                            aria-labelledby="review-top-tab">
                                            <form class="theme-form">
                                                <div class="form-row row">
                                                    
                                                    <div class="col-md-6">
                                                        <label for="name">Tên</label>
                                                        <input type="text" class="form-control" id="name"
                                                            placeholder="tên của bạn" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email">Email</label>
                                                        <input type="text" class="form-control" id="email"
                                                            placeholder="Email" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="review">Tiêu đề</label>
                                                        <input type="text" class="form-control" id="review"
                                                            placeholder=" " required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="review">Nội dung</label>
                                                        <textarea class="form-control"
                                                            placeholder="Viết nhận xét của bạn"
                                                            id="exampleFormControlTextarea1" rows="6"></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button class="btn btn-solid" type="submit">Gửi</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                         
                        <section class="section-b-space ratio_asos">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 product-related">
                                        <h2>có thể bạn quan tâm</h2>
                                    </div>
                                </div>
                                <div class="row search-product">
                                    <?php $__currentLoopData = $randpros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $randpro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php  $nphotos = explode( ',', $randpro->photo); ?>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="product-box">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="<?php echo e(route('front.product.view',$randpro->slug)); ?>">
                                                        <img  src="<?php echo e(count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>"
                                                            class="img-fluid blur-up lazyload bg-img" alt="<?php echo e($randpro->title); ?>"></a>
                                                </div>
                                                <div class="back">
                                                    <a href="<?php echo e(route('front.product.view',$randpro->slug)); ?>">
                                                        <img src="<?php echo e(count($nphotos)>1?$nphotos[1]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>"
                                                            class="img-fluid blur-up lazyload bg-img" alt="<?php echo e($randpro->title); ?>"></a>
                                                </div>
                                                <div class="cart-info cart-wrap">
                                                            <button onclick="openCart()" title="Add to cart"><i
                                                                    class="ti-shopping-cart" data-id="<?php echo e($randpro->id); ?>"></i></button>
                                                            <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" data-id="<?php echo e($randpro->id); ?>"
                                                                    aria-hidden="true"></i></a>
                                                                        <!-- <a  href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view" title="Quick View"><i
                                                                        class="ti-search" aria-hidden="true"></i></a> 
                                                                        <a       href="compare.html" title="Compare"><i
                                                                        class="ti-reload" aria-hidden="true"></i></a> -->
                                                            </div>
                                            </div>
                                            <div class="product-detail">
                                                 
                                                <a href="<?php echo e(route('front.product.view',$randpro->slug)); ?>">
                                                    <h6><?php echo e($randpro->title); ?></h6>
                                                </a>
                                                <h4><?php echo e(number_format($randpro->price,0,'.',',')); ?></h4>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-sm-3 collection-filter">
                        <div class="theme-card">
                            <h5 class="title-border">Sản phẩm mới</h5>
                            <div class="offer-slider slide-1">
                                <div>
                                <?php
                                    $i = 0;
                                    for($i = 0;  $i < 3 && $i < count($newpros);$i ++)
                                    {
                                        $newpro = $newpros[$i];
                                        $nphotos = explode( ',', $newpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href="<?php echo e(route('front.product.view',$newpro->slug)); ?>">
                                                    <img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="<?php echo e(count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>" alt="<?php echo e($newpro->title); ?>"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="<?php echo e(route('front.product.view',$newpro->slug)); ?>">
                                                        <h6><?php echo e($newpro->title); ?></h6>
                                                    </a>
                                                    <h4><?php echo e(number_format($newpro->price,0,".",",")); ?></h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                                 <div>
                                <?php
                                     
                                    for( ;  $i < 6 && $i < count($newpros);$i ++)
                                    {
                                        $newpro = $newpros[$i];
                                        $nphotos = explode( ',', $newpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href="<?php echo e(route('front.product.view',$newpro->slug)); ?>"><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="<?php echo e(count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>" alt="<?php echo e($newpro->title); ?>"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="<?php echo e(route('front.product.view',$newpro->slug)); ?>">
                                                        <h6><?php echo e($newpro->title); ?></h6>
                                                    </a>
                                                    <h4><?php echo e(number_format($newpro->price,0,".",",")); ?></h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                            </div>
                        </div>
                         
                        <div class="theme-card">
                            <h5 class="title-border">Nổi bật</h5>
                            <div class="offer-slider slide-1">
                                <div>
                                <?php
                                    $i = 0;
                                    for($i = 0;  $i < 3 && $i < count($poppros);$i ++)
                                    {
                                        $poppro = $poppros[$i];
                                        $nphotos = explode( ',', $poppro->photo);
                                        ?>
                                            <div class="media">
                                                <a href="<?php echo e(route('front.product.view',$poppro->slug)); ?>"><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="<?php echo e(count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>" alt="<?php echo e($poppro->title); ?>"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="<?php echo e(route('front.product.view',$poppro->slug)); ?>">
                                                        <h6><?php echo e($poppro->title); ?></h6>
                                                    </a>
                                                    <h4><?php echo e(number_format($poppro->price,0,".",",")); ?></h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                                 <div>
                                <?php
                                     
                                    for( ;  $i < 6 && $i < count($poppros);$i ++)
                                    {
                                        $poppro = $poppros[$i];
                                        $nphotos = explode( ',', $poppro->photo);
                                        ?>
                                            <div class="media">
                                                <a href="<?php echo e(route('front.product.view',$poppro->slug)); ?>"><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="<?php echo e(count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')); ?>" alt="<?php echo e($poppro->title); ?>"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="<?php echo e(route('front.product.view',$poppro->slug)); ?>">
                                                        <h6><?php echo e($poppro->title); ?></h6>
                                                    </a>
                                                    <h4><?php echo e(number_format($poppro->price,0,".",",")); ?></h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                            </div>
                        </div>
                        <!-- side-bar single product slider end -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/frontend/product/single.blade.php ENDPATH**/ ?>