<?php $detail = \App\Models\SettingDetail::find(1); ?>
                            
<footer class="dark-footer footer-style-1">
        <section class="section-b-space darken-layout">
            <div class="container">
                <div class="row footer-theme partition-f">
                    <div class="col-lg-4 col-md-6 sub-title">
                        <div class="footer-title footer-mobile-title">
                            <h4>Thông tin công ty</h4>
                        </div>
                        <div class="footer-contant">
                            <div class="footer-logo"><img src="<?php echo e($detail->logo); ?>" alt=""></div>
                            <h3><?php echo e($detail->company_name); ?> </h3>
                            <p><?php echo e($detail->memory); ?></p>
                            <ul class="contact-list">
                             
                                <li><i class="fa fa-map-marker"></i><?php echo e($detail->address); ?>

                                </li>
                                <li><i class="fa fa-phone"></i>
                                Điện thoại: <?php echo e($detail->phone); ?></li>
                                <li><i class="fa fa-envelope"></i>Email: <?php echo e($detail->email); ?></li>
                                <li><i class="fa fa-book"></i>Mã số doanh nghiệp: <?php echo e($detail->mst); ?></li>
                                <li><i class="fa fa-book"></i><?php echo e($detail->thoigiandk); ?></li>
                                <li><i class="fa fa-book"></i><?php echo e($detail->nguoilienhe); ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>Link hữu ích</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','chinh-sach-bao-mat')); ?>">Chính sách bảo mật</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','dieu-khoan-va-quy-dinh')); ?>">Điều khoản và quy định</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','chinh-sach-hoan-tra')); ?>">Chính sách hoàn trả</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','chinh-sach-bao-hanh')); ?>">Chính sách bảo hành</a></li>
                                    
                                    <li><a href="<?php echo e(route('front.chinhsach.view','chinh-sach-giao-van')); ?>">Chính sách giao vận</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','tai-khoan-cong-ty')); ?>">Phương thức thanh toán</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','chinh-sach-bao-ve-thong-tin-nguoi-dung')); ?>">Chính sách bảo vệ thông tin người dùng</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>Thông tin khác</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    <li><a href="<?php echo e(route('front.profile')); ?>">Cập nhật hồ sơ</a></li>
                                    <li><a href="<?php echo e(route('front.shopingcart.view')); ?>">Giỏ hàng</a></li>
                                    <li><a href="#">Đơn hàng</a></li>
                                    <li><a href="#">Công nợ</a></li>
                                    <li><a href="<?php echo e(route('front.contact')); ?>">Liên hệ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>Follow us</h4>
                            </div>
                            <div class="footer-contant">
                               <img style="height:250px; width:400px "class="img-fluid blur-up lazyload  "  src = "<?php echo e($detail->map); ?>" />
                              
                              
                               <div class="footer-social">
                                    <ul>
                                         
                                        <li><a href="<?php echo e($detail->facebook); ?>"><img src="<?php echo e(asset('frontend/assets/images/icon/facenho.png')); ?>" class=" "/> </a></li>
                                        <li><a href="<?php echo e($detail->shopee); ?>"><img src="<?php echo e(asset('frontend/assets/images/icon/shopeenho.png')); ?>" class=" "/> </a></li>
                                        <li><a href="<?php echo e($detail->lazada); ?>"><img src="<?php echo e(asset('frontend/assets/images/icon/laznho.png')); ?>" class=" "/> </a></li>
 
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="sub-footer dark-subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                    
                        <div class="footer-end">
                        
                            <p>
                           
                            <i class="fa fa-copyright" aria-hidden="true"></i> 2023-24 <?php echo e($detail->short_name); ?> - đang xây dựng</p>
                        </div>
                        
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="payment-card-bottom">
                        <div id='bct' style="padding-top:5px;width:200px; height:auto;  ">
                                  
                                  <?php
                                           echo $detail->logo_bct ;
                                           ?>
                                           </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
       
       <?php /**PATH D:\xampp\htdocs\shop4\resources\views/frontend/layouts/footer.blade.php ENDPATH**/ ?>