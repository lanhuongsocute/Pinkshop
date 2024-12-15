let mix = require('laravel-mix');

// mix.js('resources/js/app.js', 'js').sass('resources/sass/app.scss', 'css');

 
// 3. Merge and compile multiple scripts to dist/app.js
// mix.sass([
// 'public/frontend/assets/css/googlecss2.css',
// 'public/frontend/assets/css/vendors/font-awesome.css', 
// 'public/frontend/assets/css/vendors/slick.css',
//         'public/frontend/assets/css/vendors/slick-theme.css',
//         'public/frontend/assets/css/vendors/animate.css',
//         'public/frontend/assets/css/vendors/themify-icons.css',
//         'public/frontend/assets/css/vendors/bootstrap.css',
//         'public/frontend/assets/css/style.css',
//  ], 'app.css').setPublicPath('public/frontend/dist');

// mix.postCss('src/app.css', 'dist')
//    .options({
//        autoprefixer: { remove: false }
// //    });
// mix.css('public/frontend/assets/css/vendors/font-awesome.css', '.');
// mix.css( 'public/frontend/assets/css/vendors/slick.css', '.');
// mix.css('public/frontend/assets/css/vendors/slick-theme.css', '.');
// mix.css('public/frontend/assets/css/vendors/animate.css', '.');
// mix.css('public/frontend/assets/css/vendors/themify-icons.css', '.');
// mix.css('public/frontend/assets/css/vendors/bootstrap.css', '.');
// mix.css( 'public/frontend/assets/css/style.css', '.');
// mix.css( 'public/frontend/assets/css/googlecss2.css', '.');

// mix.postCss([
//     'public/frontend/assets/css/googlecss2.css',
// 'public/frontend/assets/css/vendors/font-awesome.css', 
// 'public/frontend/assets/css/vendors/slick.css',
//         'public/frontend/assets/css/vendors/slick-theme.css',
//         'public/frontend/assets/css/vendors/animate.css',
//         'public/frontend/assets/css/vendors/themify-icons.css',
//         'public/frontend/assets/css/vendors/bootstrap.css',
//         'public/frontend/assets/css/style.css',
//     ], 'dist')
//    .options({
//        autoprefixer: { remove: false }
//    });

//  mix.styles([
//     'public/frontend/dist/googlecss2.css',
// 'public/frontend/dist/font-awesome.css', 
// 'public/frontend/dist/slick.css',
//         'public/frontend/dist/slick-theme.css',
//         'public/frontend/dist/animate.css',
//         'public/frontend/dist/themify-icons.css',
//         'public/frontend/dist/bootstrap.css',
//         'public/frontend/dist/style.css',
//     ], 'public/frontend/dist/app.css') ;


//     <!-- latest jquery-->
//    
//    <!-- slick js-->
//    <script src="{{asset('frontend/assets/js/slick.js')}}"></script>
//    <script src="{{asset('frontend/assets/js/slick-animation.min.js')}}"></script>
   
//    <!-- menu js-->
//   
   
//    <!-- lazyload js-->
//    
   
//    <!-- feather icon js-->
//    <script src="{{asset('frontend/assets/js/feather.min.js')}} "></script>
   
//    <!-- Timer js-->
   
   
//    <!-- Bootstrap js-->
//   
   
//    <!-- Bootstrap Notification js-->
//    
   
//    <!-- Theme js-->
   

   
// mix.js([
//     // 'public/frontend/assets/js/jquery-3.3.1.min.js',
//     // 'public/frontend/assets/js/jquery-ui.min.js',
//     // 'public/frontend/assets/js/slick.js', 
//     'public/frontend/assets/js/slick-animation.min.js',
//     // 'public/frontend/assets/js/menu.js',
//     'public/frontend/assets/js/lazysizes.min.js',
//     // 'public/frontend/assets/js/feather.min.js',
//     'public/frontend/assets/js/bootstrap.bundle.min.js',
     
//     'public/frontend/assets/js/script.js',
//     // 'public/frontend/assets/js/custom-slick-animated.js'
       
//  ], 'app.js').setPublicPath('public/frontend/dist');