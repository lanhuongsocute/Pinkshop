@if (env('DEMOAPP')==1)
  <div class="modal fade modal-popup" id="modal-02" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content !text-center">
          <div class="relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] pl-[2.5rem]">
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="flex flex-wrap mx-[-15px]">
              <div class="xl:w-10/12 lg:w-10/12 md:w-10/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:!ml-[8.33333333%] lg:!ml-[8.33333333%] md:!ml-[8.33333333%]">
                <figure class="!mb-6"><img src="{{$detail->logo}}"   alt="image"></figure>
              </div>
              <!-- /column -->
            </div>
            <!-- /.row -->
            <h3>Đây là demo hệ thống phần mềm quản lý và website bán hàng itcctv-soft</h3>
            <p class="!mb-6">Các thông tin sản phẩm và hình thức đặt hàng chỉ làm ví dụ. Kính mong quý khách không đưa các thông tin cá nhân trong quá trình sử dụng thử sản phẩm</p>
            <div class="newsletter-wrapper">
              <div class="flex flex-wrap mx-[-15px]">
                <div class="xl:w-10/12 lg:w-10/12 md:w-10/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:!ml-[8.33333333%] lg:!ml-[8.33333333%] md:!ml-[8.33333333%]">
                  <!-- Begin Mailchimp Signup Form -->
                  <div id="mc_embed_signup">
                     Để đăng nhập vào admin xin vào link sau :
                        <a href="https://demo1.itcctv-soft.com/admin"> https://demo1.itcctv-soft.com/admin </a>
                        với tài khoản demoadmin@gmail.com/12345678 <br/>
                    Để đăng nhập vào tài khoản khách hàng xin sử dụng tài khoản: demo1@gmail.com/12345678
                  </div>
                  <form action="{{route('front.theme.update')}}" method = "post">
                            @csrf
                            <div class="  w-full flex-[0_0_auto] px-[15px] max-w-full">
                            <h3> Chọn theme </h3>
                              <?php
                              $themes = \App\Models\Themesetting::get();
                              ?>
                                <select class=" relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" name="theme_id">
                                    @foreach ($themes as $theme )
                                      <option value="{{$theme->id}}"> {{$theme->title}} </option>
                                      
                                    @endforeach
                                </select>
                              </div>
                              <button type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-send !mb-3 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]" > Chọn </button>
                  </form>
                  <!--End mc_embed_signup-->
                </div>
                <!-- /.newsletter-wrapper -->
              </div>
              <!-- /column -->
            </div>
            <!-- /.row -->
          </div>
          <!--/.modal-body -->
        </div>
        <!--/.modal-content -->
      </div>
      <!--/.modal-dialog -->
    </div>
@endif