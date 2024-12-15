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
                                <li class="nav-item"><a  
                                        class="nav-link">Giỏ hàng</a></li>
                                
                                <li class="nav-item"><a  
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
                    <div class="theme-card">
                        <div>
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert"> 
                                <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
                                {{session('error')}}
                                <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                                    <i data-lucide="x" class="w-4 h-4"></i> 
                                </button> 
                            </div>
                            
                            @endif
                        </div>
                        <form class="theme-form" method= "POST" action="{{route('front.register')}}">
                        @csrf    
                        <div class="form-row row">
                                <div class="col-md-6">
                                    <label for="email">Tên đầy đủ</label>
                                    <input type="text" name="full_name" class="form-control" id="full_name"  placeholder="tên đầy đủ"
                                       value="{{old('full_name')}}" required >
                                </div>
                                <div class="col-md-6">
                                    <label for="review">Điện thoại</label>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Số điện thoại"
                                    value="{{old('phone')}}"  required >
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-md-6">
                                    <label for="email">email</label>
                                    <input type="text" name="email"  value="{{old('email')}}"  class="form-control" id="email" placeholder="Email" required >
                                </div>
                                <div class="col-md-6">
                                    <label for="review">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" id="address" value="{{old('address')}}"
                                        placeholder="địa chỉ" required  >
                                 
                            </div>
                            <div class="form-row row">
                                <div class="col-md-6">
                                    <label for="email">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="password" required >
                                </div>
                                <div class="col-md-6">
                                    <label for="review">Giới thiệu bản thân</label>
                                    <input type="text" name="description" class="form-control" id="description"
                                        placeholder="mô tả ngắn"  >
                                </div><button type="submit"  class="btn btn-solid w-auto">Đăng ký</button>
                            </div>
                        </form>
                    </div>
                        
                    </div>
                </div>
                 <!-- right side content -->
            </div>
        </div>
    </section>
    <!--  dashboard section end -->


     
    <!-- modal end -->
    
@endsection
@section('scripts')
@endsection