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
                                    <a  class="nav-link active" href="{{route('front.profile.addressbook')}}"
                                        >Danh sách địa chỉ</a></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('front.wishlist.view')}}">SP Yêu thích</a></li>
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
                                <a href="javascript:void(0)"
                                                                    data-bs-target="#addAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                || <a href="javascript:void(0)"
                                                                    data-bs-target="#changeInvoiceAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thay đổi địa chỉ hóa đơn mặc định</a>           
                                || <a href="javascript:void(0)"
                                                                    data-bs-target="#changeShipAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thay đổi địa chỉ giao hàng mặc định</a>           
                            
                                <div class="box-account box-info">
                                @foreach ($addressbooks as $adbook)
                                    <div class="row" style="padding:10px; border-bottom:1px dotted grey">
                                        <div class="col-lg-9">
                                            <h6>{{$adbook->full_name}} </h6>
                                            <h6>{{$adbook->phone }}</h6>
                                            <h6>{{$adbook->address }}</h6>
                                        </div>
                                        <div class="col-lg-3">
                                            <a href="{{route('front.address.delete',$adbook->id)}}"> Xóa  </a>
                                            
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
    <!-- Modal start -->
    <div class="modal logout-modal fade" id="addAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method= "POST" action="{{route('front.profile.addshipadd')}}">
                            @csrf    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên đầy đủ</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name"  
                                       value="{{old('full_name')}}" required >
                                </div>
                                <div class="col-md-12   py-10">
                                    <label for="review">Điện thoại</label>
                                    <input type="text" name="phone" class="form-control" id="phone"  
                                    value="{{old('phone')}}"  required >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" id="address" value="{{old('address')}}"
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
    <!-- modal end -->
  <!-- modal invoice end -->
<!-- Select ship address start -->
    <div class="modal logout-modal fade" id="changeShipAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Chọn địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                             
                            <div style="padding-left:10px" class="form-row row">
                                @foreach ($addressbooks as $address )
                                <div class="row col-sl-12 py-10" style="border-bottom: 1px solid gray">
                                    <div class="col-lg-2  align-self-center ">
                                        <input data-bs-dismiss="modal" type="radio" data-name="{{$address->full_name}}" data-phone="{{$address->phone}}" data-address=" {{$address->address}}" class="ship_ra" name="ship_id" value="{{$address->id}}">
                                    </div>
                                    <div class="col-lg-10    ">  
                                        <h6> Tên: <span> {{$address->full_name}}</span> </h6>
                                        <h6> Điện thoại: <span> {{$address->phone}}</span> </h6>
                                        <h6> Địa chỉ: <span> {{$address->address}}</span> </h6>

                                    </div> 
                                </div>
                                @endforeach  
                            </div>
                            <div class="col-md-12  py-10 ">
                                    <button id="btnAddInv"  data-bs-dismiss="modal" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                    </div>
            </div>
        </div>
    </div>

    <!-- Select invoice address start -->
<div class="modal logout-modal fade" id="changeInvoiceAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Chọn địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                             
                            <div style="padding-left:10px" class="form-row row">
                                @foreach ($addressbooks as $address )
                                <div class="row col-sl-12 py-10" style="border-bottom: 1px solid gray">
                                    <div class="col-lg-2  align-self-center ">
                                        <input data-bs-dismiss="modal" type="radio" data-name="{{$address->full_name}}" data-phone="{{$address->phone}}" data-address=" {{$address->address}}" class="invoice_ra" name="invoice_id" value="{{$address->id}}">
                                    </div>
                                    <div class="col-lg-10    ">  
                                        <h6> Tên: <span> {{$address->full_name}}</span> </h6>
                                        <h6> Điện thoại: <span> {{$address->phone}}</span> </h6>
                                        <h6> Địa chỉ: <span> {{$address->address}}</span> </h6>

                                    </div> 
                                </div>
                                @endforeach  
                            </div>
                            <div class="col-md-12  py-10 ">
                                    <button id="btnAddInv"  data-bs-dismiss="modal" class="btn btn-solid w-auto">Lưu</button>
                                </div>
                    </div>
            </div>
        </div>
</div>   
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