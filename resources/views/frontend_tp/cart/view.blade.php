<?php
 
  $setting =\App\Models\SettingDetail::find(1);
  $user = auth()->user();
  if($user)
  {
      $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
      .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
      $pro_carts =   \DB::select($sql ) ;
  }
  else
  {
      $pro_carts = [];
  }
  $cart_size= count($pro_carts);
?>
@extends('frontend_tp.layouts.master')
 
@section('content')
    @include('frontend_tp.layouts.breadcrumb')
   
<div class="wrapper !bg-[#ffffff]">
    <div class="container pt-14 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] pb-[4.5rem] xl:pb-24 lg:pb-24 md:pb-24">
        <div class="flex flex-wrap mx-[-15px] md:mx-[-20px] xl:mx-[-35px] mt-[-70px]">
            <div class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] md:px-[20px] px-[15px] mt-[70px] max-w-full">
                <div class="table-responsive">
                    <table class="table !text-center shopping-cart">
                        <thead>
                            <tr>
                                <th class="!pl-0 !w-[22.5rem]">
                                    <div class="h4 !mb-0 text-left">Sản phẩm</div>
                                </th>
                                <th>
                                    <div class="h4 !mb-0">Giá</div>
                                </th>
                                <th>
                                    <div class="h4 !mb-0">Số lượng</div>
                                </th>
                                <th>
                                    <div class="h4 !mb-0">Tổng</div>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id= 'product_list_table'>
                        
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap mx-[-15px] mt-0">
                    <div class="md:w-8/12 lg:w-7/12 xl:w-7/12 w-full flex-[0_0_auto] px-[15px] max-w-full mt-[20px]">
                        <div class=" table-responsive-md" id='table_footer'>
                            
                        </div>
                    </div>
                    <div class="md:w-4/12 lg:w-5/12 xl:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full ml-auto lg:!ml-0 xl:!ml-0 xl:!text-right lg:!text-right md:!text-right mt-[20px]">
                        <a href="{{route('front.shopingcart.checkout')}}" class="btn btn-primary text-white secondarybackgroundcolor border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] rounded hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]">
                            Đặt hàng
                        </a>
                    </div>
                </div>
            </div>
            <div class="xl:w-4/12 lg:w-4/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] md:px-[20px] px-[15px] mt-[70px] max-w-full">
                @include('frontend_tp.layouts.catpromenu')
                @include('frontend_tp.layouts.sideproduct')
            </div>
        </div>
    </div>
</div>          

@endsection
@section('scripts')
<script>

class Productc {
    constructor(id,name,slug, price, quantity,url ) {
        this.id = id;
      this.name = name;
      this.url = url;
      this.price = price;
      this.quantity = quantity;
      this.slug = slug;
       
    }
  
    // Method to get the total cost of the product
    getTotalCost() {
      return this.price * this.quantity;
    }
  
    // Method to update the price of the product
    updatePrice(newPrice) {
        if(newPrice >= 0)
            this.price = newPrice;
    }
    generateHTML()
    {
          
        var myhtml = '<tr><td class="option text-left flex flex-row items-center !pl-0" ><figure class="!rounded-[.4rem] !w-[7rem]"><a href="/product/view/'+this.slug+' ">'
        + '<img class="!rounded-[.4rem]" src="'
        + this.url + '" ></a></figure><div class="w-full ml-4"> <h3 class="post-title h6 !leading-[1.35] !mb-1"><a href="/product/view/'+this.slug+' " class="title_color">' 
        + this.name+'</a></h3> </div> </td> '
        
        +'<td><p class="price !m-0"> <ins class="no-underline text-[#e2626b]"><span class="amount">'+
        + this.price+'</span> </ins></p></td>'
        +'   <td><div class="form-select-wrapper"><input type="number" style="width:100px"  id="iq'
        + this.id +'" onchange="updateQuantity('+this.id+')" class="ipqty py-3 px-4  text-right form-control " value="'
        + this.quantity+'"/></div></td>'
        +'<td><p class="price !m-0"><span class="amount">'
        + Intl.NumberFormat().format(this.getTotalCost())+'</span></p></td>'
        +'<td class="!pr-0"><button onclick="removeProduct('+this.id+')" class="title_color"><i class="uil uil-trash-alt before:content-["\ed4b"]"></i></button>'
        +  '</td></tr> ';
        return myhtml;
    }
    // Method to update the quantity of the product
    updateQuantity(newQuantity) {
      this.quantity = newQuantity;
    }
  
