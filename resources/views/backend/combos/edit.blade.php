@extends('backend.layouts.master')
@section('content')
<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Tạo combo
        </h2>
        
    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="lg:flex intro-y">
                <div class="relative">
                    <input type="hidden" name="combo_id" value="{{$combo->combo_id}}"/>
                    <label> Tìm tên combo </label>
                    <input type="text" id='compro_search' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Tên sản phẩm ...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                </div>
                <a id="btn_shownew"  href="{{route('product.create')}}" class=" flex  py-3 px-4 box w-full lg:w-auto mt-3 lg:mt-0 ml-auto">
                    <i  data-lucide="box"> </i>
                    Thêm sản phẩm
                </a> 
                    
            </div>
            <div class="lg:flex intro-y" id="product_combo">
            <div style='padding-top:15px'> <span> Sản phẩm: {{$combo->title}}</span></div>
            </div>
            <input type="hidden" id='product_id'  name="product_id" value = "{{$combo->id}}">
                
        </div>
        <div class="intro-y col-span-12 lg:col-span-12">
            
            
            <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
                <div class="col-span-12 lg:col-span-12 2xl:col-span-12">
                    <div class="box p-5 rounded-md">
                    <div class="lg:flex intro-y">
                        <div class="relative">
                            <label> Tìm tên cho chi tiết combmo</label>
                                <input type="text" id='product_search' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Tên sản phẩm ...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                            </div>
                        </div>
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <div class="font-medium text-base truncate">Chi tiết sản phẩm</div>
                            <!-- <a href="" class="flex items-center ml-auto text-primary"> <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Notes </a> -->
                        </div>
                        <div class="overflow-x-auto lg:overflow-visible -mt-3">
                        
                            <table border='1' class="table table-striped ">
                                <thead>
                                    <tr>
                                        
                                        <th class="whitespace-nowrap !py-5">Hàng hóa</th>
                                        <th class="whitespace-nowrap text-right">Đơn giá bán</th>
                                        <th class="whitespace-nowrap text-right">Số lượng</th>
                                        <th class="whitespace-nowrap text-right">Tổng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id='product_list_table'>
                                    
                                    
                                </tbody>
                                <tfoot id='table_footer'>
                                </tfoot>
                            </table>
                                
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Item List -->
        <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
            <div id="ticket" class="tab-pane active" role="tabpanel" aria-labelledby="ticket-tab">
                
                <div class="flex mt-5">
                    <input type='hidden' value='0' id='totalcost'/>
                    <button id='btnstore' class="btn btn-primary w-32 shadow-md ml-auto">Lưu</button>
                </div>
            </div>
                            
        </div>
    </div>
</div>
     <!-- end content -->
 
                                     
@endsection
@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="{{asset('backend/assets/js/product_combo.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
   
   $( "#btn_shownew" ).on( "click", function() {
        myModal.show();
   });
   $( "#btn_newuser" ).on( "click", function() {
        // alert('click');
        var full_name = $('#full_name').val();
        var phone = $('#phone').val();
        var address = $('#address').val();
        if(full_name == null || full_name=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin tên khách hàng!',
                    'error'
                ); 
            return;
        }
        if(phone == null || phone=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin điện thoại!',
                    'error'
                ); 
            return;
        }
        if(address == null || address=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin địa chỉ!',
                    'error'
                ); 
            return;
        }
        $.ajax({
            url:"{{route('customer.add')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                full_name:full_name,
                phone:phone,
                address:address,
            },
            success:function(response){
                myModal.hide();
                if(response.status == true)
                {
                    var cell = response.msg;
                     $('#customer_search').val(cell.full_name);
                    $('#customer_id').val(cell.id);
                }
                else
                {
                    Swal.fire(
                    'Lỗi xãy ra',
                    response.msg,
                    'error'
                ); 
                }
                
                console.log(response.msg);
            }
            
        });
    } );
</script>

<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;

