
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách bán hàng
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="<?php echo e(route('warehouseout.create')); ?>" class="btn btn-primary shadow-md mr-2">Thêm bán hàng</a>
         
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($warehouseouts->currentPage()); ?> trong <?php echo e($warehouseouts->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
               
                 
            </div>
        </div>
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form action="<?php echo e(route('warehouseout.index')); ?>" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- <?php echo csrf_field(); ?> -->
                        <div class="sm:flex items-center sm:mr-4">
                                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                                         
                                        <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                        <input name="date1" value = "<?php echo e($date1); ?>" type="text" class="datepicker form-control w-56 block mx-auto" data-single-mode="true"> 
                                         -  
                                        <input name="date2" value ="<?php echo e($date2); ?>" type="text" class="datepicker form-control w-56 block mx-auto" data-single-mode="true"> 

                                       
                                            
                                            <input type="text" id='customer_search' 
                                                class="form-control    " placeholder="Tên hoặc số điện thoại" value ="<?php echo e(isset($customer_id)&& $customer_id > 0 && \App\Models\User::find($customer_id)?\App\Models\User::find($customer_id)->full_name:''); ?>">
                                            <input type="hidden" id="customer_id" name="customer_id" value="<?php echo e(isset($customer_id)?$customer_id:0); ?>" />
                                        </div>
                            
                            <button id="btn_tim" type="submit" class="btn btn-primary w-full sm:w-16" >Chọn</button>
                        </div>
                        
                         
                    </form>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">CODE</th>
                        <th class="whitespace-nowrap">KHÁCH HÀNG</th>
                        <th class="whitespace-nowrap">KHO</th>
                        <th class="text-center whitespace-nowrap">SỐ TIỀN</th>
                        <th class="text-center whitespace-nowrap">ĐÃ THANH TOÁN</th>
                        <th class="text-center whitespace-nowrap">NGÀY LẬP</th>
                        <th class="text-center whitespace-nowrap">HĐ ĐIỆN TỬ</th>
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $warehouseouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                                if($item->final_amount != 0)
                                    $temp = (int)(($item->paid_amount/$item->final_amount)*100);
                                else
                                    $temp = 0;
                                if($temp==0)
                                    $temp = 1;
                                $class_p = "";
                                if($temp < 50)
                                {
                                    $class_p = "bg-danger";
                                }
                                else
                                {
                                    if($temp < 100)
                                    {
                                        $class_p ="bg-warning";
                                    }
                                }
                    ?>
                    <tr class="intro-x ">
                        <td>
                        <a href="<?php echo e(route('warehouseout.show',$item->id)); ?>" title="Xem chi tiết" class=" tooltip" href="javascript:;">
                            <?php echo e($item->code); ?>

                        </a>
                        </td>
                        <td>
                            
                           <a  class="tooltip "  title="Xem công nợ"  href="<?php echo e(route('user.showsup',$item->customer_id)); ?>"> <?php echo e(\App\Models\User::where('id',$item->customer_id)->value('full_name')); ?>

                            </a>
                            
                        </td>
                        <td>
                             
                            <?php echo e(\App\Models\Warehouse::where('id',$item->wh_id)->value('title')); ?>

                            
                        </td>
                        <td class="text-right">
                            
                            <?php echo e(number_format($item->final_amount, 0, '.', ',')); ?>

                            
                        </td>
                        <td class="text-right">
                           
                            <div class="progress h-6 mt-3">
                                <div class="progress-bar <?php echo e($class_p); ?> " role="progressbar" style="  width:<?php echo e($temp); ?>%"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                 <?php echo e(number_format($item->paid_amount, 0, '.', ',')); ?> 
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php echo e($item->created_at); ?>

                        </td>
                        <td>
                           <?php echo e($item->is_global==0?"không":"có"); ?>

                        </td>
                        <td>
                           <span class="<?php echo e($item->status =='returned' || $item->status =='deleted' ?'text-danger':''); ?>">
                             <?php echo e($item->status); ?>

                            </span>
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    hoạt động
                                </a>
                                <div class="dropdown-menu w-40"> 
                                    <ul class="dropdown-content">
                                        <?php
                                        if($item->is_paid == false && $item->status == 'active')
                                        {
                                            echo '<li> <a href=" '. route('warehouseout.paid',$item->id).'" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i> Trả tiền </a></li>';
                                        }
                                        ?>
                                        <li><a href="<?php echo e(route('warehouseout.new',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="copy" class="w-4 h-4 mr-1"></i> Sao chép đơn </a></li>
                                        <li><a href="<?php echo e(route('warehouseout.show',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                        <?php if($item->status == 'active'): ?>
                                            <li><a href="<?php echo e(route('warehouseout.deprint',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="printer" class="w-4 h-4 mr-1"></i> phiếu gửi hàng </a></li>
                                            <li><a href="<?php echo e(route('warehouseout.edit',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                            <li> 
                                                <form action="<?php echo e(route('warehouseout.publishitcctv')); ?>" method = "post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?php echo e($item->id); ?>"/>
                                                    <a class="dropdown-item flex items-center   publishinv" data-id="<?php echo e($item->id); ?>" href="javascript:;"  > <i data-lucide="sunrise" class="w-4 h-4 mr-1" data-tw-target="#delete-confirmation-modal"></i>Tạo hóa đơn điện tử</a>
                                                </form>
                                            </li>
                                            <li> 
                                                <form action="<?php echo e(route('warehouseout.destroy',$item->id)); ?>" method = "post">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('delete'); ?>
                                                    <a class="dropdown-item flex items-center text-danger dltBtn" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                                </form>
                                            </li>
                                            <li> 
                                                <form action="<?php echo e(route('warehouseout.return' )); ?>" method = "post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value = "<?php echo e($item->id); ?>"/>
                                                    <a class="dropdown-item flex items-center text-danger dltBtn1" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="git-branch" class="w-4 h-4 mr-1"></i> Trả hàng </a>
                                                </form>
                                            </li>
                                            <!-- <li> 
                                                <form action="<?php echo e(route('warehouseout.returnnew' )); ?>" method = "post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value = "<?php echo e($item->id); ?>"/>
                                                    <a class="dropdown-item flex items-center text-danger dltBtn1" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="git-branch" class="w-4 h-4 mr-1"></i> Trả hàng tạo lại đơn</a>
                                                </form>
                                            </li> -->
                                            <li> 
                                                <form action="<?php echo e(route('warehouseout.returnall' )); ?>" method = "get">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value = "<?php echo e($item->id); ?>"/>
                                                    <a class="dropdown-item flex items-center text-danger dltBtn2" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="git-branch" class="w-4 h-4 mr-1"></i> Trả hàng hoàn tiền</a>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div> 
                            </div> 
                            </div>
                        </td>
                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </tbody>
            </table>
            
        </div>
    </div>
    <!-- END: HTML Table Data -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <?php echo e($warehouseouts->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
  
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
 
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="<?php echo e(asset('backend/assets/js/product_selling.js')); ?>"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    
    $('.publishinv').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có muốn cho phép truy cập hóa đơn từ xa?',
            text: "Hóa đơn sau khi đẩy lên hệ thống không nên chỉnh sửa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn!'
            }).then((result) => {
            if (result.isConfirmed) {
                // alert(form);
                form.submit();
             
            }
        });
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

    $('.dltBtn1').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn trả hàng?',
            text: "Bạn không thể thay đổi sau khi trả hàng",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn trả hàng!'
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

    $('.dltBtn2').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn trả hàng và hoàn tiền?',
            text: "Bạn không thể thay đổi sau khi trả hàng",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn trả hàng!'
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
</script>
<script>
    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
           
            // Do something
            var data=$(this).val();
            var form=$(this).closest('form');
            if(data.length > 0)
            {
                form.submit();
            }
            else
            {
                  Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });


      // /////////customer search//////////////////////
    ///////////////////////////////////////////////
    var customer_search = $('#customer_search');
    customer_search.autocomplete({
        source: function(request, response) {
            $('#btn_tim').prop('disabled', true);
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
           $('#btn_tim').prop('disabled', false);
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

  
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/warehouseouts/index.blade.php ENDPATH**/ ?>