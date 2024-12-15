<?php
  $categories = \App\Models\Category::where('status','active')->orderBy('title','ASC')->get();
      
?>
<header class="header-style-5">
        <div class="mobile-fix-option"></div>
        <div class="top-header top-header-dark">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-contact">
                            <ul>
                                <li>Chào mừng bạn đến <?php echo e($detail->short_name); ?></li>
                                <li><i class="fa fa-phone" aria-hidden="true"></i>hot line: <?php echo e($detail->hotline); ?></li>
                            </ul>
                        </div>
                    </div>
                    <?php
                        $user = auth()->user();
                   
                        
                       
                        if($user)
                        {
                            $title = "Tài khoản";
                            $link = route('front.profile');
                            $linkpro="logout.php";
                            $titlepro = "Đăng xuất";
                        }
                        else
                        {
                            $link = route('front.register') ;
                            $linkpro= route('front.login') ;
                            $title = "Đăng ký";
                            $titlepro = "Đăng nhập";
                        }
                        ?>
                    <div class="col-lg-6 text-end">
                        <ul class="header-dropdown">
                            
                            <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                               
                            
                                <a href="<?php echo e($link); ?>"> <?php echo e($title); ?> </a>    |  
                                <?php if($user): ?>
                                    <a href=""  onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"  >
                                        
                                        <span> <?php echo e($titlepro); ?>  </span>
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo e($linkpro); ?>"> <?php echo e($titlepro); ?> </a> 
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-menu">
                        <div class="menu-left">
                            <div class="navbar d-block d-xl-none">
                                <a href="javascript:void(0)">
                                    <div class="bar-style" id="toggle-sidebar-res"><i class="fa fa-bars sidebar-bar"
                                            aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div>
                            
                          
                            <div class="brand-logo">
                                <a href="<?php echo e(route('home')); ?>"><img src="<?php echo e($detail->logo); ?>"
                                        class="img-fluid blur-up lazyload" alt="<?php echo e($detail->keyword); ?>"></a>
                            </div>
                        </div>
                        <div>
                            <form method = "GET" action="<?php echo e(route('front.product.search')); ?>" class="form_search" role="form">
                                <?php echo csrf_field(); ?>       
                                <input id="query search-autocomplete" type="search" name="searchdata"
                                    placeholder="Tìm kiếm sản phẩm..." class="nav-search nav-search-field"
                                    aria-expanded="true">
                                <button type="submit" name="nav-submit-button" class="btn-search">
                                    <i class="ti-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="menu-right pull-right">
                            <nav class="text-start">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                            </nav>
                            <div>
                                <div class="icon-nav">
                                    <ul>
                                        <li class="onhover-div d-xl-none d-inline-block mobile-search">
                                            <div><img src="<?php echo e(asset('frontend/assets/images/icon/search.png')); ?>" onclick="openSearch()"
                                                    class="img-fluid blur-up lazyload" alt=""> <i class="ti-search"
                                                    onclick="openSearch()"></i></div>
                                            <div id="search-overlay" class="search-overlay">
                                                <div> <span class="closebtn" onclick="closeSearch()"
                                                        title="Close Overlay">×</span>
                                                    <div class="overlay-content">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <form method = "POST" action="<?php echo e(route('front.product.search')); ?>">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control"
                                                                                id="exampleInputPassword1"
                                                                                placeholder="Search a Product">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary"><i
                                                                                class="fa fa-search"></i></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        
                                        <li class="onhover-div mobile-cart">
                                          <?php echo $__env->make('frontend.layouts.head_shopcart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-part bottom-light">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="category-menu d-none d-xl-block h-100">
                            <div id="toggle-sidebar" class="toggle-sidebar">
                                <i class="fa fa-bars sidebar-bar"></i>
                                <h5 class="mb-0">Danh mục</h5>
                            </div>
                        </div>
                        <div class="sidenav fixed-sidebar marketplace-sidebar" style="z-index:1000">
                            <nav>
                                <div>
                                    <div class="sidebar-back text-start d-xl-none d-block"><i
                                            class="fa fa-angle-left pe-2" aria-hidden="true"></i> Back</div>
                                </div>
                                <ul id="sub-menu" class="sm pixelstrap sm-vertical">
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li> <a href="<?php echo e(route('front.product.cat',$cat->slug)); ?>"><?php echo e($cat->title); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                        
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="main-nav-center">
                            <nav class="text-start">
                                <!-- Sample menu definition -->
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2"
                                                aria-hidden="true"></i></div>
                                    </li>
                                    <li><a href="<?php echo e(route('front.product.hot' )); ?>">Sản phẩm hot</a></li>
                                    <li><a href="<?php echo e(route('front.product.cat','laptop')); ?>">Laptop</a></li>
                                    <li><a href="<?php echo e(route('front.product.cat','camera-wifi')); ?>">Camera</a></li>
                                    <li><a href="<?php echo e(route('front.chinhsach.view','bang-gia')); ?>">Bảng giá</a></li>
                                    <li><a href="<?php echo e(route('front.categories.view' )); ?>">Tin tức</a></li>
                                    <li><a  href="<?php echo e(route('front.contact')); ?>">Liên hệ</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/layouts/header.blade.php ENDPATH**/ ?>