$(document).ready(function(){ //Your code here 
    $.ajax({
        type: 'GET',
        url: '{{route("combo.getProductList")}}',
        data: {
           combo_id: {{$combo->combo_id}},
        
        },
        success: function(data) {
            console.log(data);
            var products = data.msg;
            products.forEach((pitem) => {
                var imageurls = pitem.photo.split(",");
                
                // newpro = new Product(pitem.id,pitem.title, pitem.price,pitem.type,pitem.quantity,pitem.stock_qty +  pitem.quantity,imageurls[0],pitem.seri,pitem.series,plist);
                
                var newProduct = new Product(pitem.id,pitem.title, pitem.price,imageurls[0], pitem.quantity);
          
                productList.push(newProduct);
            });
            updateListView();  
        }
    }); 


$('#btnstore').on( "click", function() {
    $('#btnstore').prop('disabled', true);
    var product_id = document.getElementById('product_id').value;
    
    var iptotalcost = document.getElementById('totalcost').value;
    var final_amount = parseInt(iptotalcost )  ;
    
    importDoc = new ImportDoc({{$combo->combo_id}},product_id,final_amount );
    console.log(importDoc);
    const dataToSend = {
        _token: "{{ csrf_token() }}",
        importDoc: importDoc,
        products: productList
    };

    $.ajax({
        url: "{{route('combo.update',$combo->id)}}", // Replace with your actual server endpoint URL
        method: "PATCH",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            $('#btnstore').prop('disabled', false);
            if(response.status == true)
            {
                
              
              
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Lưu thành công',
                showConfirmButton: false,
                timer: 1000
                });
                // productList.length = 0;
                // updateListView();
                return;
            }
            else
            {
                Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                ); 
            }

        },
        error: function(error) {
            $('#btnstore').prop('disabled', false);
            console.error("Error sending product list and customer_id:", error);
        }
    });
} );

/////////////////////////
///////discount change/////
 

 ////////////////////////////////////////////////
    // /////////compro_search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#compro_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
           
          
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearchco')}}', 
                data: {
                    data: request.term,
                },
                success: function(data) {
                    console.log(data);
                    response( jQuery.map( data.msg, function( item ) {
                        var imageurls = item.photo.split(",");
                    
                        return {
                        id: item.id,
                        value: item.title,
                        price: item.price,
                        imgurl: imageurls[0],
                        qty: item.quantity,
                        expired:item.expired,
                        type:item.type,
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            $html = "<div style='padding-top:15px'> <span> Sản phẩm: "+ui.item.value+"</span></div>";
            $('#product_combo').html($html);
            $('#product_id').val(ui.item.id);
            // $.ajax({
            //     type: 'GET',
            //     url: '{{route('product.groupprice')}}',
            //     data: {
            //         product_id: ui.item.id,
            //     },
            //     success: function(data) {
            //         console.log(data);
            //         var listprices = data.msg;
            //         var plist=[];
            //         listprices.forEach((item) => {
            //             gprice = new Pricelist(item.id,item.title,item.price,item.gpid);
            //             plist.push(gprice);
            //         });
            //         const newProduct = 
            //         new Product(ui.item.id,ui.item.value, ui.item.price,ui.item.type, 1,ui.item.0, ui.item.imgurl,'',data.series,plist);
            //         if(!addtoProductList(newProduct))
            //         {
            //             Swal.fire(
            //                 'Không thực hiện!',
            //                 'Sản phẩm đã có!',
            //                 'error'
            //             );
            //         }
            //         updateListView();
            //     }
            // });
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>  <span class=" text-slate-500"> giá:' + (item.price==null?0:item.price)
            +'</span> <span class=" text-slate-500"> bảo hành:' + (item.expired==null?'':item.expired)+'</span>'
            +'</td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
   
    ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
            var warehouse_id = $('#warehouse_id').val();
            var customer_id = $('#customer_id').val();
            // var idnhom = $('#selectgroupid').val();
            // console.log('warehouseid' + warehouse_id);
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearchco')}}', 
                data: {
                    data: request.term,
                },
                success: function(data) {
                    console.log(data);
                    response( jQuery.map( data.msg, function( item ) {
                        var imageurls = item.photo.split(",");
                    
                        return {
                        id: item.id,
                        value: item.title,
                        price: item.price,
                        imgurl: imageurls[0],
                        qty: item.quantity,
                        expired:item.expired,
                        type:item.type,
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) { 
            console.log(ui.item);
            var newProduct = new Product(ui.item.id,ui.item.value, ui.item.price,ui.item.imgurl,1 );
            console.log(newProduct); //id,name, price, quantity,url,seri 
            if(!addtoProductList(newProduct))
            {
                Swal.fire(
                    'Không thực hiện!',
                    'Sản phẩm đã có!',
                    'error'
                );
            }
            updateListView();
           
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>  <span class=" text-slate-500"> giá:' + (item.price==null?0:item.price)
            +'</span> <span class=" text-slate-500"> bảo hành:' + (item.expired==null?'':item.expired)+'</span>'
            +'</td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
   

});
    

</script>
@endsection