
<?php $__env->startSection('css'); ?>
    
     
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
     <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                 
                <div class="col-lg-6">
                    <h3>Đăng nhập</h3>
                    <div class="theme-card">
                        <form method="POST" action="<?php echo e(route('front.login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" value="<?php echo e(old('email')); ?>" placeholder="Email" name = "email" required="">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="review">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="" required="">
                            </div>
                            <div class="form-group">
                               <?php if(Route::has('password.request')): ?>
                                <a class=" btn-link" href="<?php echo e(route('password.request')); ?>">
                                  <?php echo e(__('Quên mật khẩu?')); ?>

                                </a>
                              <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-solid">Đăng nhập</button>
                            </div>
                            <input type='hidden' name='plink' value='<?php echo e($plink); ?>'/>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 right-login">
                    <h3>New Customer</h3>
                    <div class="theme-card authentication-right">
                        <h6 class="title-font">Tạo tài khoản</h6>
                        <p>Hãy đăng ký tài khoản để có thể thõa thích mua sắm trên website chúng tôi. Bạn có thể tham khảo qua chính sachs bảo mật, 
                            điều khoản và các quy định liên quan của chúng tôi.</p><a href="<?php echo e(route('front.register')); ?>"
                            class="btn btn-solid">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/auth/login.blade.php ENDPATH**/ ?>