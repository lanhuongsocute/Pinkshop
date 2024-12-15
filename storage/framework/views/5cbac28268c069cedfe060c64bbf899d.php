<?php
    $mod_pros = \App\Models\FrontProMod::where('status','active')->orderBy('order_id','asc')->get();
?>

 
<?php $__currentLoopData = $mod_pros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod_pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<?php
    $mod_pro_details = \App\Models\FrontProModDetail::where('mod_id',$mod_pro->id)->orderBy('order_id','asc')->get();
?>
<section class="ratio_square">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="title-basic  " style ="  padding: 0 0 14px 0;  border-bottom: 1px solid #ddd;">
                        <h2 class="title"><i class="ti-bolt"></i> <?php echo e($mod_pro->title); ?></h2>
                        <?php if($mod_pro->mod_type == 1): ?>
                        <div class="timer">
                            <p id="demo"></p>
                        </div>
                        <?php endif; ?>
                    </div>
                   
                    
                    <div class="product-5 product-m no-arrow">
                    <?php $__currentLoopData = $mod_pro_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $products = \DB::select('select a.*, b.old_price from (select * from products where id = '.$pro_detail->pro_id.') as a left join productextends b on a.id = b.product_id  ') ;
                    ?>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                          $photos = explode( ',', $product->photo);
                          $word = 0;
                          if ($product->price < $product->old_price )
                            $word = round(($product->old_price - $product->price)*100 /$product->old_price);
                          ?>
                            <div class="product-box product-wrap">
                                <div class="img-wrapper">
                                    <?php if($word > 0): ?>
                                    <div class="lable-block"><span class="lable3"><?php echo e("- ".$word.'%'); ?></span>  </div>
                                    <?php endif; ?>
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
                                    <h4> <del> <?php echo e($product->old_price?number_format($product->old_price,0,".",",") :''); ?></del> 
                                     <?php echo e(number_format($product->price,0,".",",")); ?></h4>
                                    
                                </div>
                            </div>      

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                     </div>
                   
                </div>
            </div>
        </div>
    </section>

    

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/layouts/modpro.blade.php ENDPATH**/ ?>