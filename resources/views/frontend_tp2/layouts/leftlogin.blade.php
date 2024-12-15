@if (!$user)
  

  <div style="" class=" ">
    <div class=" w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto !mt-[-0rem]">
        <div class="card">
            <div class="card-body !p-12 !text-center">
                 
                <p class="lead text-[0.9rem] font-medium !leading-[1.65] !mb-6 text-left">Hãy điền thông tin email và mật khẩu để đăng nhập hệ thống.</p>
              
                <form method="POST" action="{{ route('front.login') }}" class="text-left !mb-3">
                  @csrf
                  <div class="form-floating !relative mb-4">
                    <input type="email" name="email" class="form-control px-4 py-[0.6rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25] block w-full text-[12px] font-medium text-[#60697b] appearance-none bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] motion-reduce:transition-none focus:text-[#60697b] focus:bg-[#fefefe] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:border-[#9fbcf0] disabled:bg-[#aab0bc] disabled:opacity-100 file:mt-[-0.6rem] file:mr-[-1rem] file:mb-[-0.6rem] file:ml-[-1rem] file:text-[#60697b] file:bg-[#fefefe] file:pointer-events-none file:transition-all file:duration-[0.2s] file:ease-in-out file:px-4 file:py-[0.6rem] file:rounded-none file:border-inherit file:border-solid file:border-0 motion-reduce:file:transition-none focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0" placeholder="Email" id="loginEmail">
                    <label class=" text-[#959ca9] text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 inline-block" for="loginEmail">Email</label>
                  </div>
                  <div class="form-floating !relative password-field mb-4">
                    <input type="password" name= "password" class="form-control px-4 py-[0.6rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25] block w-full text-[12px] font-medium text-[#60697b] appearance-none bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] motion-reduce:transition-none focus:text-[#60697b] focus:bg-[#fefefe] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:border-[#9fbcf0] disabled:bg-[#aab0bc] disabled:opacity-100 file:mt-[-0.6rem] file:mr-[-1rem] file:mb-[-0.6rem] file:ml-[-1rem] file:text-[#60697b] file:bg-[#fefefe] file:pointer-events-none file:transition-all file:duration-[0.2s] file:ease-in-out file:px-4 file:py-[0.6rem] file:rounded-none file:border-inherit file:border-solid file:border-0 motion-reduce:file:transition-none focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0" placeholder="Password" id="loginPassword">
                    <span class="password-toggle absolute -translate-y-2/4 cursor-pointer text-[0.9rem] text-[#959ca9] right-3 top-2/4"><i class="uil uil-eye"></i></span>
                    <label class=" text-[#959ca9] text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 inline-block" for="loginPassword">Mật khẩu</label>
                  </div>
                  <input type='hidden' name='plink' value='{{isset($plink)?$plink:""}}'/>
                  <button type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-login w-full mb-2">Đăng nhập</button>
                </form>
                <!-- /form -->
                <p class="!mb-1">  @if (Route::has('password.request'))
                                <a class=" btn-link" href="{{ route('password.request') }}">
                                  {{ __('Quên mật khẩu?') }}
                                </a>
                              @endif</p>
                <p class="!mb-0">Bạn chưa có tài khoản? <a href="{{route('front.register')}}" class="hover">Đăng ký</a></p>
                <div class="divider-icon !my-4"> </div>
                <nav class="nav social justify-center !text-center">
                  <a href="#" class="btn btn-circle btn-sm btn-google hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)] !text-white !bg-[#e44134] !w-[1.8rem] !h-[1.8rem] !text-[0.8rem] !inline-flex !items-center !justify-center !leading-none !mx-[0.35rem] !my-0 !p-0 !rounded-[100%] !border-transparent"><i class="uil uil-google text-[0.85rem] before:content-['\eb50']"></i></a>
                  <a href="#" class="btn btn-circle btn-sm btn-facebook-f hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)] !text-white !bg-[#4470cf] !w-[1.8rem] !h-[1.8rem] !text-[0.8rem] !inline-flex !items-center !justify-center !leading-none !mx-[0.35rem] !my-0 !p-0 !rounded-[100%] !border-transparent"><i class="uil uil-facebook-f text-[0.85rem] before:content-['\eae2']"></i></a>
                </nav>
                <!--/.social -->
            </div>
              <!--/.card-body -->
        </div>
            <!--/.card -->
    </div>
          <!-- /column -->
</div>

@endif
