 <!--modal popup start-->
 <?php if(env('DEMOAPP')==1): ?>
 <div>
    <div class="modal fade bd-example-modal-lg theme-modal" id="exampleModal" tabindex="-1" role="dialog"
        aria-hidden="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <div class="offer-content"> <img src="<?php echo e($detail->logo); ?>"
                                            class="img-fluid blur-up lazyload" alt="">
                                        <h2>Đây là demo hệ thống phần mềm quản lý và website bán hàng itcctv-soft</h2>
                                        <p class="!mb-6">Các thông tin sản phẩm và hình thức đặt hàng chỉ làm ví dụ. Kính mong quý khách không đưa các thông tin cá nhân trong quá trình sử dụng thử sản phẩm</p>
                                        <div class="newsletter-wrapper">
                                          <div class="flex flex-wrap mx-[-15px]">
                                            <div class="xl:w-10/12 lg:w-10/12 md:w-10/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:!ml-[8.33333333%] lg:!ml-[8.33333333%] md:!ml-[8.33333333%]">
                                              <!-- Begin Mailchimp Signup Form -->
                                              <div id="mc_embed_signup">
                                                Để đăng nhập vào admin xin vào link sau :
                                                    <a href="https://demo1.itcctv-soft.com/admin"> https://demo1.itcctv-soft.com/admin </a>
                                                    với tài khoản demoadmin@gmail.com/12345678 <br/>
                                                Để đăng nhập vào tài khoản khách hàng xin sử dụng tài khoản: demo1@gmail.com/12345678
                                              </div>
                                              <form action="<?php echo e(route('front.theme.update')); ?>" method = "post">
                                                  <?php echo csrf_field(); ?>
                                                   <label> Chọn theme </lable>
                                                   <?php
                                                    $themes = \App\Models\Themesetting::get();
                                                   ?>
                                                   <select class="" name="theme_id">
                                                    <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                      <option value="<?php echo e($theme->id); ?>"> <?php echo e($theme->title); ?> </option>
                                                      
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <button type="submit" class="btn" > Chọn </button>
                                              </form>
                                              <!--End mc_embed_signup-->
                                            </div>
                                            <!-- /.newsletter-wrapper -->
                                          </div>
                                          <!-- /column -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
<?php endif; ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/modalpopup.blade.php ENDPATH**/ ?>