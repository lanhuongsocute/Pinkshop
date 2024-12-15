
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách thu chi
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
    
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($suptrans->currentPage()); ?> trong <?php echo e($suptrans->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                  
                </div>
            </div>
        </div>
        
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
           
            <div class="flex mt-5 sm:mt-0">
                
            </div>
        </div>
       
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">LOẠI</th>
                        <th class="whitespace-nowrap">TÊN</th>
                        <th class="whitespace-nowrap">SỐ TIỀN</th>
                        <th class="whitespace-nowrap">TÀI KHOẢN</th>
                        <th class="text-center whitespace-nowrap">NGÀY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $suptrans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        <td>
                        <?php
                            if($item->operation == -1)
                            {
                                echo "nhập";
                            }
                            else{
                                echo "xuất";
                            }
                        ?>
                        </td>
                        <td>
                           <?php echo e($item->full_name); ?> 
                        </td>
                        <td class="text-right  <?php echo e($item->operation==-1?'text-danger':'text-primary'); ?> ">
                            <?php echo e(Number_format($item->amount,0,'.',',')); ?> 
                        </td>
                        <td>
                           <a href="<?php echo e(route('banktrans.show',$item->doc_id)); ?>"> <?php echo e($item->bankname); ?> 
                        </td>
                        
                        
                        <td class="text-center">
                            <?php echo e($item->created_at); ?>

                             
                        </td>
                        <td class="table-report__action ">
                         <a   href="<?php echo e(route('suptransaction.show',$item->id)); ?>" 
                                    class="flex items-center mr-3" href="javascript:;"> 
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem 
                        </a>
                        
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
                <?php echo e($suptrans->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
        
        
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
  
 
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/suptrans/list.blade.php ENDPATH**/ ?>