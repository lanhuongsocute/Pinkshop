<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Midone - HTML Admin Template" class="w-6" src="{{asset('backend/assets/dist/images/logo.svg')}}">
        </a>
        <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>
    <div class="scrollable">
        <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
                
               
<ul>
        <li>
            <a href="{{route('admin')}}" class="menu menu{{$active_menu=='dashboard'?'--active':''}}">
                <div class="menu__icon"> <i data-lucide="home"></i> </div>
                <div class="menu__title"> Dashboard </div>
            </a>
        </li> 
       <!-- Blog -->
        <li>
          <a href="javascript:;.html" class="menu menu{{($active_menu=='tag_list'|| $active_menu=='tag_add'||$active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
              <div class="menu__icon"> <i data-lucide="align-center"></i> </div>
              <div class="menu__title">
                  Bài viết
                  <div class="menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='tag_list'|| $active_menu=='tag_add'||$active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'menu__sub-open':''}}">
              <li>
                  <a href="{{route('blog.index')}}" class="menu {{$active_menu=='blog_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="compass"></i> </div>
                      <div class="menu__title">Danh sách bài viết </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('blog.create')}}" class="menu {{$active_menu=='blog_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm bài viết</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('tag.index')}}" class="menu {{$active_menu=='tag_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="anchor"></i> </div>
                      <div class="menu__title">Tags </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('blogcategory.index')}}" class="menu {{$active_menu=='blogcat_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="hash"></i> </div>
                      <div class="menu__title">Danh mục bài viết </div>
                  </a>
              </li>
        </ul>
    </li>
    <li>
            <a href="{{route('comment.index')}}" class="menu {{$active_menu=='comment_list'||$active_menu=='comment_add'?'menu--active':''}}">
                <div class="menu__icon"> <i data-lucide="package"></i> </div>
                <div class="menu__title"> Bình luận</div>
            </a>
        
      </li> 
    <!--Quan ly ban hang  -->
    <li>
        <?php
            $reg_totals = \DB::select("select count(id) as tong from orders where status = 'pending'");
            $reg_total = $reg_totals[0]->tong;
        ?>
          <a href="javascript:;" class="menu  class="menu {{($active_menu=='or_list' || $active_menu=='customer_list'|| $active_menu=='wo_list'|| $active_menu=='wo_add'|| $active_menu=='delivery_list'    )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="shopping-cart"></i> </div>
              <div class="menu__title">
                  Quản lý bán hàng
                  @if ($reg_total > 0)
                            <div style="margin-top:-0.5rem"> &nbsp;
                                <span class="text-xs px-1 rounded-full bg-success text-white mr-1">{{$reg_total}}</span>
                            </div>
                        @endif

                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='or_list'|| $active_menu=='customer_list'|| $active_menu=='wo_list'||$active_menu=='wo_add'||$active_menu=='delivery_list' )?'menu__sub-open':''}}">
                <li>
                  <a href="{{route('warehouseout.index')}}" class="menu {{$active_menu=='wo_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                      <div class="menu__title">Ds bán hàng
                       
                      </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehouseout.create')}}" class="menu {{$active_menu=='wo_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm bán hàng</div>
                  </a>
              </li>
             
              <li>
                  <a href="{{route('order.index')}}" class="menu {{$active_menu=='or_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="shopping-cart"></i> </div>
                      <div class="menu__title"> Đơn đặt hàng
                        @if ($reg_total > 0)
                        <div style="margin-top:-0.5rem"> &nbsp;
                            <span class="text-xs px-1 rounded-full bg-success text-white mr-1">{{$reg_total}}</span>
                        </div>
                        @endif

                      </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('customer.index')}}" class="menu {{$active_menu=='customer_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="menu__title">Ds khách hàng</div>
                  </a>
              </li>
                <li>
                  <a href="{{route('delivery.index')}}" class="menu {{$active_menu=='delivery_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="truck"></i> </div>
                      <div class="menu__title">Ds nhà vận chuyển</div>
                  </a>
              </li>
             
          </ul>
      </li>
        <!--Quan ly kho menu  -->
        <li>
          <a href="javascript:;" class="menu  class="menu {{($active_menu=='ic_list'|| $active_menu=='wtd_list'||$active_menu=='wtp_list'||   $active_menu=='des_inv' || $active_menu=='wm_trans' || $active_menu=='wi_trans'|| $active_menu=='sup_list'|| $active_menu=='i_list'|| $active_menu=='bi_list'|| $active_menu =='wh_add'|| $active_menu=='wh_list'    )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="database"></i> </div>
              <div class="menu__title">
                  Quản lý kho 
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='ic_list'|| $active_menu=='wtd_list'||$active_menu=='wtp_list' ||   $active_menu=='des_inv' || $active_menu=='wm_trans'|| $active_menu=='wi_trans'|| $active_menu=='sup_list'||$active_menu=='wi_add'||$active_menu=='wi_list'||$active_menu=='i_list'||$active_menu=='bi_list'|| $active_menu =='wh_add'|| $active_menu=='wh_list' )?'menu__sub-open':''}}">
          <li>
                  <a href="{{route('warehousein.index')}}" class="menu {{$active_menu=='wi_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="corner-up-right"></i> </div>
                      <div class="menu__title"> Danh sách nhập kho</div>
                  </a>
              </li>      
            <li>
                  <a href="{{route('warehousein.create')}}" class="menu {{$active_menu=='wi_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Nhập kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('combocreation.index')}}" class="menu {{$active_menu=='combo_list'?'menu--active':''}}">
                      <div class=" menu__icon"> <i data-lucide="figma"></i> </div>
                      <div class=" menu__title">Danh sách tạo thành phẩm</div>
                  </a>
                </li>
              <li>
                  <a href="{{route('inventory.index')}}" class="menu {{$active_menu=='i_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="database"></i> </div>
                      <div class="menu__title"> Tồn kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('inventorycheck.index')}}" class="menu {{$active_menu=='ic_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="pen-tool"></i> </div>
                      <div class="menu__title"> Kiểm kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetransfer.index')}}" class="menu {{$active_menu=='wi_trans'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="git-branch"></i> </div>
                      <div class="menu__title"> Chuyển kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetomaintain.index')}}" class="menu {{$active_menu=='wm_trans'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="menu__title"> Chuyển kho bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetoproperty.index')}}" class="menu {{$active_menu=='wtp_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="menu__title"> Chuyển kho sử dụng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetodestroy.index')}}" class="menu {{$active_menu=='wtd_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="menu__title"> Chuyển kho hủy</div>
                  </a>
              </li>
              
              <li>
                  <a href="{{route('supplier.index')}}" class="menu {{$active_menu=='sup_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="menu__title"> Danh sách nhà cung cấp</div>
                  </a>
              </li>
                <li>
                  <a href="{{route('warehouse.index')}}" class="menu {{$active_menu=='wh_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="figma"></i> </div>
                      <div class="menu__title">Danh sách kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehouse.create')}}" class="menu {{$active_menu=='wh_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('binventory.index')}}" class="menu {{$active_menu=='bi_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="play"></i> </div>
                      <div class="menu__title"> Tồn kho đầu kì</div>
                  </a>
              </li>
             
             
              <li>
                  <a href="{{route('inventorydestroy.index')}}" class="menu {{$active_menu=='des_inv'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="trash-2"></i> </div>
                      <div class="menu__title">DS kho hủy</div>
                  </a>
              </li>
              
          </ul>
      </li>
 <!--Quan ly tien  -->
        <li>
          <a href="javascript:;" class="menu  class="menu {{($active_menu=='freetranstype_add'||$active_menu=='freetranstype_list'|| $active_menu=='ft_list'|| $active_menu=='bt_list'||$active_menu=='bank_list'|| $active_menu=='bank_add'    )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
              <div class="menu__title">
                  Quản lý quỹ 
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='freetranstype_add'||$active_menu=='freetranstype_list'||  $active_menu=='ft_list'|| $active_menu=='bt_list'|| $active_menu=='bank_list'|| $active_menu=='bank_add')?'menu__sub-open':''}}">
              <li>
                  <a href="{{route('bankaccount.index')}}" class="menu {{$active_menu=='bank_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="briefcase"></i> </div>
                      <div class="menu__title">Danh sách tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('bankaccount.create')}}" class="menu {{$active_menu=='bank_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('bankaccount.viewtrans')}}" class="menu {{$active_menu=='bt_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="repeat"></i> </div>
                      <div class="menu__title"> Ds giao dịch tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('freetranstype.index')}}" class="menu {{$active_menu=='freetranstype_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="book"></i> </div>
                      <div class="menu__title"> Loại thu chi</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('freetransaction.index')}}" class="menu {{$active_menu=='ft_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="wind"></i> </div>
                      <div class="menu__title"> Ds phiếu thu chi</div>
                  </a>
              </li>
          </ul>
      </li>
      <!-- product category menu -->
      <li>
          <a href="javascript:;" class="menu {{($active_menu =='pro_add'|| $active_menu=='pro_list' || $active_menu =='brand_list' || $active_menu == 'brand_list' || $active_menu=='cat_add'|| $active_menu=='cat_list')?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="box"></i> </div>
              <div class="menu__title">
                  Hàng hóa 
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
            <ul class="{{($active_menu =='pro_add'|| $active_menu=='pro_list' || $active_menu =='cat_add'|| $active_menu=='cat_list' || $active_menu =='brand_list' || $active_menu == 'brand_list')?'menu__sub-open':''}}">
              <li>
                  <a href="{{route('product.index')}}" class="menu {{$active_menu=='pro_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="list"></i> </div>
                      <div class="menu__title">Danh sách hàng hóa</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('product.create')}}" class="menu {{$active_menu=='pro_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm hàng hóa</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('category.index')}}" class="menu {{$active_menu=='cat_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="archive"></i> </div>
                      <div class="menu__title"> Danh sách danh mục </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('category.create')}}" class="menu {{$active_menu=='cat_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm danh mục </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('brand.index')}}" class="menu {{$active_menu=='brand_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="package"></i> </div>
                      <div class="menu__title"> Ds nhà sản xuất </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('brand.create')}}" class="menu {{$active_menu=='brand_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm nhà sản xuất </div>
                  </a>
              </li>
          </ul>
      </li>
      <!-- Nguoi dung menu  -->
      <li>
          <a href="javascript:;" class="menu  class="menu {{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list'  )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="user"></i> </div>
              <div class="menu__title">
                  Người dùng 
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list')?'menu__sub-open':''}}">
              <li>
                  <a href="{{route('user.index')}}" class="menu {{$active_menu=='ctm_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="menu__title">Danh sách người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('user.create')}}" class="menu {{$active_menu=='ctm_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('ugroup.index')}}" class="menu {{$active_menu=='ugroup_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="circle"></i> </div>
                      <div class="menu__title">Ds nhóm người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('ugroup.create')}}" class="menu {{$active_menu=='ugroup_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm nhóm người dùng</div>
                  </a>
              </li>
          </ul>
      </li>
    
       <!--Quan ly tai san menu  -->
       <li>
          <a href="javascript:;" class="menu  class="menu {{($active_menu=='ptm_list' || $active_menu=='ptd_list'|| $active_menu=='ptw_list'  || $active_menu=='pro_inv'   )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="crosshair"></i> </div>
              <div class="menu__title">
                  Quản lý tài sản
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='ptm_list'|| $active_menu=='ptw_list'||$active_menu=='ptd_list'||  $active_menu=='pro_inv'     )?'menu__sub-open':''}}">
              
              <li>
                  <a href="{{route('propertytodestroy.index')}}" class="menu {{$active_menu=='wtd_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="trash"></i> </div>
                      <div class="menu__title"> Chuyển kho hủy</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('propertytowarehouse.index')}}" class="menu {{$active_menu=='ptw_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="cpu"></i> </div>
                      <div class="menu__title">  chuyển kho bán</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('propertytomaintain.index')}}" class="menu {{$active_menu=='ptm_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="menu__title">  chuyển kho bảo hành</div>
                  </a>
              </li>
             <li>
                  <a href="{{route('inventoryproperty.index')}}" class="menu {{$active_menu=='pro_inv'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="slack"></i> </div>
                      <div class="menu__title">DS kho sử dụng</div>
                  </a>
              </li>
          </ul>
      </li>
      
    
        <!--Quan ly bao hanh -->
        <li>
          <a href="javascript:;" class="menu  class="menu {{($active_menu=='mtp_list' || $active_menu=='mtd_list' || $active_menu=='mtw_list' || $active_menu=='mb_list' || $active_menu=='ms_list'|| $active_menu=='mainsent_list'||  $active_menu=='mainin_list'||  $active_menu=='main_inv'    )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="pie-chart"></i> </div>
              <div class="menu__title">
                  Quản lý bảo hành
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='mtp_list'|| $active_menu=='mtd_list'|| $active_menu=='mtw_list' || $active_menu=='mb_list' || $active_menu=='ms_list'|| $active_menu=='mainsent_list'|| $active_menu=='mainin_list'||  $active_menu=='main_inv' )?'menu__sub-open':''}}">
                <li>
                  <a href="{{route('inventorymaintenance.index')}}" class="menu {{$active_menu=='main_inv'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="codepen"></i> </div>
                      <div class="menu__title">Tồn kho bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainin.index')}}" class="menu {{$active_menu=='mainin_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="layers"></i> </div>
                      <div class="menu__title">DS nhận bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainsent.index')}}" class="menu {{$active_menu=='ms_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="briefcase"></i> </div>
                      <div class="menu__title">DS gửi bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainback.index')}}" class="menu {{$active_menu=='mb_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="framer"></i> </div>
                      <div class="menu__title">DS trả bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintowarehouse.index')}}" class="menu {{$active_menu=='mtw_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="cpu"></i> </div>
                      <div class="menu__title">Chuyển kho bán</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintodestroy.index')}}" class="menu {{$active_menu=='mtd_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="trash-2"></i> </div>
                      <div class="menu__title">Chuyển kho hủy</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintoproperty.index')}}" class="menu {{$active_menu=='mtp_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="slack"></i> </div>
                      <div class="menu__title">Chuyển kho sử dụng</div>
                  </a>
              </li>
          </ul>
      </li>
      
     <!--Bao cao -->
     <li>
          <a href="javascript:;" class="menu " class="menu {{($active_menu=='report_list'     )?'menu--active':''}}">
              <div class="menu__icon"> <i data-lucide="book-open"></i> </div>
              <div class="menu__title">
                  Báo cáo
                  <div class="menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='report_list'     )?'menu__sub-open':''}}">
              
              <li>
                  <a href="{{route('report.money')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title"> Lợi nhuận</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.thuchi')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title"> Thu chi</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.congnokhach')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title"> Công nợ khách</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.congnosup')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title"> Công nợ nhà cung cấp</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.sanpham')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title"> Sản phẩm</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.quy')}}" class="menu {{$active_menu=='report_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="menu__title">Quỹ</div>
                  </a>
              </li>
          </ul>
    </li>
   
    <!-- setting menu -->
    <li>
        <a href="javascript:;.html" class="menu menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
              <div class="menu__icon"> <i data-lucide="settings"></i> </div>
              <div class="menu__title">
                  Cài đặt
                  <div class="menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='banner_add'|| $active_menu=='banner_list')?'menu__sub-open':''}}">
              <li>
                  <a href="{{route('banner.index')}}" class="menu {{$active_menu=='banner_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="image"></i> </div>
                      <div class="menu__title">Danh sách banner </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('banner.create')}}" class="menu {{$active_menu=='banner_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Thêm banner</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('role.index',1)}}" class="menu {{$active_menu=='role_list'||$active_menu=='role_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="octagon"></i> </div>
                      <div class="menu__title"> Roles</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('cmdfunction.index',1)}}" class="menu {{$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="moon"></i> </div>
                      <div class="menu__title"> Chức năng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('setting.edit',1)}}" class="menu {{$active_menu=='setting_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="key"></i> </div>
                      <div class="menu__title"> Thông tin công ty</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('log.index')}}" class="menu {{$active_menu=='log_list'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Nhật ký</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('kiot.index')}}" class="menu {{$active_menu=='kiot'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title"> Kiot</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('setting.update_data')}}" class="menu {{$active_menu=='updatedata'?'menu--active':''}}">
                      <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="menu__title">Cập nhật hệ thống</div>
                  </a>
              </li>
          </ul>
    </li>
</ul>
    </div>
</div>