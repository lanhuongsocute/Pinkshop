<footer class="!bg-[#21262c] shrink-0">
    <div class=" px-5 py-10 xl:!py-10 lg:!py-10 md:!py-10  ">
      <div class="flex flex-wrap mx-[-15px] mt-[-30px] xl:mt-0 lg:mt-0">
        <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
          <div class="widget text_light_color">
            <img class="!mb-4" src="{{$setting->logo}}"   >
            <p class="!mb-4">
              {{$setting->memory}} 
            </p>
           
            <!-- /.social -->
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
          <div class="widget text_light_color">
              <h4 class="widget-title text-white !mb-3"> {{$setting->company_name}}</h4>
              <ul class="contact-list" style="padding-left: 0rem;">
                <li><i  class="text-white uil uil-house-user"></i> {{$setting->address}}
                </li>
                <li><i class="text-white uil uil-phone-alt"></i> Điện thoại: {{$setting->phone}}</li>
                <li><i class="text-white uil uil-envelopes"></i> Email: {{$setting->email}} </li>
                <li><i class="text-white uil uil-book-alt"></i> Mst:  {{$setting->mst}} </li>
                <li><i class="text-white uil uil-headphones"></i> Hotline:  {{$setting->hotline}} </li>
              </ul>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="md:w-4/12 xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
          <div class="widget text_light_color">
            <h4 class="widget-title text-white !mb-3">LIÊN KẾT HỮU ÍCH</h4>
            <ul class="pl-0 list-none   !mb-0">
                <li><a class="text_light_color" href="{{route('front.chinhsach.view','chinh-sach-bao-mat')}}">Chính sách bảo mật</a></li>
                <li><a class="text_light_color" href="{{route('front.chinhsach.view','dieu-khoan-va-quy-dinh')}}">Điều khoản và quy định</a></li>
                <li><a class="text_light_color" href="{{route('front.chinhsach.view','chinh-sach-hoan-tra')}}">Chính sách hoàn trả</a></li>
                <li><a class="text_light_color" href="{{route('front.chinhsach.view','chinh-sach-bao-hanh')}}">Chính sách bảo hành</a></li>
                <li><a class="text_light_color" href="{{route('front.chinhsach.view','chinh-sach-giao-van')}}">Chính sách giao vận</a></li>
                <li><a class="text_light_color"href="{{route('front.chinhsach.view','tai-khoan-cong-ty')}}">Tài khoản công ty</a></li>

            </ul>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="md:w-full xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:mt-0 lg:mt-0 mt-[30px]">
          <div class="widget text_light_color">
            <h4 class="widget-title text-white !mb-3">Thông tin khác</h4>
              <ul class="pl-0 list-none   !mb-0">
                <li><a class="text_light_color" href="{{route('front.profile')}}">Cập nhật hồ sơ</a></li>
                <li><a class="text_light_color" href="{{route('front.shopingcart.view')}}">Giỏ hàng</a></li>
                <li><a class="text_light_color" href="#">Đơn hàng</a></li>
                <li><a class="text_light_color" href="#">Công nợ</a></li>
                <li><a class="text_light_color" href="{{route('front.contact')}}">Liên hệ</a></li>
              </ul>
              <div class="py-5 newsletter-wrapper">
                  <nav class="nav social social-white">
                    <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="{{$setting->facebook}}">
                      <img src="{{asset('frontend/assets/images/icon/facenho.png')}}" class=" "/>
                    </a>
                    <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="{{$setting->shopee}}">
                      <img src="{{asset('frontend/assets/images/icon/shopeenho.png')}}" class=" "/>
                    </a>
                    <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]" href="{{$setting->lazada}}">
                      <img src="{{asset('frontend/assets/images/icon/laznho.png')}}" class=" "/>
                    </a>
                  </nav> 
              </div>
              <!--End mc_embed_signup-->
            </div>
            <!-- /.newsletter-wrapper -->
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
      </div>
      <!--/.row -->
    </div>
    <div class="footer-end">
                            <p> <i class=" uil uil-copyright"></i>2023-24 {{$setting->short_name}}  - đang xây dựng    </p>
                        </div>
    <!-- /.container -->
  </footer>