@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')

    <!--  dashboard section start -->
    <section class="dashboard-section section-b-space user-dashboard-section">
        <div class="container">
            <div class="row">
                <!-- left side bar -->
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <div class="profile-top">
                            <div class="profile-image">
                                <img src="{{isset($profile->photo)?$profile->photo:asset('frontend/assets/images/avtar.jpg')}}" alt="" class="img-fluid">
                            </div>
                            <div class="profile-detail">
                                
                                <h5>{{$profile->full_name}}</h5>
                                <h6>{{$profile->email}}</h6>
                            </div>
                        </div>
                        <div class="faq-tab">
                            <ul class="nav nav-tabs" id="top-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('front.profile')}}"
                                        >Thông tin tài khoản</a></li>
                                <li class="nav-item">
                                    <a  href="{{route('front.shopingcart.view')}}"
                                        class="nav-link">Giỏ hàng</a></li>
                                <li class="nav-item">
                                    <a  class="nav-link " href="{{route('front.profile.addressbook')}}"
                                        >Danh sách địa chỉ</a></li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('front.wishlist.view')}}">SP Yêu thích</a></li>
                                <li class="nav-item">
                                    <a class="nav-link">Đơn hàng</a></li>
                                <li class="nav-item">
                                    <a   class="nav-link">Công nợ</a></li>
                                
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
                                            <img src="{{asset('frontend/assets/images/icon/dashboard/sale.png')}}" class="img-fluid">
                                            <div>
                                                <h3>{{$totalorder}}</h3>
                                                <h5>Đơn hàng</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="{{asset('frontend/assets/images/icon/dashboard/homework.png')}}" class="img-fluid">
                                            <div>
                                                <h3>{{$totalpendorder}}</h3>
                                                <h5>Đơn hàng đang đợi</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="counter-box">
                                            <img src="{{asset('frontend/assets/images/icon/dashboard/order.png')}}" class="img-fluid">
                                            <div>
                                                <h3>{{$totalwishlist}}</h3>
                                                <h5>SP yêu thích</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row py-10">
                                    <h3>Danh sách sản phẩm yêu thích</h3>
                                </div>
                      
                                <div class="box-account box-info row">
                                    @foreach ($products as $randpro )
                                    <?php  $nphotos = explode( ',', $randpro->photo); ?>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="product-box">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="{{route('front.product.view',$randpro->slug)}}">
                                                        <img style="width:100%" src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}"
                                                            class="img-fluid blur-up lazyload  " alt=""></a>
                                                </div>
                                               
                                                <div class="cart-info cart-wrap">
                                                            <button onclick="openCart()" title="Add to cart"><i
                                                                    class="ti-shopping-cart" data-id="{{ $randpro->id}}"></i></button>
                                                            
                                                                  
                                                            </div>
                                            </div>
                                            <div class="product-detail">
                                                
                                                <a href="{{route('front.product.view',$randpro->slug)}}">
                                                    <h6>{{$randpro->title}}</h6>
                                                </a>
                                                <h4>{{number_format($randpro->price,0,'.',',')}}</h4>
                                                <a href="{{route('front.wishlist.remove',$randpro->id)}} " class="align-self-center">
                                                    <span> Xóa  </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
   
 
 
@endsection
@section('scripts')
<script>
    function add_notify(msg, status)
    {
        $.notify({
                    icon: 'fa fa-check',
                    title: status?'Thành Công!':'Thất bại!',
                    message:  msg,
                }, {
                    element: 'body',
                    position: null,
                    type: status?"info":"warning",
                    allow_dismiss: false,
                    newest_on_top: false,
                    showProgressbar: true,
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    offset: 20,
                    spacing: 10,
                    z_index: 1031,
                    delay: 2000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                    icon_type: 'class',
                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                        '<button type="button" aria-hidden="true" class="btn-close" data-notify="dismiss"></button>' +
                        '<span data-notify="icon"></span> ' +
                        '<span data-notify="title">{1}</span> ' +
                        '<span data-notify="message">{2}</span>' +
                        '<div class="progress" data-notify="progressbar">' +
                        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                        '</div>' +
                        '<a href="{3}" target="{4}" data-notify="url"></a>' +
                        '</div>'
                });
    }

    $('.invoice_ra').on('click', function () {
        var invoice_id = $(this).attr("value");
        $.ajax({
            type: 'GET',
            url: '{{route("front.address.setinvoice")}}',
            data: {
                id: invoice_id,
            },
            success: function(data) {
                add_notify(data.msg,data.status);
            },
        }); 
    });

    $('.ship_ra').on('click', function () {
        var ship_id = $(this).attr("value");
        $.ajax({
            type: 'GET',
            url: '{{route("front.address.setship")}}',
            data: {
                id: ship_id,
            },
            success: function(data) {
                add_notify(data.msg,data.status);
            },
        }); 
    });
</script>
@endsection