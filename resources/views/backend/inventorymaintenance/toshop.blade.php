@extends('backend.layouts.master')
@section('content')
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Chuyển kho bán hàng
                    </h2>
                   
                </div>
                <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Item List -->
                    <div class="intro-y col-span-12 ">
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
                        <div class="intro-y box mt-3  py-3 px-3">
                             
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
                    <!-- END: Item List -->
                    
                </div>
</div>
     <!-- end content -->
            
@endsection
@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="{{asset('backend/assets/js/product_msent.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>

 

<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;

$(document).ready(function(){ //Your code here 
   

$('#btnstore').on( "click", function() {
   
    const dataToSend = {
        _token: "{{ csrf_token() }}",
        products: productList
    };

    $.ajax({
        url: "{{route('inventorymaintenance.savetodestroy')}}", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
        console.log("Product list and supplier_id sent successfully:", response);
            Swal.fire(
                        'Thành công',
                        'nhập kho thành công!',
                        'success'
                    ); 
            productList.length = 0;
            updateListView();
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
   

});
    

</script>
@endsection