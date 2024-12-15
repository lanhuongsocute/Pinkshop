
<?php $__env->startSection('content'); ?>

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm tồn kho 
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
             
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($inventorys->currentPage()); ?> trong <?php echo e($inventorys->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="<?php echo e(route('inventory.search')); ?>" method = "get">
                        <?php echo csrf_field(); ?>
                        <input type="text" name="datasearch" value="<?php echo e($searchdata); ?>" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>
      
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">SẢN PHẨM</th>
                        <th class="text-center whitespace-nowrap">KHO</th>
                        <th class="text-center whitespace-nowrap">SỐ LƯỢNG</th>
                        
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $inventorys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        <td>
                        <a href="<?php echo e(route('inventory.view',$item->id)); ?>">     <?php echo e(\App\Models\Product::where('id',$item->product_id)->value('title')); ?>  </a>
                        </td>
                        <td>
                             <?php echo e(\App\Models\Warehouse::where('id',$item->wh_id)->value('title')); ?>  
                        </td>
                        <td class='text-center'>
                             <?php echo e($item->quantity); ?>  
                        </td>
                        
                         
                        
                        
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                              
                               
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
                <?php echo e($inventorys->links('vendor.pagination.tailwind')); ?>

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
<!-- script for modal -->
 
<!-- end script for modal -->
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/inventories/search.blade.php ENDPATH**/ ?>