@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
 <!--section start-->
 <section class="cart-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 table-responsive-xs">
                    <table border='1' class="table cart-table ">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Hàng hóa</th>
                                <th scope="col">Đơn giá bán</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id='product_list_table'>
                            
                            
                        </tbody>
                        </table>
                        <div class=" table-responsive-md" id='table_footer'>
                         
                        </div>
                    
                </div>
            </div>
            <div class="row cart-buttons">
                <div class="col-6"><a href="{{route('home')}}" class="btn btn-solid">Tiếp tục mua sắm</a></div>
                <div class="col-6"><a href="{{route('front.shopingcart.checkout')}}" class="btn btn-solid">Thanh toán</a></div>
            </div>
        </div>
    </section>
    <!--section end-->
    
@endsection

@section('scripts')
<script >


class Product {
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
        var btnclose='<button type="button" onclick="removeProduct('+this.id+')" class="btn-close text-red" data-tw-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </button>';
       
        var myhtml = '<tr><td  ><div class="flex items-center"><div class=" ">'
        + '<img class=" " src="'
        + this.url + '" ></div><a href="" class="font-medium whitespace-nowrap ml-4">'
        + this.name+'</a> </div> </td> <td class="text-right"><div class="flex"><span>'
        + this.price+'</span>  </div></td><td class="text-right"><input type="number" style="width:100px"  id="iq'
        + this.id +'" onchange="updateQuantity('+this.id+')" class="ipqty py-3 px-4  text-right form-control " value="'
        + this.quantity+'"/></td><td class="text-right">'
        + Intl.NumberFormat().format(this.getTotalCost())+'</td><td>' + btnclose +'</td></tr>'
        // +'<tr> <td colspan="5">'+ divprice+' </td></tr>'
        
        ;

        var myhtml1 = ' <tr> <td> <a href="#"><img src="'+this.url+'" alt=""></a>'
            +'</td> <td><a href="'+'{{ url('')}}'+'/product/view/'+this.slug+'"><h4>'+this.name+'</h4></a> <div class="mobile-cart-content row">'
            +'<div class="col"> <div class="qty-box"> <div class="input-group"> '
            +'<input type="text"  id="iqa'
            + this.id +'" onchange="updateQuantityA('+this.id+')"  class="form-control input-number" value="'+this.quantity+'">'
             +'</div> </div> </div> <div class="col"> <h4 class="td-color">'+this.price+'</h4> </div>'
             +' <div class="col"> <h4 class="td-color">'+ btnclose  
             +' </h4>  </div>  </div> </td> <td> <h4>'+this.price+'</h4></td><td> <div class="qty-box">'
            +'<div class="input-group"><input type="number" min="1"  id="iq'
            + this.id +'" onchange="updateQuantity('+this.id+')"  class="form-control input-number"'
            +' value="'+this.quantity+'"> </div> </div> </td> <td><h4 class="td-color">'    
            + Intl.NumberFormat().format(this.getTotalCost())+'</h4></td><td>' + btnclose +'</td></tr> ';
            
  
        return myhtml1;
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
function add_notify(msg, status)
    {
        $.notify({
                    icon: 'fa fa-check',
                    title: status?'Thành Công!':'Thất bại!',
                    message:  msg,
                }, {
                    element: 'body',
                    position: null,
                    type: status?"info":"warning",
                    allow_dismiss: false,
                    newest_on_top: false,
                    showProgressbar: true,
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    offset: 20,
                    spacing: 10,
                    z_index: 1031,
                    delay: 2000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                    icon_type: 'class',
                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                        '<button type="button" aria-hidden="true" class="btn-close" data-notify="dismiss"></button>' +
                        '<span data-notify="icon"></span> ' +
                        '<span data-notify="title">{1}</span> ' +
                        '<span data-notify="message">{2}</span>' +
                        '<div class="progress" data-notify="progressbar">' +
                        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                        '</div>' +
                        '<a href="{3}" target="{4}" data-notify="url"></a>' +
                        '</div>'
                });
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
                 add_notify(response.msg,response.status);
                var return_pros = response.products;
                // console.log(return_pros);
                var return_pros = response.products;
                // console.log(return_pros);
                //modify head shopingcart
                var innerhtml = "";
                var total = 0;
                var dem = 0;
                $('#cart_qty_cls').html(return_pros.length);
                while (dem < 5 && dem < return_pros.length)
                {
                   var pp = return_pros[dem];
                    var imageurls = pp.photo.split(",");
                    innerhtml += '<li> <div class="media"> <a href="#"><img alt="" class="me-3" '
                    +'    src="'+(imageurls.length >0? imageurls[0]:"")+'"></a>  <div class="media-body">  <a href="#">  <h4>'+pp.title+'</h4> '
                    +'</a>  <h4><span>'+pp.quantity+' x ' 
                    + Intl.NumberFormat().format(pp.price)
                    +'</span></h4>  </div> </div>   </li>';
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
                innerhtml  +=' <li>  <div class="total">  <h5>Tổng : <span>'
                  + Intl.NumberFormat().format(total)+'</span></h5>'
                    +'   </div>  </li>  <li>  <div class="buttons"><a href="'+'{{route("front.shopingcart.view")}}'+'" class="view-cart">'
                    +'xem giỏ hàng</a> <a href="#" class="checkout">Mua hàng</a></div>  </li>';
                $('#head_shoping_cart').html(innerhtml);
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
                newpro = new Product(pitem.id,pitem.title,pitem.slug, pitem.price,  pitem.quantity,imageurls[0] );
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