<div class="job-list mb-10">
    <a href="{{route('front.profile')}}" class="card mb-4 lift">
        <div class="card-body p-5">
        
            <span class="flex flex-wrap mx-[-15px] items-center">
            <i style="font-size:200%" class="uil uil-user-nurse"></i> &nbsp;&nbsp;&nbsp;&nbsp; Thông tin tài khoản
            </span>
        </div>
    </a>
    <a href=" {{route('front.shopingcart.view')}}" class="card mb-4 lift">
        <div class="card-body p-5">
            <span class="flex flex-wrap mx-[-15px]   items-center">
            <i style="font-size:200%" class="uil uil-shopping-bag"></i>  &nbsp;&nbsp;&nbsp;&nbsp;  Giỏ hàng
            </span>
        </div>
    </a>
    <a href="{{route('front.profile.order')}}" class="card mb-4 lift">
        <div class="card-body p-5">
            <span class="flex flex-wrap mx-[-15px]  items-center">
            <i style="font-size:200%" class="uil uil-archive"></i> &nbsp;&nbsp;&nbsp;&nbsp;  Lịch sử mua hàng
            </span>
        </div> 
    </a>
    <a href="{{route('front.wishlist.view')}}" class="card mb-4 lift">
        <div class="card-body p-5">
            <span class="flex flex-wrap mx-[-15px]  items-center">
            <i style="font-size:200%" class="uil uil-file-alt"></i>  &nbsp;&nbsp;&nbsp;&nbsp; Danh sách yêu thích
            </span>
        </div>
    </a>
    <a href="{{route('front.profile.addressbook')}}" class="card mb-4 lift">
        <div class="card-body p-5">
            <span class="flex flex-wrap mx-[-15px]  items-center">
            <i style="font-size:200%" class="uil uil-file-alt"></i>  &nbsp;&nbsp;&nbsp;&nbsp; Danh sách địa chỉ
            </span>
        </div>
    </a>
    <a href="#" class="card mb-4 lift">
        <div class="card-body p-5">
            <span class="flex flex-wrap mx-[-15px]  items-center">
            <i style="font-size:200%" class="uil uil-file-alt"></i>  &nbsp;&nbsp;&nbsp;&nbsp; Theo dõi công nợ
            </span>
        </div>
    </a>
    <a  class="card mb-4 lift">
        <div class="card-body p-5">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                <button class="flex flex-wrap mx-[-15px]  items-center" type="submit">
                    <i style="font-size:200%" class="uil uil-arrow-circle-left"></i>   &nbsp;&nbsp;&nbsp;&nbsp; Đăng xuất
                </button>
            </form>
           
        </div>
    </a>
   
</div>
    