
<?php $__env->startSection('content'); ?>
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Điều chỉnh build thành phẩm
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
                                        <div class="form-help mt-6">
                                            * Kiểm tra số tiền, số lượng, số loại hàng hóa.
                                            <br/> Thông tin sẽ không được điều chỉnh sau khi lưu một thời gian.
                                        </div>
                                        <div class="mt-3">
                                            <div class="form-help"> * Các số series cách nhau dấu ,</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Item List -->
                    <!-- BEGIN: Ticket -->
                    <div class="col-span-12 lg:col-span-4">
                            <div  class="intro-y box mt-3  py-3 px-3 ">
                           
                                <div class="mt-3">
                                    <label style="min-width:50px  " class="form-select-label" for="">
                                    Chọn kho
                                    </label>
                                    <select id="warehouse_id" name="wh_id" class="form-select mt-2 sm:mr-2"    >
                                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($wh->id); ?>" <?php echo e($combo->wh_id==$wh->id?'selected':''); ?>><?php echo e($wh->title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                </div>
                                
                            </div>
                            <div class="intro-y box mt-3  py-3 px-3 ">
                                <div class="lg:flex intro-y">
                                    <div class="relative">
                                        <label> Tìm tên combo </label>
                                        <input type="text" id='compro_search' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Tên sản phẩm ...">
                                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                                    </div>

                                    <a id="btn_shownew"  href="<?php echo e(route('product.create')); ?>" class=" flex  py-3 px-4 box w-full lg:w-auto mt-3 lg:mt-0 ml-auto">
                                        <i  data-lucide="box"> </i>
                                        Thêm sp
                                    </a> 
                                        
                                </div>
                                <div class="lg:flex intro-y" id="product_combo">
                                    <div style='padding-top:15px;padding-bottom:15px'> 
                                       <b> <span> Sản phẩm: <?php echo e($combo->title); ?></span></b> 
                                    </div>
                                </div>
                             
                                <div class="relative">
                                        <label> Số lượng</label>
                                        <input type="number" id='quantity' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" value="<?php echo e($combo->quantity); ?>">
                                         
                                </div>
                                <div class="relative">
                                    <label> Đơn giá bán</label>
                                    <input type="text" id='sold_price' class="form-control py-3 px-4 w-full lg:w-64 box pr-10"  oninput="formatNumberS(this)" value="<?php echo e(number_format($combo->price,0,'.',',')); ?>">
                                    
                                </div>
                                <input type="hidden" id='product_id'  name="product_id" value = "<?php echo e($combo->product_id); ?>">
                                
                        </div>
                       
                         
                        <input   type="hidden" id='shipcost'  value="0"
                        class="form-control py-3 mt-2 " placeholder="Phí vận chuyển">
                        <input type="hidden" id='discount_amount'  value="0"
                                class="form-control py-3 mt-2 " placeholder="tiền giảm">
                        <span  style="display:none" id='sptotalcost' class="text-medium" >
                                </span>
                        <input  id='paid_amount' type="hidden" name='paid_amount' value="0"
                                class="form-control py-3 mt-2 " placeholder="số tiền đã thanh toán">
                        <input type='hidden' value='0' id='totalcost'/>
                        <div class="intro-y box mt-3  py-3 px-3">
                             
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Người lập đơn: 
                                </label> 
                                <span class='font-medium'>
                                    <?php echo e($user->full_name); ?>

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
 
                                       
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="<?php echo e(asset('backend/assets/js/product_combocreation.js')); ?>"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;
let formatter = new Intl.NumberFormat('en-US'); 
$(document).ready(function(){ //Your code here 

    $.ajax({
        type: 'GET',
        url: '<?php echo e(route("combocreation.getProductList")); ?>',
        data: {
            combo_id: <?php echo e($combo->id); ?>,
        
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
                newpro = new Product(pitem.id,pitem.title, pitem.price,pitem.type,pitem.quantity,pitem.stock_qty +  pitem.quantity,imageurls[0],pitem.seri,pitem.series,plist);
                productList.push(newpro);
            });
            updateListView();  
        }
    }); 

$('#btnstore').on( "click", function() {
    $('#btnstore').prop('disabled', true);
    var wh_id = document.getElementById('warehouse_id').value;
    var iptotalcost = document.getElementById('totalcost').value;
    var product_id = document.getElementById('product_id').value;
    var discount_amount = document.getElementById('discount_amount').value;
    var customer_id = 0;
    var final_amount = parseInt(iptotalcost )  ;
    var paid_amount = document.getElementById('paid_amount').value;
    var quantity = document.getElementById('quantity').value;
    var sold_price = document.getElementById('sold_price').value;
    var int_sold_price = sold_price.replace(/,/g, '');
    if(product_id == 0)
    {
        Swal.fire(
                'Lỗi',
                'chưa điền thông tin sản phẩm tạo combo',
                'error'
        ); 
        $('#btnstore').prop('disabled', false);
        return;
    }
    if(quantity <= 0)
    {
        Swal.fire(
                'Lỗi',
                'số lượng phải lớn hơn 0',
                'error'
        ); 
        $('#btnstore').prop('disabled', false);
        return;
    }

    importDoc = new ImportDoc(<?php echo e($combo->id); ?>,final_amount, wh_id,product_id,quantity);
    console.log(importDoc);
    const dataToSend = {
        '_method': 'PATCH',
        _token: "<?php echo e(csrf_token()); ?>",
        importDoc: importDoc,
        products: productList,
        sold_price: int_sold_price,
    };

    $.ajax({
        url: "<?php echo e(route('combocreation.update',$combo->id)); ?>", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            $('#btnstore').prop('disabled', false);
            if(response.status == true)
            {
                Swal.fire(
                        'Thành công!',
                        response.msg,
                        'success'
                ); 
                // var hmlt = response.html;
                // $('#modalcontent').html(hmlt);
                // myModalprint.show();
              
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
           
        }
    });
} );

/////////////////////////
///////discount change/////
 
 
     ////////////////////////////////////////////////
    // /////////check paidall change//////////////////////
    ///////////////////////////////////////////////
   
    ////////////////////////////////////////////////
    // /////////warehouse change//////////////////////
    ///////////////////////////////////////////////
    ////////////////////////////////////////////////
    // /////////compro_search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#compro_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
           
          
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.jsearchco')); ?>', 
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
            $('#sold_price').val(  formatter.format(ui.item.price)  );
            // $.ajax({
            //     type: 'GET',
            //     url: '<?php echo e(route('product.groupprice')); ?>',
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
                url: '<?php echo e(route('product.jsearchwo')); ?>',
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

            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.groupprice')); ?>',
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
                    new Product(ui.item.id,ui.item.value, ui.item.price,ui.item.type, 1,ui.item.qty, ui.item.imgurl,'',data.series,plist);
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
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>  <span class=" text-slate-500"> giá:' + (item.price==null?0:item.price)
            +'</span> <span class=" text-slate-500"> bảo hành:' + (item.expired==null?'':item.expired)+'</span>'
            +'</td></tr></table>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
    // /////////customer search//////////////////////
    ///////////////////////////////////////////////
    var customer_search = $('#customer_search');
    customer_search.autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('customer.jsearch')); ?>',
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

           $('#customer_id').val(ui.item.id);
           
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
    
////////////////////////////////////////////////
    // /////////compro_search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#compro_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
          
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.jsearchco')); ?>', 
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/combocreations/edit.blade.php ENDPATH**/ ?>