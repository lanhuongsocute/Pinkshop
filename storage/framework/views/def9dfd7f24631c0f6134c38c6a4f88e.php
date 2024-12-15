
<?php $__env->startSection('css'); ?>
    
     
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!--  dashboard section start -->
    <section class="dashboard-section section-b-space user-dashboard-section">
        <div class="container">
            <div class="row">
                <!-- left side bar -->
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <div class="profile-top">
                            <div class="profile-image">
                                <img src="<?php echo e(isset($profile->photo)?$profile->photo:asset('frontend/assets/images/avtar.jpg')); ?>" alt="" class="img-fluid">
                            </div>
                            <div class="profile-detail">
                                
                                <h5><?php echo e($profile->full_name); ?></h5>
                                <h6><?php echo e($profile->email); ?></h6>
                            </div>
                        </div>
                        <div class="faq-tab">
                            <ul class="nav nav-tabs" id="top-tab" role="tablist">
                                <li class="nav-item"><a  
                                        class="nav-link active">Thông tin tài khoản</a></li>
                                        <li class="nav-item"><a  href="<?php echo e(route('front.shopingcart.view')); ?>"
                                        class="nav-link">Giỏ hàng</a></li>
                                        <li class="nav-item"><a  href="<?php echo e(route('front.profile.addressbook')); ?>"
                                        class="nav-link">Danh sách địa chỉ</a></li>
                                
                                <li class="nav-item"><a  href="<?php echo e(route('front.wishlist.view')); ?>"
                                        class="nav-link">SP Yêu thích</a></li>
                                <li class="nav-item"><a  
                                        class="nav-link">Đơn hàng</a></li>
                                <li class="nav-item"><a  
                                        class="nav-link">Công nợ</a></li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                  <!-- left side bar -->
                  <!-- right side content -->
                <div class="col-lg-9">
                    <div class="faq-content tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="info">
                            <div class="counter-section">
                                 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="<?php echo e(asset('frontend/assets/images/icon/dashboard/sale.png')); ?>" class="img-fluid">
                                            <div>
                                                <h3><?php echo e($totalorder); ?></h3>
                                                <h5>Đơn hàng</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="<?php echo e(asset('frontend/assets/images/icon/dashboard/homework.png')); ?>" class="img-fluid">
                                            <div>
                                                <h3><?php echo e($totalpendorder); ?></h3>
                                                <h5>Đơn hàng đang đợi</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="<?php echo e(asset('frontend/assets/images/icon/dashboard/order.png')); ?>" class="img-fluid">
                                            <div>
                                                <h3><?php echo e($totalwishlist); ?></h3>
                                                <h5>SP yêu thích</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-account box-info">
                                    <div class="box-head">
                                        <h4>Thông tin tài khoản</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box">
                                                <div class="box-title">
                                                    <h3>Thông tin liên hệ</h3>
                                                    <a href="javascript:void(0)"
                                                                    data-bs-target="#updateName"
                                                                    data-bs-toggle="modal" class="bottom_btn">điều chỉnh</a>
                                                </div>
                                                <div class="box-content px-20">
                                                    <h6><?php echo e($profile->full_name); ?></h6>
                                                    <h6><?php echo e($profile->email); ?></h6>
                                                    <h6><?php echo e($profile->phone); ?></h6>
                                                    <h6><?php echo e($profile->address); ?></h6>
                                                    <h6>
                                                    <a href="javascript:void(0)"
                                                                    data-bs-target="#changePassword"
                                                                    data-bs-toggle="modal" class="bottom_btn">Đổi mật khẩu</a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box">
                                                <div class="box-title">
                                                    <h3>Thông tin mô tả</h3>
                                                    <a href="javascript:void(0)"
                                                                    data-bs-target="#editDescription"
                                                                    data-bs-toggle="modal" class="bottom_btn">điều chỉnh</a>
                                                </div>
                                                <div class="box-content px-20">
                                                    <?php echo e($profile->description); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box mt-3">
                                        <div class="box-title">
                                            <h3>Thông tin đơn vị</h3>
                                            <a href="javascript:void(0)"
                                                                    data-bs-target="#editCompanyAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">điều chỉnh</a>
                                           
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h6>Tên công ty: <span> <?php echo e($profile->taxname); ?>   </span></h6> 
                                                <h6>Mst: <span> <?php echo e($profile->taxcode); ?></span></h6> 
                                                <h6>Địa chỉ: <span> <?php echo e($profile->taxaddress); ?> </span></h6> 
                                                <address>  </address>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="box mt-3">
                                        <div class="box-title">
                                            <h3>Địa chỉ mặc định</h3> 
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h5>Địa chỉ nhận hàng</h5>
                                                <?php if($defaut_setting && isset($invoiceaddress)): ?>
                                                <div class="px-20">  
                                                    <h6> <?php echo e($invoiceaddress->full_name); ?> </j6>
                                                    <h6> <?php echo e($invoiceaddress->phone); ?> </j6>
                                                    <h6> <?php echo e($invoiceaddress->address); ?> </j6>
                                                </div>
                                                <?php else: ?>
                                                <a href="javascript:void(0)"
                                                                    data-bs-target="#addInvoiceAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                                <?php endif; ?>
                                               
                                            </div>
                                            <div class="col-sm-6">
                                                <h5>Địa chỉ nhận hóa đơn</h5>
                                                <?php if($defaut_setting && isset($shipaddress)): ?>
                                                <div  class="px-10">  
                                                    <h6> <?php echo e($shipaddress->full_name); ?> </j6>
                                                    <h6> <?php echo e($shipaddress->phone); ?> </j6>
                                                    <h6> <?php echo e($shipaddress->address); ?> </j6>
                                                </div>
                                                <?php else: ?>
                                                <a href="javascript:void(0)"
                                                                    data-bs-target="#addShipAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                 <!-- right side content -->
            </div>
        </div>
    </section>
    <!--  dashboard section end -->
    <!-- Modal start -->
    <div class="modal logout-modal fade" id="addShipAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.addshipadd')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên đầy đủ</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name"  
                                       value="<?php echo e(old('full_name')); ?>" required >
                                </div>
                                <div class="col-md-12   py-10">
                                    <label for="review">Điện thoại</label>
                                    <input type="text" name="phone" class="form-control" id="phone"  
                                    value="<?php echo e(old('phone')); ?>"  required >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" id="address" value="<?php echo e(old('address')); ?>"
                                          required  >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <input type="checkbox" name="default" value = "1"/> địa chỉ mặc định
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
    <!--description modal -->
 <div class="modal logout-modal fade" id="editDescription" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Điều chỉnh mô tả</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.updatedescription')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Đôi lời về bản thân</label>
                                    <textarea name="description" class="form-control" id="taxname"><?php echo e($profile->description); ?></textarea>
                                </div>
                              
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
   <!-- description modal -->
 <!-- Company address modal -->
 <div class="modal logout-modal fade" id="editCompanyAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Điều chỉnh thông tin công ty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.updatetax')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên công ty</label>
                                    <input type="text" name="taxname" class="form-control" id="taxname"  
                                       value="<?php echo e($profile->taxname); ?>" required >
                                </div>
                                <div class="col-md-12   py-10">
                                    <label for="review">MST</label>
                                    <input type="text" name="taxcode" class="form-control" id="taxcode"  
                                    value="<?php echo e($profile->taxcode); ?>"  required >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="taxaddress" class="form-control" id="taxaddress" value="<?php echo e($profile->taxaddress); ?>"
                                          required  >
                                </div>
                                
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
   <!-- Company address modal -->

