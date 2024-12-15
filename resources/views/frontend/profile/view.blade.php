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
                                <li class="nav-item"><a  
                                        class="nav-link active">Thông tin tài khoản</a></li>
                                        <li class="nav-item"><a  href="{{route('front.shopingcart.view')}}"
                                        class="nav-link">Giỏ hàng</a></li>
                                        <li class="nav-item"><a  href="{{route('front.profile.addressbook')}}"
                                        class="nav-link">Danh sách địa chỉ</a></li>
                                
                                <li class="nav-item"><a  href="{{route('front.wishlist.view')}}"
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
                                                    <h6>{{$profile->full_name}}</h6>
                                                    <h6>{{$profile->email}}</h6>
                                                    <h6>{{$profile->phone}}</h6>
                                                    <h6>{{$profile->address}}</h6>
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
                                                    {{$profile->description}}
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
                                                <h6>Tên công ty: <span> {{$profile->taxname}}   </span></h6> 
                                                <h6>Mst: <span> {{$profile->taxcode}}</span></h6> 
                                                <h6>Địa chỉ: <span> {{$profile->taxaddress}} </span></h6> 
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
                                                @if ($defaut_setting && isset($invoiceaddress))
                                                <div class="px-20">  
                                                    <h6> {{$invoiceaddress->full_name}} </j6>
                                                    <h6> {{$invoiceaddress->phone}} </j6>
                                                    <h6> {{$invoiceaddress->address}} </j6>
                                                </div>
                                                @else
                                                <a href="javascript:void(0)"
                                                                    data-bs-target="#addInvoiceAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                                @endif
                                               
                                            </div>
                                            <div class="col-sm-6">
                                                <h5>Địa chỉ nhận hóa đơn</h5>
                                                @if ($defaut_setting && isset($shipaddress))
                                                <div  class="px-10">  
                                                    <h6> {{$shipaddress->full_name}} </j6>
                                                    <h6> {{$shipaddress->phone}} </j6>
                                                    <h6> {{$shipaddress->address}} </j6>
                                                </div>
                                                @else
                                                <a href="javascript:void(0)"
                                                                    data-bs-target="#addShipAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                                @endif
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
                        <form class="theme-form" method= "POST" action="{{route('front.profile.updatedescription')}}">
                            @csrf    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Đôi lời về bản thân</label>
                                    <textarea name="description" class="form-control" id="taxname">{{$profile->description}}</textarea>
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
                        <form class="theme-form" method= "POST" action="{{route('front.profile.updatetax')}}">
                            @csrf    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên công ty</label>
                                    <input type="text" name="taxname" class="form-control" id="taxname"  
                                       value="{{$profile->taxname}}" required >
                                </div>
                                <div class="col-md-12   py-10">
                                    <label for="review">MST</label>
                                    <input type="text" name="taxcode" class="form-control" id="taxcode"  
                                    value="{{$profile->taxcode}}"  required >
                                </div>
                                <div class="col-md-12  py-10 ">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="taxaddress" class="form-control" id="taxaddress" value="{{$profile->taxaddress}}"
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
                        <form class="theme-form" method= "POST" action="{{route('front.profile.changepass')}}">
                            @csrf    
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
                        <form class="theme-form" method= "POST" action="{{route('front.profile.updatename')}}">
                            @csrf    
                            <div style="padding-left:10px" class="form-row row">
                                <div class="col-md-12 py-10">
                                    <label for="email">Tên</label>
                                   <input  class="form-control" type="text" name="full_name" value="{{$profile->full_name}}" />
                                </div>
                                <div class="col-md-12 py-10">
                                    <label for="email">Địa chỉ</label>
                                   <input  class="form-control" type="text" name="address" value="{{$profile->address}}" />
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
                        <form class="theme-form" method= "POST" action="{{route('front.profile.addinvoiceadd')}}">
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
    
@endsection
@section('scripts')
 
@endsection