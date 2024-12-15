
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="<?php echo e($setting->short_name); ?>">
  <meta content="INDEX,FOLLOW" name="robots" />
  <meta name="copyright" content="<?php echo e($setting->site_url); ?>" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta http-equiv="audience" content="General" />
  <meta name="resource-type" content="Document" />
  <meta name="distribution" content="Global" />
  <meta name="revisit-after" content="1 days" />
  <meta name="GENERATOR" content="<?php echo e($setting->short_name); ?>" />
  <meta name="keywords" content= "<?php echo e(isset($keyword)?$keyword:$setting->keyword); ?>"/>
  <meta name="description" content= "<?php echo e(isset($description)?strip_tags($description):$setting->memory); ?>"/>
  
  <!-- Facebook Meta Tags -->
  <meta property="og:title" content=' <?php echo e(isset($page_up_title)?$page_up_title:$setting->web_title); ?>' />
  <meta property="og:description" content="<?php echo e(isset($description)?strip_tags($description):$setting->memory); ?>" />
  <meta property="og:image" content="<?php echo e(isset($ogimage)?$ogimage:$setting->logo); ?>" />
  <meta property="og:url" content='<?php echo e("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>'>
  <meta property="og:type" content="website">

  <!-- Twitter Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="<?php echo e($setting->site_url); ?>">
  <meta property="twitter:url" content='<?php echo e("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>'>
  <meta name="twitter:title" content=' <?php echo e(isset($page_up_title)?$page_up_title:$setting->web_title); ?>' />
  <meta name="twitter:description" content="<?php echo e(isset($description)?strip_tags($description):$setting->memory); ?>" />
  <meta name="twitter:image" content="<?php echo e(isset($ogimage)?$ogimage:$setting->logo); ?>" />
      
  <link href="<?php echo e($setting->icon); ?>" rel="shortcut icon">
  <link rel="shortcut icon" href="<?php echo e($setting->icon); ?>" type="image/x-icon" />
  <title><?php echo e(isset($page_up_title)?$page_up_title:""); ?> <?php echo e($setting->web_title); ?> </title>

  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Manrope:wght@400;500;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- fonts -->
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/assets_tp/fonts/unicons/unicons.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('frontend/assets_tp/css/plugins.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('frontend/assets_tp/style.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('frontend/assets_tp/css/icon.css')); ?>">
  <style>
    .tooltip {
      background: transparent !important;
      padding: 0 !important;

      }
      :root {
      --primary-color: #343f52;
      --secondary-color: <?php echo e(env('THEME_COLOR')); ?>;
      --background-color: #eff7fa;
      --text-color: #2d3748;
      --text-light-color: #acacac;
    }
    price{
      color: var(--secondary-color);
    }
    a{
      color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#3f78e0'); ?>  ;
    }
    a:hover{
      color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#3f78e0'); ?> !important ;
    }
    .text_light_color{
      color: var(--text-light-color);
    }
    .primarytextcolor{
      color: var(--primary-color);
    }
    .secondarytextcolor{
      color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#3f78e0'); ?>;
    }
    .secondarybackgroundcolor{
      background-color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#3f78e0'); ?>;
    }
    .navbar-expand-lg.navbar-light .dropdown:not(.dropdown-submenu)>.dropdown-toggle:after {
        --tw-text-opacity: 1;
        color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'rgb(63 120 224 / var(--tw-text-opacity))'); ?>;
    }
    .title_color{ color: var(--primary-color)} 
    .title_color:hover{ 
      color: var(--secondary-color)
    }
    .nav_color:hover{
      color: var(--secondary-color)
      
    }
    .lead_color{}
    
    .leading_title_color::before {
        content: var(--tw-content);
        --tw-bg-opacity: 1;
        background-color: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#3f78e0'); ?>;
    }
    .stroke {
        stroke: <?php echo e(env('THEME_COLOR')?env('THEME_COLOR'):'#54a8c7'); ?>;
    }
    a .btn, a .btn:hover, a .btn:disabled, a .btn:active{
      border-color: var(--secondary-color);
    }
    <?php if(env('SHOW_CART') == 0): ?>
        <?php if(auth()->user() && auth()->user()->full_name != 'demo1'): ?>
            .cart-box{
                display:none !important;
            }
            .item-cart,   #btn_add_to_cart{
                display:none !important;
            }
        <?php endif; ?>
        <?php if(!auth()->user()  ): ?>
            .cart-box{
                display:none !important;
            }
            .item-cart,   #btn_add_to_cart{
                display:none !important;
            }
        <?php endif; ?>
    <?php endif; ?>
  </style>
 
 <?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend_tp/layouts/head.blade.php ENDPATH**/ ?>