    // Method to display product information
    displayInfo() {
    console.log(`Product Id: ${this.id}`);
      console.log(`Product Name: ${this.name}`);
      console.log(`Price: $${this.price}`);
      console.log(`Quantity: ${this.quantity}`);
      console.log(`Total Cost: $${this.getTotalCost()}`);
    }
  }
  
  function updatePrice(id )
{
    ip = document.getElementById('ip'+id);
    if(ip.value < 0)
    {
        ip.value = 0;
    }
    // alert(ip.value);
    productList.forEach((product) => {
        if(product.id == id)
        {
            product.updatePrice(ip.value);
           
        }
    });
    updateListView();
}
 
function removeProduct(id)
{
    productList = productList.filter(product => product.id !== id);
    update_cart(id,0);
    updateListView();
}

function updateQuantityA(id )
{
    ip = document.getElementById('iqa'+id);
    if(ip.value < 0)
    {
        ip.value = 1;
    }
    update_cart(id,ip.value);
    // alert(ip.value);
    productList.forEach((product) => {
        if(product.id == id)
        {
            product.updateQuantity(ip.value);
        }
    });
    updateListView();
}
function updateQuantity(id )
{
    ip = document.getElementById('iq'+id);
    if(ip.value < 0)
    {
        ip.value = 1;
    }
    update_cart(id,ip.value);
    // alert(ip.value);
    productList.forEach((product) => {
        if(product.id == id)
        {
            product.updateQuantity(ip.value);
        }
    });
    updateListView();
}
function generateTableFooter()
{
    var pcount = 0;
    var qsum = 0;
    var ptotal = 0;
    productList.forEach((product) => {
        pcount ++;
        qsum += product.quantity*1;
        ptotal += product.getTotalCost();
    });
    var myhtml = "<tr> <td class='text-left'>Tổng số loại: "+pcount 
            +"</td><td class='text-right'>-</td><td class='text-right'> số lượng: "
            +qsum+"</td><td class='text-right' colspan='3'> tổng tiền: "
            + Intl.NumberFormat().format(ptotal) +"</td></tr>";
   
    var myhtml = '<table class="table cart-table "> <tfoot> <tr> <td>Tổng:</td> <td>'
                   +' <h2> '+Intl.NumberFormat().format(ptotal) +'</h2> </td> </tr> </tfoot> </table>';
    return myhtml;
}
function addtoProductList(newpro )
{
    var kq = true;
    productList.forEach((product) => {
        if(product.id == newpro.id)
            kq = false;
    });
    if(kq == true)
    productList.push(newpro)
    return kq;
}

function updateListView()
{
    var tbody = $('#product_list_table');
    var tfooter = $('#table_footer');
    
    var myhtml ="";
    productList.forEach((product) => {
        
        
        myhtml += product.generateHTML();
    });
    tbody.html(myhtml);
    tfooter.html(generateTableFooter());
}
 
function update_cart(product_id,quantity)
{
    const dataToSend = {
            _token: "{{ csrf_token() }}",
            product_id: product_id,
            quantity: quantity,
        };
        $.ajax({
            url: "{{route('front.shopingcart.update')}}" , // Replace with your actual server endpoint URL
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(dataToSend),
            success: function(response) {
                var msg = response.msg;
                $.notify(response.msg,'success' ); 
                var return_pros = response.products;
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
            },
            error: function(error) {
            console.error("Error add to addtocart:", error);
            }
        });

}
</script>
<script>
 
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

var  productList=[];
var tong = 0;

$(window).ready(function()
{  
    $.ajax({
        type: 'GET',
        url: '{{route("front.shopingcart.getlist")}}',
        success: function(data) {
            console.log('kết quả');
            console.log(data);
            var products = data.products;
            products.forEach((pitem) => {
                var imageurls = pitem.photo.split(",");
                newpro = new Productc(pitem.id,pitem.title,pitem.slug, pitem.price,  pitem.quantity,imageurls[0] );
                productList.push(newpro);
            });
            updateListView();  
        },
        error: function(error) {
            console.error("Error add to addtocart:", error);
        }
    }); 
});
</script>
@endsection