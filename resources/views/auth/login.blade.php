<!DOCTYPE html>
<?php
    $detail = \App\Models\SettingDetail::find(1);
?>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
      @include('backend.layouts.head')
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert"> 
                        <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
                        {{session('error')}}
                        <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                            <i data-lucide="x" class="w-4 h-4"></i> 
                        </button> 
                    </div>
                    
                    @endif
                
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <span class="text-white text-lg ml-3">{{$detail->company_name}} </span> 
                    </a>
                    <div class="my-auto">
                        <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{$detail->logo}}">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Quản lý bán hàng <br/>
                            
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400"> </div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                      <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                          <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                              Đăng nhập
                          </h2>
                          <div class="intro-x mt-2 text-slate-400 xl:hidden text-center"> </div>
                          <div class="intro-x mt-8">
                            <div>
                              <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                <input id="email" type="email" 
                                class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" 
                                required autocomplete="email" 
                                autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <br/>
                                <label for="password" class="col-md-4 col-form-label  text-md-end">{{ __('Mật khẩu') }}</label>
                                <input id="password" type="password" 
                                class="intro-x login__input form-control py-3 px-4 block 
                                 @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                               
                            </div>
                          </div>
                          <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                               
                              @if (Route::has('password.request'))
                                <a class=" btn-link" href="{{ route('password.request') }}">
                                  {{ __('Quên mật khẩu?') }}
                                </a>
                              @endif
                          </div>
                          <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                              <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Đăng nhập</button>
                              <button class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Đăng ký</button>
                          </div>
                          <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left"> 
                            Đăng ký tài khoản bạn sẽ đồng ý các điều khoản sau 
                            <a class="text-primary dark:text-slate-200" href="">Điều khoản và quy định</a> 
                            & <a class="text-primary dark:text-slate-200" href="">Quy định bảo mật</a> 
                          </div>
                      </div>
                  </div>
                </form>
                <!-- END: Login Form -->
            </div>
        </div>
        @include('backend.layouts.footer')
    </body>
</html>