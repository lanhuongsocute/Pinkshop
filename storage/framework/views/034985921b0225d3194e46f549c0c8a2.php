
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách mod
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
    <a href="<?php echo e(route('modpro.create')); ?>" class="btn btn-primary shadow-md mr-2">Thêm module</a>
       
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
             
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($mods->currentPage()); ?> trong <?php echo e($mods->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                
            </div>
        </div>

       
        
   

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tiêu đề</th>
                        <th class="whitespace-nowrap">Thứ tự</th>
                        <th class="text-center whitespace-nowrap">Loại</th>
                        <th class="text-center whitespace-nowrap">Op</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $mods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        
                        <td>
                             <?php echo e($item->title); ?>  
                        </td>
                        <td class='text-center'>
                        <?php echo e($item->order_id); ?>  
                        </td>
                        <td class='text-center'>
                        <?php echo e($item->mod_type); ?>  
                        </td>
                         
                        <td class='text-center'>
                        <?php echo e($item->value('op_type')); ?>  
                        </td>
                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            <?php echo e($item->status=="active"?"checked":""); ?>

                            data-size="sm"
                            name="toogle"
                            value="<?php echo e($item->id); ?>"
                            data-style="ios">
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <div class="dropdown py-3 px-1 ">  
                                    <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                        hoạt động
                                    </a>
                                    <div class="dropdown-menu w-40"> 
                                        <ul class="dropdown-content">
                                        <li><a class="dropdown-item" href="<?php echo e(route('modpro.edit',$item->id)); ?>" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('modpro.addpro',$item->id)); ?>" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Thêm sản phẩm </a></li>
                                       
                                        
                                        <li>
                                            <form action="<?php echo e(route('modpro.destroy',$item->id)); ?>" method = "post">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('delete'); ?>
                                                <a class=" dropdown-item flex items-center text-danger dltBtn" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                            </form>
                                        </li>
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
                <?php echo e($mods->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
<!-- end content -->
  
   
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
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

    
  
  
    
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
<script>
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
    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"<?php echo e(route('modpro.status')); ?>",
            type:"post",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                mode:mode,
                id:id,
            },
            success:function(response){
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.msg,
                showConfirmButton: false,
                timer: 1000
                });
                console.log(response.msg);
            }
            
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/frontpromods/index.blade.php ENDPATH**/ ?>