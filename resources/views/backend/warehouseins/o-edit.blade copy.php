@extends('backend.layouts.master')
@section('content')
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Điều chỉnh phiếu nhập kho
                    </h2>
                   
                </div>
                <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Item List -->
                    <div class="intro-y col-span-12 lg:col-span-8">
                        <div class="lg:flex intro-y">
                            <div class="relative">
                                <input type="text" id='product_search' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Tên sản phẩm ...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                            </div>
                            
                                
                        </div>
                       
                        <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
                            <div class="col-span-12 lg:col-span-12 2xl:col-span-12">
                                <div class="box p-5 rounded-md">
                                    <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                        <div class="font-medium text-base truncate">Chi tiết sản phẩm</div>
                                        <!-- <a href="" class="flex items-center ml-auto text-primary"> <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Notes </a> -->
                                    </div>
                                    <div class="overflow-x-auto lg:overflow-visible -mt-3">
                                 
                                        <table border='1' class="table table-striped ">
                                            <thead>
                                                <tr>
                                                    
                                                    <th class="whitespace-nowrap !py-5">Hàng hóa</th>
                                                    <th class="whitespace-nowrap text-right">Đơn giá nhập</th>
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
                    <!-- BEGIN: Ticket -->
                    <div class="col-span-12 lg:col-span-4">
                        <div class="intro-y box mt-3  py-3 px-3 ">
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn kho
                                </label>
                                <select id="warehouse_id" name="wh_id" class="form-select mt-2 sm:mr-2"    >
                                    @foreach ($warehouses as $wh)
                                        <option value="{{$wh->id}}" {{$warehousein->wh_id==$wh->id?'selected':''}}>{{$wh->title}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                            <div class=" mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn nhà cung cấp
                                </label>
                                <input type="text" id='supplier_search' 
                                value="{{\App\Models\User::where('id',$warehousein->supplier_id)->value('full_name')}}"
                                class="form-control py-3  mt-2" placeholder="Tên nhà cung cấp">
                                <input type="hidden" id="supplier_id" value="{{$warehousein->supplier_id}}" />
                            </div>
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3 ">
                            <div class="mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Phí vận chuyển
                                </label>
                                <input type="number" id='shipcost'  value="{{$ship_amount}}"
                                class="form-control py-3 mt-2 " placeholder="Phí vận chuyển" >
                                
                            </div>
                            
                            
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3">
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Số tiền thanh toán:
                                </label>
                            </div>
                           
                            <div class="mt-3"> 
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Tiền giảm:
                                </label>
                                <input type="number" id='discount_amount'  value="{{$warehousein->discount_amount}}"
                                class="form-control py-3 mt-2 " placeholder="tiền giảm">
                            </div> 
                            <div class ="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                    Số tiền phải trả:
                                </label>
                                <span  id='sptotalcost' class="text-medium" >
                                {{$warehousein->final_amount}}
                                </span>
                            </div>
                            <div class="mt-3">
                                <div class="mt-2">
                                    <div class="form-check form-switch"> 
                                        <input id="check_paidall" class="form-check-input" {{$warehousein->is_paid == true?'checked':''}} type="checkbox">
                                         <label class="form-check-label" for="checkbox-switch-7">Đã thanh toán hết</label> </div>
                                </div>
                                <input  id='paid_amount' type="number" name='paid_amount' value="{{$warehousein->paid_amount+ $ship_amount}}"
                                class="form-control py-3 mt-2 " placeholder="số tiền đã thanh toán">
                                <input type='hidden' value="{{$warehousein->final_amount + $ship_amount}}" id='totalcost'/>
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn tài khoản trả tiền
                                </label>
                                <select id="bank_id" name="bank" class="form-select mt-2 sm:mr-2"    >
                                    @foreach ($bankaccounts as $bank)
                                        <option value="{{$bank->id}}" {{$bank_id==$bank->id?'selected':''}}>{{$bank->title}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Người lập đơn: 
                                </label> 
                                <span class='font-medium'>
                                    {{$user->full_name}}
                                </span>
                            </div>
                        
                            <div class="tab-content">
                                <div id="ticket" class="tab-pane active" role="tabpanel" aria-labelledby="ticket-tab">
                                    
                                    <div class="flex mt-5">
                                        <button id='btnstore' class="btn btn-primary w-32 shadow-md ml-auto">Lưu</button>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                    <!-- END: Ticket -->
                </div>
                <!-- BEGIN: New Order Modal -->
                
                <!-- END: New Order Modal -->
</div>            
@endsection
@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet">  
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script src="{{asset('backend/assets/js/product_v2.js')}}"></script> 
<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;

$(document).ready(function(){ //Your code here 
    $.ajax({
        type: 'GET',
        url: '{{route("warehousein.getProductList")}}',
        data: {
            wi_id: {{$warehousein->id}},
        
        },
        success: function(data) {
            console.log(data);
            var products = data.msg;
            products.forEach((pitem) => {
                var imageurls = pitem.photo.split(",");
                var plist = [];
                pitem.groupprice.forEach((groupprice) => {
                    var newgroup = new Pricelist(groupprice.idg,groupprice.title,groupprice.price,groupprice.id);
                    plist.push(newgroup);
                });
                newpro = new Product(pitem.id,pitem.title, pitem.price, pitem.quantity,imageurls[0],plist);
                productList.push(newpro);
            });
            updateListView();  
        }
    });

$('#btnstore').on( "click", function() {
    var wh_id = document.getElementById('warehouse_id').value;
    var iptotalcost = document.getElementById('totalcost').value;
    var shipcost = document.getElementById('shipcost').value;
    var discount_amount = document.getElementById('discount_amount').value;
    var supplier_id = document.getElementById('supplier_id').value;
    var final_amount = parseInt(iptotalcost )  ;
    var paid_amount = document.getElementById('paid_amount').value;
    var bank_id = document.getElementById('bank_id').value;
     
    if (supplier_id == 0)
    {
        Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin nhà cung cấp!',
                    'error'
                ); 
        return;
    }
    if(parseInt(paid_amount)<parseInt(shipcost ))
    {
        Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền trả phải lớn hơn hoặc bằng tiền vận chuyển!',
                    'error'
                ); 
        return;
    }
    if(parseInt(paid_amount)>final_amount )
    {
        Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền trả lớn hơn tiền phải trả!',
                    'error'
                ); 
        return;
    }
    importDoc = new ImportDoc({{$warehousein->id}},supplier_id,final_amount,discount_amount,shipcost,paid_amount,bank_id,wh_id);
    console.log(importDoc);
    const dataToSend = {
        '_method': 'PATCH',
        _token: "{{ csrf_token() }}",
        importDoc: importDoc,
        products: productList
    };

    $.ajax({
        url: "{{route('warehousein.update',$warehousein->id)}}", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
        console.log("Product list and supplier_id sent successfully:", response);
        if(response.status == true)
            Swal.fire('Thành công',response.msg,'success'); 
        else
            Swal.fire('Thất bại',response.msg,'error'); 
            return;
        },
        error: function(error) {
        console.error("Error sending product list and supplier_id:", error);
        }
    });
} );

