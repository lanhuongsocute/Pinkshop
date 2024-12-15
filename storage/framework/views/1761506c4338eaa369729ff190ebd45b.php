
<?php $__env->startSection('content'); ?>
<div class="content">
    <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Thông tin giao dịch mã: <?php echo e($banktrans->id); ?> 
                    </h2>
                   
    </div>
     <!-- BEGIN: Form Layout -->   
     
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <div class="lg:flex intro-y box py-5 px-5">
                    <div class='relative'> 
                        <div class="mt-3">
                        <label class="font-medium"> Tài khoản <?php echo e($banktrans->operation==-1?"chuyển": "nhận"); ?>: </label>
                        <span><?php echo e(\App\Models\Bankaccount::where('id',$banktrans->bank_id)->value('title')); ?></span>
                        </div>
                        
                        <div class="mt-3">
                        <label class="font-medium"> Số tiền: </label>
                        <span><?php echo e(Number_format($banktrans->total,0,'.',',')); ?></span>
                        </div>
                    </div>
                    
                    <div class=" mt-3  lg:w-auto   lg:mt-0 ml-auto" > 
                        <div class="mt-3">
                            <label class="font-medium"> Người thực hiện: </label>       
                            <?php echo e(\App\Models\User::find($banktrans->user_id)->value('full_name')); ?>

                            <br/>
                            <label class="font-medium"> Thời gian: </label>       
                            <?php echo e($banktrans->created_at); ?>

                            <br/>
                        </div>
                    </div>
                    <div class="mt-3 lg:w-auto   lg:mt-0 ml-auto">
                        <div  class="mt-3">
                            <label class="font-medium"> Hóa đơn tham chiếu: </label>
                            <?php
                                if($banktrans->doc_type =='wi')
                                {
                                    echo '<a class="font-medium" href ="'.route('warehousein.show',$banktrans->doc_id )
                                    .'"> phiếu nhập hàng: '.$banktrans->doc_id.'</a>';
                                }
                                if($banktrans->doc_type =='fi')
                                {
                                    echo '<a class="font-medium" href ="'.route('freetransaction.show',$banktrans->doc_id)
                                    .'"> phiếu thu chi: '.$banktrans->doc_id.'</a>';
                                }
                                if($banktrans->doc_type =='si')
                                {
                                    echo '<a class="font-medium" href ="'.route('suptransaction.show',$banktrans->doc_id)
                                    .'"> phiếu nạp tiền nhà cung cấp: '.$banktrans->doc_id.'</a>';
                                }
                                if($banktrans->doc_type =='wo')
                                {
                                    echo '<a class="font-medium" href ="'.route('warehouseout.show',$banktrans->doc_id)
                                    .'"> phiếu bán hàng: '.$banktrans->doc_id.'</a>';
                                }
                                if($banktrans->doc_type =='fo')
                                {
                                    echo 'đơn hàng đã hủy';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="mt-3">
                <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>    <?php echo e($error); ?> </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>                   
            
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
   
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/bankaccounts/show.blade.php ENDPATH**/ ?>