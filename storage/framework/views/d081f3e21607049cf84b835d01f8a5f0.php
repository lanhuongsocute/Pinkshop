<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="<?php echo e($detail->short_name); ?>">
        <meta content="INDEX,FOLLOW" name="robots" />
        <meta name="copyright" content="<?php echo e($detail->site_url); ?>" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <meta http-equiv="audience" content="General" />
        <meta name="resource-type" content="Document" />
        <meta name="distribution" content="Global" />
        <meta name="revisit-after" content="1 days" />
        <meta name="GENERATOR" content="<?php echo e($detail->short_name); ?>" />
        <meta name="keywords" content= "<?php echo e(isset($keyword)?$keyword:$detail->keyword); ?>"/>
        <meta name="description" content= "<?php echo e(isset($description)?strip_tags($description):$detail->memory); ?>"/>
        
        <!-- Facebook Meta Tags -->
        <meta property="og:title" content=' <?php echo e(isset($page_up_title)?$page_up_title:$detail->web_title); ?>' />
        <meta property="og:description" content="<?php echo e(isset($description)?strip_tags($description):$detail->memory); ?>" />
        <meta property="og:image" content="<?php echo e(isset($ogimage)?$ogimage:$detail->logo); ?>" />
        <meta property="og:url" content='<?php echo e("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>'>
        <meta property="og:type" content="website">
 
        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="<?php echo e($detail->site_url); ?>">
        <meta property="twitter:url" content='<?php echo e("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>'>
        <meta name="twitter:title" content=' <?php echo e(isset($page_up_title)?$page_up_title:$detail->web_title); ?>' />
        <meta name="twitter:description" content="<?php echo e(isset($description)?strip_tags($description):$detail->memory); ?>" />
        <meta name="twitter:image" content="<?php echo e(isset($ogimage)?$ogimage:$detail->logo); ?>" />
        
    <link href="<?php echo e($detail->icon); ?>" rel="shortcut icon">
    <link rel="shortcut icon" href="<?php echo e($detail->icon); ?>" type="image/x-icon" />
    <title><?php echo e(isset($page_up_title)?$page_up_title:""); ?> <?php echo e($detail->web_title); ?> </title>

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/dist/app.css')); ?>">

       <style>
        .py-10{
            padding-top:10px;
        }
        .px-10{
            padding-right:10px;
            padding-left:10px;
        }
        .px-20{
            padding-right:20px;
            padding-left:20px;
        }
        .tag_4{
            margin:10px;
            font-size:140%;
            color:  var(--theme-color)  ;
            
        }
        .tag_3{
            margin:10px;
            font-size:120%;
            color:  var(--theme-color)  ;
            
        }
        .tag_2{
            font-size:100%;
            color:  var(--theme-color) ;
            margin:10px;
        }
        .tag_1{
            font-size:80%;
            color:  var(--theme-color) ;
            margin:10px;
        }
        <?php if(env('SHOW_CART') == 0): ?>
            <?php if(auth()->user() && auth()->user()->full_name != 'demo1'): ?>
                .cart-box{
                    display:none !important;
                }
                .cart-info,  .icon-nav, .input-group, .product-buttons{
                    display:none !important;
                }
            <?php endif; ?>
            <?php if(!auth()->user()  ): ?>
                .cart-box{
                    display:none !important;
                }
                .cart-info,  .icon-nav, .input-group, .product-buttons{
                    display:none !important;
                }
            <?php endif; ?>
        <?php endif; ?>
        .product-tab-discription li { display:list-item}
        .blog-detail li{
            display:list-item;
            padding-left:30px;
            padding-top:10px;
            padding-bottom:10px;
            margin-left:30px;
        }
        .media {
            
                display: block !important;
        }
        .blog-container img, .product-tab-discription img {
            max-width: 100%;
            height: auto; /* Maintains the aspect ratio */
        }
        .breadcrumb-item {
            text-transform: capitalize !important;
        }
        #bct img{
                        width:100%;
                        height:auto;
        }
        .section-b-space {
            padding-bottom:5px !important;
        }                
       .breadcrumb-section {
            background-color: #eee;
       }
       .tab-product .tab-content.nav-material p  {
            font-size: 14px !important;
            color: black !important;
            /* color: #777; */
            line-height: 24px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tab-product .tab-content.nav-material ul  {
            
           padding-left:35px;
        }
        <?php if(env('THEME_COLOR')!= ''): ?>
            .theme-color-10 {
                --theme-color: <?php echo e(env('THEME_COLOR')); ?> !important;
            }
            #main-menu li a{
            /* font-weight:400 !important; */
            color: <?php echo e(env('THEME_COLOR')); ?> !important;
            
            }
            #main-menu li a:hover{
                    font-weight:700 !important;
                    color: <?php echo e(env('THEME_COLOR')); ?> !important;
                    
            }
            .header-style-5 .bottom-part.bottom-light .pixelstrap>li>a {
                    color: <?php echo e(env('THEME_COLOR')); ?> !important;
                }
            .title, .title-border{
                    color: <?php echo e(env('THEME_COLOR')); ?> !important;
                }
            .top-header.top-header-dark {
                    background-color: <?php echo e(env('THEME_COLOR')); ?> !important;
                }
        <?php endif; ?>
    </style>
<?php echo $__env->yieldContent('css'); ?>
<?php echo $__env->yieldContent('scriptop'); ?>
<?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/layouts/head.blade.php ENDPATH**/ ?>