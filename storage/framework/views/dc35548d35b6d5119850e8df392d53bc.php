<section class="ratio_square">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="title-basic">
                        <h2 class="title"><i class="ti-bolt"></i> Sản phẩm đặc biệt trong ngày</h2>
                        <div class="timer">
                            <p id="demo"></p>
                        </div>
                    </div>
                   
                    
                    <div class="product-5 product-m no-arrow">
                        <?php $__currentLoopData = $rand_pros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                          $photos = explode( ',', $product->photo);
                           
                          ?>
                        <div class="product-box product-wrap">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="<?php echo e(route('front.product.view',$product->slug)); ?>"><img
                                            src="<?php echo e($photos[0]?$photos[0]:asset('frontend/assets/images/electronics/pro/26.jpg')); ?>"
                                            class="img-fluid blur-up lazyload bg-img" alt="<?php echo e($product->title); ?>" title="<?php echo e($product->title); ?>"></a>
                                </div>
                                <div class="cart-box style-1 rounded-0">
                                    <button   title="Add to cart"><i
                                            class="ti-shopping-cart" data-id="<?php echo e($product->id); ?>"></i></button>
                                    <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" data-id="<?php echo e($product->id); ?>"
                                            aria-hidden="true"></i></a>
                                  <!--   <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                        title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> -->
                                    <!-- <a href="compare.html" title="Compare"><i class="ti-reload"
                                            aria-hidden="true"></i></a> -->
                                </div>
                            </div>
                            <div class="product-detail">
                                 
                                <a href="<?php echo e(route('front.product.view',$product->slug)); ?>">
                                    <p style="font-size:16px"><?php echo e($product->title); ?></p>
                                </a>
                                <h4><?php echo e(number_format($product->price,0,".",",")); ?></h4>
                                
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                   
                </div>
            </div>
        </div>
    </section><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/home_dealday.blade.php ENDPATH**/ ?>