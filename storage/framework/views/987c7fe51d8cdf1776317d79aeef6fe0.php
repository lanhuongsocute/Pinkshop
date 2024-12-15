
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách thu chi
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
    
            <a href="<?php echo e(route('freetransaction.create')); ?>" class="btn btn-primary shadow-md mr-2">Thêm thu chi</a>
           
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($freetrans->currentPage()); ?> trong <?php echo e($freetrans->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                  
                </div>
            </div>
        </div>
        
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form action="<?php echo e(route('freetransaction.sort')); ?>" method = "get" class="xl:flex sm:mr-auto" >
                <!-- <?php echo csrf_field(); ?> -->
                <div class="sm:flex items-center sm:mr-4">
                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                    <select name="operation" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="0">-thu/chi-</option>
                        <option value="1" <?php echo e($operation==1?'selected':''); ?>>thu</option>
                        <option value="-1" <?php echo e($operation==-1?'selected':''); ?>>chi</option>
                    </select>
                </div>
                <div class="sm:flex items-center sm:mr-4">
                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                    <select name="type_id" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="-1">-thể loại-</option>
                        <option value="0" <?php echo e(0 == $type_id?'selected':''); ?>>tự động</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <option value="<?php echo e($type->id); ?>" <?php echo e($type->id == $type_id?'selected':''); ?>><?php echo e($type->title); ?></option>
                        
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </select>
                </div>
                <?php $curYear = date('Y'); ?>
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Thời gian</label>
                    <select name="select_year" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                        <option value="0" selected>-chọn năm-</option>
                        <option value="<?php echo e($curYear); ?>" <?php echo e($curYear==$select_year?'selected':''); ?>>2024</option>
                    </select>
                    <select name="select_month" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                        <option value="0" selected>-chọn tháng-</option>
                        <?php for($i = 1; $i < 13; $i++): ?>
                            <option value ="<?php echo e($i); ?>" <?php echo e($i==$select_month?'selected':''); ?> > tháng <?php echo e($i); ?> </option>
                        <?php endfor; ?>
                    </select>
                    <select name="select_day" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                        <option value="0" selected>-chọn ngày-</option>
                        <?php for($i = 1; $i <= 31; $i++): ?>
                            <option value ="<?php echo e($i); ?>" <?php echo e($i==$select_day?'selected':''); ?>> ngày <?php echo e($i); ?> </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mt-2 xl:mt-0">
                    <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Chọn</button>
                </div>
            </form>
            <div class="flex mt-5 sm:mt-0">
                
            </div>
        </div>
       
        <div class=" intro-y col-span-12 ">  
            <h3  >Tổng thu - chi : <?php echo e(number_format($final_balance,0,",",".")); ?>   </h3> 
      
        </div>
      
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">PHÂN LOẠI</th>
                        <th class="whitespace-nowrap">NỘI DUNG</th>
                        <th class="whitespace-nowrap">SỐ TIỀN</th>
                        <th class="whitespace-nowrap">TÀI KHOẢN</th>
                        <th class="text-center whitespace-nowrap">LOẠI PHIẾU</th>
                        <th class="text-center whitespace-nowrap">NGƯỜI LẬP</th>
                        <th class="text-center whitespace-nowrap">NGÀY</th>
                        <th class="text-center whitespace-nowrap"> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $freetrans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        <td>
                        <?php
                            if($item->type_id == 0)
                            {
                                echo "tự động";
                            }
                            else{
                                echo \App\Models\FreetransType::find($item->type_id)->title;
                            }
                        ?>
                         
                        </td>
                        <td>
                           <?php echo e($item->content); ?> 
                        </td>
                        <td class="text-right  <?php echo e($item->operation==-1?'text-danger':'text-primary'); ?> ">
                            <?php echo e(Number_format($item->total,0,'.',',')); ?> 
                        </td>
                        <td>
                           <?php echo e(\App\Models\Bankaccount::find($item->bank_id)->title); ?> 
                        </td>
                        <td class="text-center  <?php echo e($item->operation==-1?'text-danger':'text-primary'); ?> ">
                           <?php echo e($item->operation==1?'thu':'chi'); ?> 
                        </td>
                         <td class="text-center">
                         <?php echo e(\App\Models\User::find($item->user_id)->full_name); ?> 
                             
                        </td>
                        <td class="text-center">
                            <?php echo e($item->created_at); ?>

                             
                        </td>
                        <td class="table-report__action ">
                         <a   href="<?php echo e(route('freetransaction.show',$item->id)); ?>" 
                                    class="flex items-center mr-3" href="javascript:;"> 
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem 
                        </a>
                        <?php if(auth()->user()->role =="admin"): ?>
                         
                            <a href="<?php echo e(route('freetransaction.edit',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> 
                            <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                        
                        <?php endif; ?>    
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
                <?php echo e($freetrans->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
        
        <div class=" intro-y col-span-12 ">  
            <h3  >Tổng thu - chi : <?php echo e(number_format($final_balance,0,",",".")); ?>   </h3> 
        </div>
        
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
  
 
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/freetransactions/index.blade.php ENDPATH**/ ?>