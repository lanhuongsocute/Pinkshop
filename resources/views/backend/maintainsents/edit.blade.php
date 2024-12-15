@extends('backend.layouts.master')
@section('content')
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Điều chỉnh phiếu gửi bảo hành
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
                                                   
                                                    <th class="whitespace-nowrap text-right">Số lượng</th>
                                                   
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id='product_list_table'>
                                                
                                                
                                            </tbody>
                                            <tfoot id='table_footer'>
                                            </tfoot>
                                        </table>
                                        <div class="form-help mt-6">
                                            * Kiểm tra số lượng, số loại hàng hóa.
                                            <br/> Thông tin sẽ không được điều chỉnh sau khi lưu một thời gian.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Item List -->
                    <!-- BEGIN: Ticket -->
                    <div class="col-span-12 lg:col-span-4">
                        <div class="intro-y box mt-3  py-3 px-3 ">
                            
                            <div class=" mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn đối tác
                                </label>
                                <div class="flex">
                                    <input type="text" id='supplier_search' value="{{\App\Models\User::find($ms->supplier_id)->full_name}}"
                                        class="form-control py-3   " placeholder="Tên hoặc số điện thoại">
                                    <button id ="btn_shownew" class=" btn btn-primary w-32 shadow-md ml-auto" style="width:50px">
                                        <i data-lucide="user-plus"   ></i>
                                    </button>
                                </div>
                                <input type="hidden" id="supplier_id" value="{{$ms->supplier_id}}" />
                                
                            </div>
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3 ">
                            <div class="mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Phí vận chuyển
                                </label>
                                <input type="number" id='shipcost'  value="{{$ms->shipcost}}"
                                class="form-control py-3 mt-2 " placeholder="Phí vận chuyển">
                                
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn nhà vận chuyển:
                                </label>
                                <select id="delivery_id" name="delivery_id" class="form-select mt-2 sm:mr-2"    >
                                    <option value=""  > -- nhà vận chuyển --</option>
                                        
                                    @foreach ($deliveries as $delivery)
                                        <option value="{{$delivery->id}}" {{$ms->delivery_id==$delivery->id?'selected':''}}>{{$delivery->full_name}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3">
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
                                    {{auth()->user()->full_name}}
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
</div>
     <!-- end content -->
  <!-- BEGIN: Modal   -->
  <div  id="myModal" class="modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thêm khách hàng </h2>    
                
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tên</label>
                    <input   id="full_name" type="text" class="form-control" placeholder="tên">
                </div>
                    
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Điện thoại</label>
                    <input id="phone"  type="text" class="form-control" placeholder="điện thoại">
                    <div class="form-help">Kiểm tra lại số điện thoại, thông tin nãy sẽ không được chỉnh sửa sau khi hoàn thành việc thêm mới.</div>
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Địa chỉ</label>
                    <input id="address"   type="text" class="form-control" placeholder="địa chỉ">
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_newuser" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
         </div>
 </div>                     
@endsection
@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="{{asset('backend/assets/js/product_msent.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
   const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModal"));
   $( "#btn_shownew" ).on( "click", function() {
        myModal.show();
   });
   $( "#btn_newuser" ).on( "click", function() {
        myModal.hide();
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
            url:"{{route('supplier.add')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                full_name:full_name,
                phone:phone,
                address:address,
            },
            success:function(response){
              console.log(response);
                if(response.status == true)
                {
                    var cell = response.msg;
                     $('#supplier_search').val(cell.full_name);
                    $('#supplier_id').val(cell.id);
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
        url: '{{route("maintainsent.getProductList")}}',
        data: {
            ms_id: {{$ms->id}},
        
        },
        success: function(data) {
            console.log(data);
            var products = data.msg;
            var flag = 0;
            products.forEach((pitem) => {
                var imageurls = pitem.photo.split(",");
                newpro = new Product(pitem.id,pitem.title, pitem.price,pitem.quantity,pitem.stock_qty +  pitem.quantity,imageurls[0],pitem.seri);
                flag = 0;
                productList.forEach((pl)=>{
                    if (pl.id == newpro.id)
                    {
                        flag = 1;
                        pl.updateQuantity(pl.quantity + newpro.quantity);
                    }
                });
                if(flag == 0)
                    productList.push(newpro);
            });
            updateListView();  
        }
    }); 


$('#btnstore').on( "click", function() {
   
   
    var shipcost = document.getElementById('shipcost').value;
    var supplier_id = document.getElementById('supplier_id').value;
    var bank_id = document.getElementById('bank_id').value;
    var delivery_id = document.getElementById('delivery_id').value;
    if (supplier_id == 0)
    {
        Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin đối tác!',
                    'error'
                ); 
        return;
    }
    
    importDoc = new ImportDoc({{$ms->id}},supplier_id, shipcost, bank_id, delivery_id);
    console.log(importDoc);
    console.log(productList);
    const dataToSend = {
        '_method': 'PATCH',
        _token: "{{ csrf_token() }}",
        importDoc: importDoc,
        products: productList
    };

    $.ajax({
        url: "{{route('maintainsent.update',$ms->id)}}", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            if(response.status == true)
            {
                Swal.fire(
                        'Thành công',
                        'nhập kho thành công!',
                        'success'
                    ); 
                    updateListView();
            }
           else
           {
            Swal.fire(
                        'Lỗi',
                        response.msg,
                        'error'
                    ); 
           }
           
            return;

        },
        error: function(error) {
        console.error("Error sending product list and supplier_id:", error);
        }
    });
} );

   
    ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
           
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearchms')}}',
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
                       
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            const newProduct = 
            new Product(ui.item.id,ui.item.value, ui.item.price, 1,ui.item.qty, ui.item.imgurl);
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
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>   '
            +'</td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
    // /////////supplier search//////////////////////
    ///////////////////////////////////////////////
    var supplier_search = $('#supplier_search');
    supplier_search.autocomplete({
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
    //////////end supplier search /////////////////////////


});
    

</script>
@endsection