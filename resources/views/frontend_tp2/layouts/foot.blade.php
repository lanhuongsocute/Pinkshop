<div class="progress-wrap fixed w-[2.3rem] h-[2.3rem] cursor-pointer block shadow-[inset_0_0_0_0.1rem_rgba(128,130,134,0.25)] z-[1010] opacity-0 invisible translate-y-3 transition-all duration-[0.2s] ease-[linear,margin-right] delay-[0s] rounded-[100%] right-6 bottom-6 motion-reduce:transition-none after:absolute after:content-['\e951'] after:text-center after:leading-[2.3rem] after:text-[1.2rem] after:text-[#54a8c7] after:h-[2.3rem] after:w-[2.3rem] after:cursor-pointer after:block after:z-[1] after:transition-all after:duration-[0.2s] after:ease-linear after:left-0 after:top-0 motion-reduce:after:transition-none after:font-Unicons">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path class="fill-none stroke stroke-[4] box-border transition-all duration-[0.2s] ease-linear motion-reduce:transition-none" d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
 
  <script src="{{asset('frontend/assets_tp/js/plugins.js')}}"></script>
  <script src="{{asset('frontend/assets_tp/js/theme.js')}}"></script>
  <script src="{{asset('frontend/assets_tp/js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('frontend/assets_tp/js/notify.min.js')}}"></script>
  <script>
    class Sanpham {
      constructor(id, quantity) {
          this.id = id;
          this.quantity = quantity;
      }
  
  }
       
      $('body').on('click','.uil-heart , #btn_add_to_wish' , function() {
            var data_send = new Sanpham($(this).attr("data-id"),0);
              console.log(data_send);
              const dataToSend = {
                  _token: "{{ csrf_token() }}",
                  product: data_send,
              };
              $.ajax({
                  url: "{{route('front.wishlist.add')}}" , // Replace with your actual server endpoint URL
                  method: "POST",
                  contentType: "application/json",
                  data: JSON.stringify(dataToSend),
                  success: function(response) {
                      var msg = response.msg;
                      $.notify(msg,'success');
                  },
                  error: function(error) {
                  console.error("Error add to wishlist:", error);
                  }
              });
      });
      $('body').on('click','.item-cart' , function() {
        
        var data_send = new Sanpham($(this).attr("data-id"),1);
        console.log(data_send);
        const dataToSend = {
            _token: "{{ csrf_token() }}",
            product: data_send,
        };
        $.ajax({
            url: "{{route('front.shopingcart.add')}}" , // Replace with your actual server endpoint URL
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(dataToSend),
            success: function(response) {
                var msg = response.msg;
                // add_notify(response.msg,response.status);
                var return_pros = response.products;
                //  console.log(return_pros);
                //modify head shopingcart
                var innerhtml = "";
                var total = 0;
                var dem = 0;
                if(!return_pros)
                    return;
                $('#cart_qty_cls').html(return_pros.length);

                

                while (dem < 10 && dem < return_pros.length)
                {
                   var pp = return_pros[dem];
                    var imageurls = pp.photo.split(",");
                    innerhtml += ' <div class="shopping-cart-item flex justify-between !mb-4"> <div class="flex flex-row">'
                    +'<figure class="!rounded-[.4rem] !w-[7rem]"> <a href="/product/view/' +pp.slug+'"> <img class="!rounded-[.4rem]"   '
                    +'    src="'+(imageurls.length >0? imageurls[0]:"")+'"/></a> </figure>  <div class="!w-full !ml-[1rem]">'
                    +'<h3 class="post-title !text-[.8rem] !leading-[1.35] !mb-1"><a href= "/product/view/' +pp.slug+'" class="title_color">'
                    +pp.title +'</a></h3><p class="price !text-[.7rem]"> <ins class="no-underline text-[#e2626b]"><span class="amount">'
                    + Intl.NumberFormat().format(pp.price) +'đ</span></ins> x '+pp.quantity +'</p></div></div></div>';
                    total += pp.price*pp.quantity;
                    dem += 1;
                    if(dem == 10 && return_pros.length > 10)
                    {
                        innerhtml += '<li>   <a href="#"> Xem thêm ...  </a>    </li>';
                         
                    }
                }
                while (dem < return_pros.length)
                {
                    total +=  return_pros[dem].price*return_pros[dem].quantity;
                    dem++;
                }
                var tong_quick_cart = Intl.NumberFormat().format(total) +' đ';
                $('#head_shoping_cart').html(innerhtml);
                $('#tong_quick_cart').html(tong_quick_cart);
                $.notify(msg,'success');
            },
            error: function(error) {
            console.error("Error add to addtocart:", error);
            }
        });
      });

      $('body').on('click','#btn_add_to_cart' , function() {
        var pro_quantity = $('#pro_quantity').val();

        var data_send = new Sanpham($(this).attr("data-id"),pro_quantity);
        console.log(data_send);
        const dataToSend = {
            _token: "{{ csrf_token() }}",
            product: data_send,
        };
        $.ajax({
            url: "{{route('front.shopingcart.add')}}" , // Replace with your actual server endpoint URL
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(dataToSend),
            success: function(response) {
                var msg = response.msg;
                // add_notify(response.msg,response.status);
                var return_pros = response.products;
                //  console.log(return_pros);
                //modify head shopingcart
                var innerhtml = "";
                var total = 0;
                var dem = 0;
                if(!return_pros)
                    return;
                $('#cart_qty_cls').html(return_pros.length);

                

                while (dem < 10 && dem < return_pros.length)
                {
                   var pp = return_pros[dem];
                    var imageurls = pp.photo.split(",");
                    innerhtml += ' <div class="shopping-cart-item flex justify-between !mb-4"> <div class="flex flex-row">'
                    +'<figure class="!rounded-[.4rem] !w-[7rem]"> <a href="/product/view/' +pp.slug+'"> <img class="!rounded-[.4rem]"   '
                    +'    src="'+(imageurls.length >0? imageurls[0]:"")+'"/></a> </figure>  <div class="!w-full !ml-[1rem]">'
                    +'<h3 class="post-title !text-[.8rem] !leading-[1.35] !mb-1"><a href= "/product/view/' +pp.slug+'" class="title_color">'
                    +pp.title +'</a></h3><p class="price !text-[.7rem]"> <ins class="no-underline text-[#e2626b]"><span class="amount">'
                    + Intl.NumberFormat().format(pp.price) +'đ</span></ins> x '+pp.quantity +'</p></div></div></div>';
                    total += pp.price*pp.quantity;
                    dem += 1;
                    if(dem == 10 && return_pros.length > 10)
                    {
                        innerhtml += '<li>   <a href="#"> Xem thêm ...  </a>    </li>';
                         
                    }
                }
                while (dem < return_pros.length)
                {
                    total +=  return_pros[dem].price*return_pros[dem].quantity;
                    dem++;
                }
                var tong_quick_cart = Intl.NumberFormat().format(total) +' đ';
                $('#head_shoping_cart').html(innerhtml);
                $('#tong_quick_cart').html(tong_quick_cart);
                $.notify(msg,'success');
            },
            error: function(error) {
            console.error("Error add to addtocart:", error);
            }
        });
      });

  </script>
  