/////////////////////////
///////discount change/////
$("#discount_amount").keyup(function(){
    var v_value = $("#discount_amount").val();
    if( !v_value )
     {
        v_value = 0;
        $("#discount_amount").val(0);
     }  
    updateListView();
});

$("#shipcost").keyup(function(){
    var v_value = $("#shipcost").val();
    if( !v_value )
     {
        v_value = 0;
        $("#shipcost").val(0);
     }  
    updateListView();
});
$("#paid_amount").keyup(function(){
    var v_value = $("#paid_amount").val();
    if( !v_value )
     {
        v_value = 0;
        $("#paid_amount").val(0);
     }  
    updateListView();
});

     ////////////////////////////////////////////////
    // /////////check paidall change//////////////////////
    ///////////////////////////////////////////////
    var check_paidall = $('#check_paidall');
    check_paidall.change(function(e){
       
        var check = $(this).prop('checked');
        if (check == true)
        {
            $('#paid_amount').val($('#totalcost').val());
            $('#paid_amount').prop( "disabled", true );
        }
        else
        {
            $('#paid_amount').prop( "disabled", false );
        }
        // e.preventDefault();
      
    });
    ////////////////////////////////////////////////
    // /////////warehouse change//////////////////////
    ///////////////////////////////////////////////
    
    var warehouse_id = $('#warehouse_id');
    warehouse_id.change(function(e){
        // alert('nunu');
        // e.preventDefault();
        productList.length=0;
        updateListView();
    });
    ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
            var warehouse_id = $('#warehouse_id').val();
            
            // var idnhom = $('#selectgroupid').val();
            // console.log('warehouseid' + warehouse_id);
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearchwi')}}',
                data: {
                    data: request.term,
                    warehouse_id: warehouse_id,
                
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
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {

            $.ajax({
                type: 'GET',
                url: '{{route('product.groupprice')}}',
                data: {
                    product_id: ui.item.id,
                },
                success: function(data) {
                    console.log(data);
                    var listprices = data.msg;
                    var plist=[];
                    listprices.forEach((item) => {
                        gprice = new Pricelist(item.id,item.title,item.price,item.gpid);
                        plist.push(gprice);
                    });
                    const newProduct = 
                    new Product(ui.item.id,ui.item.value, ui.item.price, 1,ui.item.imgurl,plist);
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
            });
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500">No:' + (item.qty==null?0:item.qty) 
            +'</span>  <span class=" text-slate-500">Price:' + (item.qty==null?0:item.qty)
            +'</td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
    // /////////supplier search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#supplier_search');
    product_search.autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: '{{route('supplier.jsearch')}}',
                data: {
                    data: request.term,
                },
                success: function(data) {
                    console.log(data);
                    response( jQuery.map( data.msg, function( item ) {
                        return {
                        id: item.id,
                        value: item.title,
                       
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {

           $('#supplier_id').val(ui.item.id);
           
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td>'
            +'<span   style="line-height:220%">'+ item.value +'</span></td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////


});
    

</script>
@endsection