/*-----------------------------------------------------------------------------------

 Template Name:Multikart
 Template URI: themes.pixelstrap.com/multikart
 Description: This is E-commerce website
 Author: Pixelstrap
 Author URI: https://themeforest.net/user/pixelstrap

 ----------------------------------------------------------------------------------- */
// 01.Pre loader
// 02.Tap on Top
// 03.Age verify modal
// 04.Mega menu js
// 05.Image to background js
// 06.Filter js
// 07.Left offer toggle
// 08.Toggle nav
// 09.Footer according
// 10.Add to cart quantity Counter
// 11.Product page Quantity Counter
// 12.Full slider
// 13.Slick slider
// 14.Header z-index js
// 15.Tab js
// 16.Category page
// 17.Filter sidebar js
// 18.Add to cart
// 19.Add to wishlist
// 20.Color Picker
// 21.RTL & Dark-light
// 22.Menu js
// 23.Theme-setting
// 24.Add to cart sidebar js
// 25.Tooltip
///define object
 

///////////////


/*=====================
 21. Dark Light
 ==========================*/

var body_event = $("body");
body_event.on("click", ".dark-btn", function () {
    $(this).toggleClass('dark');
    $('body').removeClass('dark');
    if ($('.dark-btn').hasClass('dark')) {
        $('.dark-btn').text('Light');
        $('body').addClass('dark');
    } else {
        $('#theme-dark').remove();
        $('.dark-btn').text('Dark');
    }

    return false;
});


/*=====================
 22. Menu js
 ==========================*/
function openNav() {
    document.getElementById("mySidenav").classList.add('open-side');
}

function closeNav() {
    document.getElementById("mySidenav").classList.remove('open-side');
}
$(function () {
    $('#main-menu').smartmenus({
        subMenusSubOffsetX: 1,
        subMenusSubOffsetY: -8
    });
    $('#sub-menu').smartmenus({
        subMenusSubOffsetX: 1,
        subMenusSubOffsetY: -8
    });
});


/*=====================
 23.Tooltip
 ==========================*/
$(window).on('load', function () {
    $('[data-toggle="tooltip"]').tooltip()
});

/*=====================
 24. Cookiebar
 ==========================*/
window.setTimeout(function () {
    $(".cookie-bar").addClass('show')
}, 5000);

$('.cookie-bar .btn, .cookie-bar .btn-close').on('click', function () {
    $(".cookie-bar").removeClass('show')
});

/*=====================
 25. Recently puchase modal
 ==========================*/
setInterval(function () {
    $(".recently-purchase").toggleClass('show')
}, 20000);

$('.recently-purchase .close-popup').on('click', function () {
    $(".recently-purchase").removeClass('show')
});


/*=====================
 26. other js
 ==========================*/
var width_content = jQuery(window).width();
if ((width_content) > '991') {

    $(".filter-bottom-title").click(function () {
        $(".filter-bottom-content").slideToggle("");
    });
    $(".close-filter-bottom").click(function () {
        $(".filter-bottom-content").slideUp("");
    });
} else {
    $(".filter-bottom-title").click(function () {
        $(".filter-bottom-content").toggleClass("open");
    });
    $(".close-filter-bottom").click(function () {
        $(".filter-bottom-content").removeClass("open");
    });
}

if ((width_content) < '991') {
    $('.filter-bottom-title').on('click', function (e) {
        $('.filter-bottom-content').css("left", "-15px");
    });
}

$('.color-variant li').on('click', function (e) {
    $(".color-variant li").removeClass("active");
    $(this).addClass("active");
});

$('.custom-variations li').on('click', function (e) {
    $(".custom-variations li").removeClass("active");
    $(this).addClass("active");
});

$('.size-box ul li').on('click', function (e) {
    $(".size-box ul li").removeClass("active");
    $('#selectSize').removeClass('cartMove');
    $(this).addClass("active");
    $(this).parent().addClass('selected');
});

$('#cartEffect').on('click', function (e) {
    if ($("#selectSize .size-box ul").hasClass('selected')) {
        $('#cartEffect').text("Added to bag ");
        $('.added-notification').addClass("show");
        setTimeout(function () {
            $('.added-notification').removeClass("show");
        }, 5000);
    } else {
        $('#selectSize').addClass('cartMove');
    }
});

// modern product box plus effect
$('.add-extent .animated-btn').on('click', function (e) {
    $(this).parents(".add-extent").toggleClass("show");
});