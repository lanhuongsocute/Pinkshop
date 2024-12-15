
<?php $__env->startSection('content'); ?>
<div class="content">
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Tạo kiểm kho
                    </h2>
                   
                </div>
                <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Item List -->
                    <div class="intro-y col-span-12  ">
                        <div class="lg:flex intro-y">
                            <div class="relative">
                                <input type="text" id='product_search' class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Tên sản phẩm ...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                            </div>
                            <!-- <button id="btn_shownew" class=" flex  py-3 px-4 box w-full lg:w-auto mt-3 lg:mt-0 ml-auto">
                                <i  data-lucide="box"> </i>
                                Thêm sản phẩm
                            </button>  -->
                            <div id="div_parent_id" class=" flex    px-4   w-full lg:w-auto mt-3 lg:mt-0 ml-auto  ">
                                <label style="min-width:50px  " class="form-select-label py-4"  >Kho</label>
                                <select id="wh_id" name="wh_id" class="form-control" aria-label="Default select example"   >
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($wh->id); ?>" <?php echo e(old('wh_id')==$wh->id?'selected':''); ?>><?php echo e($wh->title); ?></option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
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
                                                    <th class="whitespace-nowrap text-center">Số lượng</th>
                                                    <th class="whitespace-nowrap text-right">Số lượng thật</th>
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

                                <div class="intro-y box mt-3  py-3 px-3">
                            
                                    <div class="mt-3">
                                        <label style="min-width:50px  " class="form-select-label" for="">
                                        Người lập đơn: 
                                        </label> 
                                        <span class='font-medium'>
                                            <?php echo e(auth()->user()->full_name); ?>

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
                        </div>
                    </div>
                    <!-- END: Item List -->
                    
                </div>
</div>
     <!-- end content -->
          
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('backend/assets/js/sweetalert2@11.js')); ?>"></script>
<link href="<?php echo e(asset('backend/assets/css/jquery-ui.css')); ?>" rel="Stylesheet"> 
<script src="<?php echo e(asset('backend/assets/js/product_incheck.js')); ?>"></script> 
<script src="<?php echo e(asset('backend/assets/js/jquery-ui.js')); ?>" ></script>

 

<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});


var  productList=[];
var tong = 0;

$(document).ready(function(){ //Your code here 
   
    var wh_id = $('#wh_id');
    wh_id.change(function(e){
        e.preventDefault();
        productList.length=0;
        updateListView();
    });

$('#btnstore').on( "click", function() {
   
   
    var wh_id = document.getElementById('wh_id').value;
     
    
    importDoc = new ImportDoc(0,wh_id);
    console.log(importDoc);
    console.log(productList);
    const dataToSend = {
        _token: "<?php echo e(csrf_token()); ?>",
        importDoc: importDoc,
        products: productList
    };

    $.ajax({
        url: "<?php echo e(route('inventorycheck.store')); ?>", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            if(response.status == false)
            {
                Swal.fire(
                            'Lỗi',
                            response.msg,
                            'error'
                        ); 
                 
                 
            }
            else
            {
                console.log("Product list and supplier_id sent successfully:", response);
                Swal.fire(
                            'Thành công',
                            'nhập kho thành công!',
                            'success'
                        ); 
                productList.length = 0;
                updateListView();
                return;
            }
       

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
    product_search.autocomplete
    ({
        source: function(request, response) {
            var warehouse_id = $('#wh_id').val();
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.jsearchic')); ?>',
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
                url: '<?php echo e(route('product.groupprice')); ?>',
                data: {
                    product_id: ui.item.id,
                },
                success: function(data) {
                    console.log(data);
                    const newProduct = 
                    new Product(ui.item.id,ui.item.value, ui.item.price, ui.item.qty,ui.item.qty, ui.item.imgurl,data.series);
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
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>   '
            +'</td></tr></table>')
            .appendTo(ul);
        };
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////
    // /////////supplier search//////////////////////
    ///////////////////////////////////////////////
    var supplier_search = $('#supplier_search');
    supplier_search.autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('supplier.jsearch')); ?>',
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/inventorycheck/create.blade.php ENDPATH**/ ?>