<!--password modal -->
    <div class="modal logout-modal fade" id="changePassword" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Đổi mật khẩu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.changepass')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Mật khẩu hiện tại</label>
                                   <input  class="form-control" type="password" name="current_password" />
                                </div>
                                <div class="col-md-12 py-10">
                                    <label for="email">Mật khẩu mới</label>
                                   <input  class="form-control" type="password" name="new_password" />
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
<!--password modal -->
  
<!--name modal -->
<div class="modal logout-modal fade" id="updateName" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cập nhật tên, địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.updatename')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên</label>
                                   <input  class="form-control" type="text" name="full_name" value="<?php echo e($profile->full_name); ?>" />
                                </div>
                                <div class="col-md-12 py-10">
                                    <label for="email">Địa chỉ</label>
                                   <input  class="form-control" type="text" name="address" value="<?php echo e($profile->address); ?>" />
                                </div>
                                
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <!-- Modal invoice start -->
    <div class="modal logout-modal fade" id="addInvoiceAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="<?php echo e(route('front.profile.addinvoiceadd')); ?>">
                            <?php echo csrf_field(); ?>    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên đầy đủ</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name"  
                                       value="<?php echo e(old('full_name')); ?>" required >
                                </div>
                                <div class="col-md-12   py-10">
                                    <label for="review">Điện thoại</label>
                                    <input type="text" name="phone" class="form-control" id="phone"  
                                    value="<?php echo e(old('phone')); ?>"  required >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" id="address" value="<?php echo e(old('address')); ?>"
                                          required  >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <input type="checkbox" name="default" value = "1"/> địa chỉ mặc định
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <button type="submit" id="btnAddInv" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                               
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <!-- modal invoice end -->
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/profile/view.blade.php ENDPATH**/ ?>