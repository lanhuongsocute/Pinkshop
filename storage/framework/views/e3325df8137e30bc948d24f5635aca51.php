
<?php $__env->startSection('content'); ?>
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Chuyển kho
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
                                Kho nguồn
                                </label>
                                <select id="wh_id1" name="wh_id1" class="form-select mt-2 sm:mr-2"    >
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($wh->id); ?>" <?php echo e(old('wh_id1')==$wh->id?'selected':''); ?>><?php echo e($wh->title); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Nhân viên xuất kkho
                                </label>
                                <select id="vendor_id1" name="vendor_id1" class="form-select mt-2 sm:mr-2"    >
                                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vd->id); ?>" <?php echo e(old('vendor_id1')==$vd->id?'selected':''); ?>><?php echo e($vd->full_name); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                               Kho đích
                                </label>
                                <select id="wh_id2" name="wh_id2" class="form-select mt-2 sm:mr-2"    >
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($wh->id); ?>" <?php echo e(old('wh_id2')==$wh->id?'selected':''); ?>><?php echo e($wh->title); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Nhân viên nhập kho
                                </label>
                                <select id="vendor_id2" name="vendor_id2" class="form-select mt-2 sm:mr-2"    >
                                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vd->id); ?>" <?php echo e(old('vendor_id2')==$vd->id?'selected':''); ?>><?php echo e($vd->full_name); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3 ">
                            <div class="mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Phí vận chuyển
                                </label>
                                <input type="number" id='shipcost'  value="0"
                                class="form-control py-3 mt-2 " placeholder="Phí vận chuyển">
                                 
                            </div>
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn nhà vận chuyển:
                                </label>
                                <select id="delivery_id" name="delivery_id" class="form-select mt-2 sm:mr-2"    >
                                    <option value=""  > -- nhà vận chuyển --</option>
                                        
                                    <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($delivery->id); ?>" <?php echo e(old('delivery_id')==$delivery->id?'selected':''); ?>><?php echo e($delivery->full_name); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" id="totalcost" value="0"/>
                            </div>
                            
                        </div>
                        <div class="intro-y box mt-3  py-3 px-3">
                            
                            <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn tài khoản trả tiền
                                </label>
                                <select id="bank_id" name="bank" class="form-select mt-2 sm:mr-2"    >
                                    <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($bank->id); ?>" <?php echo e(old('bank_id')==$bank->id?'selected':''); ?>><?php echo e($bank->title); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
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
             
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="<?php echo e(asset('backend/assets/js/product_transfer2.js')); ?>"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;

$(document).ready(function(){ //Your code here 
   

$('#btnstore').on( "click", function() {
    var wh_id1 = document.getElementById('wh_id1').value;
    var wh_id2 = document.getElementById('wh_id2').value;
    var vendor_id1 = document.getElementById('vendor_id1').value;
    var vendor_id2 = document.getElementById('vendor_id2').value;
    var total = document.getElementById('totalcost').value;
    var shipcost = document.getElementById('shipcost').value;
    var bank_id = document.getElementById('bank_id').value;
    var delivery_id = document.getElementById('delivery_id').value;
    if(wh_id1 == wh_id2  )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Hai kho giống nhau!',
                    'error'
                ); 
            return;
        }
    importDoc = new ImportDoc(0,total,shipcost,bank_id,wh_id1,wh_id2,vendor_id1,vendor_id2,delivery_id);
    console.log(importDoc);
    const dataToSend = {
        _token: "<?php echo e(csrf_token()); ?>",
        importDoc: importDoc,
        products: productList
    };

    $.ajax({
        url: "<?php echo e(route('warehousetransfer.store')); ?>", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            if(response.status == true)
            {
                Swal.fire(
                            'Thành công',
                            response.msg,
                            'success'
                        ); 
                productList.length = 0;
                updateListView();
                return;
            }
            else
            {
                Swal.fire('Lỗi',
                            response.msg,
                            'error'
                        ); 
            }
           

        },
        error: function(error) {
        console.error("Error sending product list and customer_id:", error);
        }
    });
} );

/////////////////////////
///////discount change/////
  
      
    ////////////////////////////////////////////////
    // /////////warehouse change//////////////////////
    ///////////////////////////////////////////////
    
    var wh_id1 = $('#wh_id1');
    wh_id1.change(function(e){
        // alert('nunu');
        // e.preventDefault();
        productList.length=0;
        updateListView();
    });
    ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete
    ({
        source: function(request, response) {
            
            var warehouse_id = $('#wh_id1').val();
            var customer_id = $('#customer_id').val();
            // var idnhom = $('#selectgroupid').val();
            // console.log('warehouseid' + warehouse_id);
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.jsearchwf')); ?>',
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
                        type: item.type,
                        price: item.price,
                        imgurl: imageurls[0],
                        qty: item.quantity,
                        expired:item.expired,
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
                    
                    const newProduct = //(id,name, price, quantity,stock_quantity,url, series )
                    new Product(ui.item.id,ui.item.value, ui.item.price, ui.item.type, 1,ui.item.qty, ui.item.imgurl,'',data.series );
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
});   

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/warehousetransfers/create.blade.php ENDPATH**/ ?>