@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
     <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                 
                <div class="col-lg-6">
                    <h3>Đăng nhập</h3>
                    <div class="theme-card">
                        <form method="POST" action="{{ route('front.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" value="{{ old('email') }}" placeholder="Email" name = "email" required="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="review">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="" required="">
                            </div>
                            <div class="form-group">
                               @if (Route::has('password.request'))
                                <a class=" btn-link" href="{{ route('password.request') }}">
                                  {{ __('Quên mật khẩu?') }}
                                </a>
                              @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-solid">Đăng nhập</button>
                            </div>
                            <input type='hidden' name='plink' value='{{$plink}}'/>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 right-login">
                    <h3>New Customer</h3>
                    <div class="theme-card authentication-right">
                        <h6 class="title-font">Tạo tài khoản</h6>
                        <p>Hãy đăng ký tài khoản để có thể thõa thích mua sắm trên website chúng tôi. Bạn có thể tham khảo qua chính sachs bảo mật, 
                            điều khoản và các quy định liên quan của chúng tôi.</p><a href="{{route('front.register')}}"
                            class="btn btn-solid">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
    
@endsection
@section('scripts')
@endsection