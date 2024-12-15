<nav class="side-nav">
   
<ul>
        <li>
            <a href="{{route('admin')}}" class="side-menu side-menu{{$active_menu=='dashboard'?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li> 
       <!-- Blog -->
        <li>
          <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='tag_list'|| $active_menu=='tag_add'||$active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
              <div class="side-menu__title">
                  Bài viết
                  <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='tag_list'|| $active_menu=='tag_add'||$active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'side-menu__sub-open':''}}">
              <li>
                  <a href="{{route('blog.index')}}" class="side-menu {{$active_menu=='blog_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                      <div class="side-menu__title">Danh sách bài viết </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('blog.create')}}" class="side-menu {{$active_menu=='blog_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm bài viết</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('tag.index')}}" class="side-menu {{$active_menu=='tag_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="anchor"></i> </div>
                      <div class="side-menu__title">Tags </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('blogcategory.index')}}" class="side-menu {{$active_menu=='blogcat_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="hash"></i> </div>
                      <div class="side-menu__title">Danh mục bài viết </div>
                  </a>
              </li>
        </ul>
    </li>
    <li>
            <a href="{{route('comment.index')}}" class="side-menu {{$active_menu=='comment_list'||$active_menu=='comment_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="package"></i> </div>
                <div class="side-menu__title"> Bình luận</div>
            </a>
        
      </li> 
      
    <!--Quan ly ban hang  -->
    <li>
        <?php
            $reg_totals = \DB::select("select count(id) as tong from orders where status = 'pending'");
            $reg_total = $reg_totals[0]->tong;
        ?>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu=='or_list' || $active_menu=='customer_list'|| $active_menu=='wo_list'|| $active_menu=='wo_add'|| $active_menu=='delivery_list'    )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="shopping-cart"></i> </div>
              <div class="side-menu__title">
                  Quản lý bán hàng
                  @if ($reg_total > 0)
                            <div style="margin-top:-0.5rem"> &nbsp;
                                <span class="text-xs px-1 rounded-full bg-success text-white mr-1">{{$reg_total}}</span>
                            </div>
                        @endif

                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='or_list'|| $active_menu=='customer_list'|| $active_menu=='wo_list'||$active_menu=='wo_add'||$active_menu=='delivery_list' )?'side-menu__sub-open':''}}">
                <li>
                  <a href="{{route('warehouseout.index')}}" class="side-menu {{$active_menu=='wo_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                      <div class="side-menu__title">Ds bán hàng
                       
                      </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehouseout.create')}}" class="side-menu {{$active_menu=='wo_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm bán hàng</div>
                  </a>
              </li>
             
              <li>
                  <a href="{{route('order.index')}}" class="side-menu {{$active_menu=='or_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="shopping-cart"></i> </div>
                      <div class="side-menu__title"> Đơn đặt hàng
                        @if ($reg_total > 0)
                        <div style="margin-top:-0.5rem"> &nbsp;
                            <span class="text-xs px-1 rounded-full bg-success text-white mr-1">{{$reg_total}}</span>
                        </div>
                        @endif

                      </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('customer.index')}}" class="side-menu {{$active_menu=='customer_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="side-menu__title">Ds khách hàng</div>
                  </a>
              </li>
                <li>
                  <a href="{{route('delivery.index')}}" class="side-menu {{$active_menu=='delivery_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="truck"></i> </div>
                      <div class="side-menu__title">Ds nhà vận chuyển</div>
                  </a>
              </li>
             
          </ul>
      </li>
        <!--Quan ly kho menu  -->
        <li>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu=='combo_list'|| $active_menu=='ic_list'|| $active_menu=='wtd_list'||$active_menu=='wtp_list'||   $active_menu=='des_inv' || $active_menu=='wm_trans' || $active_menu=='wi_trans'|| $active_menu=='sup_list'|| $active_menu=='i_list'|| $active_menu=='bi_list'|| $active_menu =='wh_add'|| $active_menu=='wh_list'    )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="database"></i> </div>
              <div class="side-menu__title">
                  Quản lý kho 
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='combo_list'||$active_menu=='ic_list'|| $active_menu=='wtd_list'||$active_menu=='wtp_list' ||   $active_menu=='des_inv' || $active_menu=='wm_trans'|| $active_menu=='wi_trans'|| $active_menu=='sup_list'||$active_menu=='wi_add'||$active_menu=='wi_list'||$active_menu=='i_list'||$active_menu=='bi_list'|| $active_menu =='wh_add'|| $active_menu=='wh_list' )?'side-menu__sub-open':''}}">
                <li>
                  <a href="{{route('warehousein.index')}}" class="side-menu {{$active_menu=='wi_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="corner-up-right"></i> </div>
                      <div class="side-menu__title"> Danh sách nhập kho</div>
                  </a>
                </li>      
                <li>
                  <a href="{{route('warehousein.create')}}" class="side-menu {{$active_menu=='wi_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Nhập kho</div>
                  </a>
                </li>
                <li>
                  <a href="{{route('combocreation.index')}}" class="side-menu {{$active_menu=='combo_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="figma"></i> </div>
                      <div class="side-menu__title">Danh sách tạo thành phẩm</div>
                  </a>
                </li>
                <li>
                  <a href="{{route('inventory.index')}}" class="side-menu {{$active_menu=='i_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="database"></i> </div>
                      <div class="side-menu__title"> Tồn kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('inventorycheck.index')}}" class="side-menu {{$active_menu=='ic_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="pen-tool"></i> </div>
                      <div class="side-menu__title"> Kiểm kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetransfer.index')}}" class="side-menu {{$active_menu=='wi_trans'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="git-branch"></i> </div>
                      <div class="side-menu__title"> Chuyển kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetomaintain.index')}}" class="side-menu {{$active_menu=='wm_trans'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="side-menu__title"> Chuyển kho bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetoproperty.index')}}" class="side-menu {{$active_menu=='wtp_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="side-menu__title"> Chuyển kho sử dụng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehousetodestroy.index')}}" class="side-menu {{$active_menu=='wtd_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="side-menu__title"> Chuyển kho hủy</div>
                  </a>
              </li>
              
              <li>
                  <a href="{{route('supplier.index')}}" class="side-menu {{$active_menu=='sup_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="side-menu__title"> Danh sách nhà cung cấp</div>
                  </a>
              </li>
                <li>
                  <a href="{{route('warehouse.index')}}" class="side-menu {{$active_menu=='wh_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="figma"></i> </div>
                      <div class="side-menu__title">Danh sách kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('warehouse.create')}}" class="side-menu {{$active_menu=='wh_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm kho</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('binventory.index')}}" class="side-menu {{$active_menu=='bi_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="play"></i> </div>
                      <div class="side-menu__title"> Tồn kho đầu kì</div>
                  </a>
              </li>
             
             
              <li>
                  <a href="{{route('inventorydestroy.index')}}" class="side-menu {{$active_menu=='des_inv'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="trash-2"></i> </div>
                      <div class="side-menu__title">DS kho hủy</div>
                  </a>
              </li>
              
          </ul>
      </li>
 <!--Quan ly tien  -->
        <li>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu=='bbank'||$active_menu=='freetranstype_add'||$active_menu=='freetranstype_list'|| $active_menu=='ft_list'|| $active_menu=='bt_list'||$active_menu=='bank_list'|| $active_menu=='bank_add'    )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
              <div class="side-menu__title">
                  Quản lý quỹ 
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='bbank'||$active_menu=='freetranstype_add'||$active_menu=='freetranstype_list'||  $active_menu=='ft_list'|| $active_menu=='bt_list'|| $active_menu=='bank_list'|| $active_menu=='bank_add')?'side-menu__sub-open':''}}">
              <li>
                  <a href="{{route('bankaccount.index')}}" class="side-menu {{$active_menu=='bank_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                      <div class="side-menu__title">Danh sách tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('bankaccount.create')}}" class="side-menu {{$active_menu=='bank_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('bbanktrans.index')}}" class="side-menu {{$active_menu=='bbank'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="archive"></i> </div>
                      <div class="side-menu__title"> Nhập quỹ đầu kỳ</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('bankaccount.viewtrans')}}" class="side-menu {{$active_menu=='bt_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="repeat"></i> </div>
                      <div class="side-menu__title"> Ds giao dịch tài khoản</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('freetranstype.index')}}" class="side-menu {{$active_menu=='freetranstype_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                      <div class="side-menu__title"> Loại thu chi</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('freetransaction.index')}}" class="side-menu {{$active_menu=='ft_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="wind"></i> </div>
                      <div class="side-menu__title"> Ds phiếu thu chi</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('suptrans.list')}}" class="side-menu {{$active_menu=='ft_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="wind"></i> </div>
                      <div class="side-menu__title"> Ds giao dịch nhập xuất</div>
                  </a>
              </li>
          </ul>
      </li>
      <!-- product category menu -->
      <li>
          <a href="javascript:;" class="side-menu {{($active_menu =='pro_add'|| $active_menu=='pro_list' || $active_menu =='brand_list' || $active_menu == 'brand_list' || $active_menu=='cat_add'|| $active_menu=='cat_list')?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
              <div class="side-menu__title">
                  Hàng hóa 
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
            <ul class="{{($active_menu =='pro_add'|| $active_menu=='pro_list' || $active_menu =='cat_add'|| $active_menu=='cat_list' || $active_menu =='brand_list' || $active_menu == 'brand_list')?'side-menu__sub-open':''}}">
              <li>
                  <a href="{{route('product.index')}}" class="side-menu {{$active_menu=='pro_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                      <div class="side-menu__title">Danh sách hàng hóa</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('product.create')}}" class="side-menu {{$active_menu=='pro_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm hàng hóa</div>
                  </a>
              </li>
              
              <li>
                  <a href="{{route('category.index')}}" class="side-menu {{$active_menu=='cat_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="archive"></i> </div>
                      <div class="side-menu__title"> Danh sách danh mục </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('category.create')}}" class="side-menu {{$active_menu=='cat_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm danh mục </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('brand.index')}}" class="side-menu {{$active_menu=='brand_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="package"></i> </div>
                      <div class="side-menu__title"> Ds nhà sản xuất </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('brand.create')}}" class="side-menu {{$active_menu=='brand_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm nhà sản xuất </div>
                  </a>
              </li>
          </ul>
      </li>
      <!-- Nguoi dung menu  -->
      <li>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list'  )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
              <div class="side-menu__title">
                  Người dùng 
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list')?'side-menu__sub-open':''}}">
              <li>
                  <a href="{{route('user.index')}}" class="side-menu {{$active_menu=='ctm_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                      <div class="side-menu__title">Danh sách người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('user.create')}}" class="side-menu {{$active_menu=='ctm_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('ugroup.index')}}" class="side-menu {{$active_menu=='ugroup_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="circle"></i> </div>
                      <div class="side-menu__title">Ds nhóm người dùng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('ugroup.create')}}" class="side-menu {{$active_menu=='ugroup_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm nhóm người dùng</div>
                  </a>
              </li>
          </ul>
      </li>
    
       <!--Quan ly tai san menu  -->
       <li>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu=='ptm_list' || $active_menu=='ptd_list'|| $active_menu=='ptw_list'  || $active_menu=='pro_inv'   )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
              <div class="side-menu__title">
                  Quản lý tài sản
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='ptm_list'|| $active_menu=='ptw_list'||$active_menu=='ptd_list'||  $active_menu=='pro_inv'     )?'side-menu__sub-open':''}}">
              
              <li>
                  <a href="{{route('propertytodestroy.index')}}" class="side-menu {{$active_menu=='wtd_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="trash"></i> </div>
                      <div class="side-menu__title"> Chuyển kho hủy</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('propertytowarehouse.index')}}" class="side-menu {{$active_menu=='ptw_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="cpu"></i> </div>
                      <div class="side-menu__title">  chuyển kho bán</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('propertytomaintain.index')}}" class="side-menu {{$active_menu=='ptm_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="crosshair"></i> </div>
                      <div class="side-menu__title">  chuyển kho bảo hành</div>
                  </a>
              </li>
             <li>
                  <a href="{{route('inventoryproperty.index')}}" class="side-menu {{$active_menu=='pro_inv'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="slack"></i> </div>
                      <div class="side-menu__title">DS kho sử dụng</div>
                  </a>
              </li>
          </ul>
      </li>
      
    
        <!--Quan ly bao hanh -->
        <li>
          <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu=='mtp_list' || $active_menu=='mtd_list' || $active_menu=='mtw_list' || $active_menu=='mb_list' || $active_menu=='ms_list'|| $active_menu=='mainsent_list'||  $active_menu=='mainin_list'||  $active_menu=='main_inv'    )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="pie-chart"></i> </div>
              <div class="side-menu__title">
                  Quản lý bảo hành
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='mtp_list'|| $active_menu=='mtd_list'|| $active_menu=='mtw_list' || $active_menu=='mb_list' || $active_menu=='ms_list'|| $active_menu=='mainsent_list'|| $active_menu=='mainin_list'||  $active_menu=='main_inv' )?'side-menu__sub-open':''}}">
                <li>
                  <a href="{{route('inventorymaintenance.index')}}" class="side-menu {{$active_menu=='main_inv'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="codepen"></i> </div>
                      <div class="side-menu__title">Tồn kho bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainin.index')}}" class="side-menu {{$active_menu=='mainin_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                      <div class="side-menu__title">DS nhận bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainsent.index')}}" class="side-menu {{$active_menu=='ms_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                      <div class="side-menu__title">DS gửi bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintainback.index')}}" class="side-menu {{$active_menu=='mb_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="framer"></i> </div>
                      <div class="side-menu__title">DS trả bảo hành</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintowarehouse.index')}}" class="side-menu {{$active_menu=='mtw_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="cpu"></i> </div>
                      <div class="side-menu__title">Chuyển kho bán</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintodestroy.index')}}" class="side-menu {{$active_menu=='mtd_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="trash-2"></i> </div>
                      <div class="side-menu__title">Chuyển kho hủy</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('maintaintoproperty.index')}}" class="side-menu {{$active_menu=='mtp_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="slack"></i> </div>
                      <div class="side-menu__title">Chuyển kho sử dụng</div>
                  </a>
              </li>
          </ul>
      </li>
      
     <!--Bao cao -->
     <li>
          <a href="javascript:;" class="side-menu " class="side-menu {{($active_menu=='report_list'     )?'side-menu--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="book-open"></i> </div>
              <div class="side-menu__title">
                  Báo cáo
                  <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
              </div>
          </a>
          <ul class="{{($active_menu=='report_list'     )?'side-menu__sub-open':''}}">
              
              <li>
                  <a href="{{route('report.money')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title"> Lợi nhuận</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.thuchi')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title"> Thu chi</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.congnokhach')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title"> Công nợ khách</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.congnosup')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title"> Công nợ nhà cung cấp</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.sanpham')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title"> Sản phẩm</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('report.quy')}}" class="side-menu {{$active_menu=='report_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                      <div class="side-menu__title">Quỹ</div>
                  </a>
              </li>
          </ul>
    </li>
   
    <!-- setting menu -->
    <li>
        <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='mod_pro'||$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
              <div class="side-menu__title">
                  Cài đặt
                  <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
        </a>
        <ul class="{{($active_menu=='mod_pro'||$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='banner_add'|| $active_menu=='banner_list')?'side-menu__sub-open':''}}">
              <li>
                  <a href="{{route('banner.index')}}" class="side-menu {{$active_menu=='banner_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="image"></i> </div>
                      <div class="side-menu__title">Danh sách banner </div>
                  </a>
              </li>
              <li>
                  <a href="{{route('banner.create')}}" class="side-menu {{$active_menu=='banner_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm banner</div>
                  </a>
              </li>
              <li>
                <a href="{{route('modpro.index')}}" class="side-menu {{$active_menu=='mod_pro' ?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="package"></i> </div>
                    <div class="side-menu__title"> Mod sản phẩm</div>
                </a>
            </li>
              <li>
                  <a href="{{route('role.index',1)}}" class="side-menu {{$active_menu=='role_list'||$active_menu=='role_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="octagon"></i> </div>
                      <div class="side-menu__title"> Roles</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('cmdfunction.index',1)}}" class="side-menu {{$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="moon"></i> </div>
                      <div class="side-menu__title"> Chức năng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('setting.edit',1)}}" class="side-menu {{$active_menu=='setting_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="key"></i> </div>
                      <div class="side-menu__title"> Thông tin công ty</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('log.index')}}" class="side-menu {{$active_menu=='log_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Nhật ký</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('kiot.index')}}" class="side-menu {{$active_menu=='kiot'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Kiot</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('setting.update_data')}}" class="side-menu {{$active_menu=='updatedata'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title">Cập nhật hệ thống</div>
                  </a>
              </li>
          </ul>
    </li>
</ul>
</nav>