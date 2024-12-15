
 
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm bớt sản phẩm
        </h2>
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tên</th>
                        <th class="whitespace-nowrap">Vị trí</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $prodetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        <td>
                             <?php echo e(\App\Models\Product::find($item->pro_id)->title); ?>  
                        </td>
                        <td>
                             <?php echo e($item->order_id); ?>  
                        </td>
                        
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <div class="dropdown py-3 px-1 ">  
                                <a class="dropdown-item" href="<?php echo e(route('modpro.up',[$item->id,$frontmod->id])); ?>" class="flex items-center mr-3" href="javascript:;">   Tăng vị trí</a> 
                                <br/> 
                                <a class="dropdown-item" href="<?php echo e(route('modpro.down',[$item->id,$frontmod->id])); ?>" class="flex items-center mr-3" href="javascript:;">   Giảm vị trí</a> 
                                <br/>   
                                <a class="dropdown-item" href="<?php echo e(route('cproduct.priceview',[$item->pro_id,$frontmod->id])); ?>" class="flex items-center mr-3" href="javascript:;">   Thiết lập giá</a> 
                                     
                                            <form action="<?php echo e(route('modpro.removepro' )); ?>" method = "post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="pro_id" value="<?php echo e($item->pro_id); ?>"/>
                                                <input type="hidden" name="mod_id" value="<?php echo e($item->mod_id); ?>"/>
                                                <a class=" dropdown-item flex items-center text-danger dltBtn" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                            </form>
                                         
                                     
                                </div>   
                               
                            </div>
                        </td>
                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </tbody>
            </table>
            
        </div>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="mt-3">
                <label for="regular-form-1" class="form-label">Tìm sản phẩm</label>
                <div  class=' ' >
                    <input type="text" id='product_search' class="form-control " placeholder="Tên sản phẩm ...">
                    
                </div>
            </div>
            <div class="mt-3">
                <table id='dsspthem' style="display:none; width:100%" class='table table-bordered'> 
                    <thead  style="  width:100%">
                        <tr>
                        
                            <th colspan='2'  >Hàng hóa</th>
                                
                            
                        </tr>
                    </thead>
                    <tbody id='product_list_table'>
                        
                        
                    </tbody>
                    <tfoot id='table_footer'>
                    </tfoot>
                </table>
                        
               
            </div>
            <div class="mt-3">
                <button id="btnstore" class="btn btn-primary w-44">
                    
                    Lưu
                </button> 
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/js/product_v4.js')); ?>"></script> 
<script>
    
$('#btnstore').on( "click", function() {
    
     if (productList.length  == 0)
    {
        Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có sản phẩm!',
                    'error'
                ); 
               
        return;
    }
   
     
    const dataToSend = {
        _token: "<?php echo e(csrf_token()); ?>",
        products: productList,
        mod_id:<?php echo e($frontmod->id); ?>

    };

    $.ajax({
        url: "<?php echo e(route('modpro.savepro')); ?>", // Replace with your actual server endpoint URL
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(dataToSend),
        success: function(response) {
            var json =        response;
            console.log(json);
            if(json.status == true)
            {
                Swal.fire(
                            'Thành công',
                            'thêm sản phẩm thành công!',
                            'success'
                        ); 
                // productList.length = 0;
                // updateListView();
                location.reload();
                return;
            }
            else
            {
                Swal.fire(
                            'Lỗi xãy ra',
                            json.msg,
                            'error'
                        ); 
            }

        },
        error: function(error) {
        console.error("Error sending product list and supplier_id:", error);
        }
    });
} );

$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});
 
    $('.dltBtn').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa không?',
            text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
            if (result.isConfirmed) {
                // alert(form);
                form.submit();
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
            }
        });
    });

var  productList=[];
var tong = 0;

      ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
           
            
            // var idnhom = $('#selectgroupid').val();
            // console.log('warehouseid' + warehouse_id);
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('product.productjmodsearch')); ?>',
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
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            const newProduct = 
            new Product(ui.item.id,ui.item.value, ui.item.price, 1,ui.item.imgurl,null);
            if(!addtoProductList(newProduct))
            {
                Swal.fire(
                    'Không thực hiện!',
                    'Sản phẩm đã có!',
                    'error'
                );
                $('#product_search').val('');
            }
            updateListView();
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li  ></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<div class="flex"><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/><span >'+ item.value 
            
            +' </div>')
            .appendTo(ul);
        };;
    //////////end product search /////////////////////////
  ////////////////////////////////////////////////


</script>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/frontpromods/addpro.blade.php ENDPATH**/ ?>