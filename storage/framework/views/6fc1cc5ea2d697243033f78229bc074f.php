
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách giao dịch tài khoản
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
    
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($banktrans->currentPage()); ?> trong <?php echo e($banktrans->lastPage()); ?> trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                  
                </div>
            </div>
        </div>
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form action="<?php echo e(route('banktransaction.sort')); ?>" method = "get" class="xl:flex sm:mr-auto" >
                <!-- <?php echo csrf_field(); ?> -->
                <div class="sm:flex items-center sm:mr-4">
                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                    <select name="bank_id" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="0">Chọn tài khoản</option>
                        <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bank->id); ?>" <?php echo e($bank->id==$bank_id?'selected':''); ?> ><?php echo e($bank->title); ?></option>
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
        <?php if($bank_id != 0): ?>
        <div class=" intro-y col-span-12 ">  
            <h3  > Số dư đầu kỳ - cuối kỳ : <?php echo e(number_format($pre_balance,0,",",".")); ?> - <?php echo e(number_format($end_balance,0,",",".")); ?> </h3> 
      
    </div>
        <?php endif; ?>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">TÀI KHOẢN</th>
                        <th  class="text-right">SỐ TIỀN</th>
                        <th class="text-center whitespace-nowrap">LOẠI</th>
                        <th  class="text-right">SỐ DƯ</th>
                        <th class="text-center whitespace-nowrap">HÓA ĐƠN</th>
                        <th class="text-center whitespace-nowrap">NGÀY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $banktrans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="intro-x">
                        <td>
                           <?php echo e(\App\Models\Bankaccount::where('id',$item->bank_id)->value('title')); ?> 
                        </td>
                        <td class="text-right">
                            <span class="<?php echo e($item->operation==-1?"text-danger":"text-success"); ?>">
                           <?php echo e(number_format($item->total,0,'.',',')); ?> 
                            </span>
                        </td>
                        <td class="text-right">
                           <?php echo e($item->operation==1?'thu':'chi'); ?> 
                        </td>
                        <td class="text-right">
                        <?php echo e(number_format($item->total*$item->operation +$item->pre_balance ,0,'.',',')); ?> 
                        </td>
                         <td class="text-right">
                            <?php
                                if($item->doc_type=='wi')
                                {
                                    echo '<a class="font-medium" href ="'.route('warehousein.show',$item->doc_id)
                                    .'"> phiếu nhập: '.$item->doc_id.'</a>';
                                }
                                if($item->doc_type=='wo')
                                {
                                    echo '<a class="font-medium" href ="'.route('warehouseout.show',$item->doc_id)
                                    .'"> phiếu xuất: '.$item->doc_id.'</a>';
                                }
                                if($item->doc_type=="fi")
                                {
                                    if($item->operation == -1)
                                    {
                                        echo '<a class="font-medium" href ="'.route('freetransaction.show',$item->doc_id)
                                        .'"> phiếu chi: '.$item->doc_id.'('.
                                        \App\Models\FreeTransaction::where('id',$item->doc_id)->value('content').')</a>';
                                    }
                                    else{
                                        echo '<a class="font-medium" href ="'.route('freetransaction.show',$item->doc_id)
                                        .'"> phiếu thu: '.$item->doc_id.'('.
                                        \App\Models\FreeTransaction::where('id',$item->doc_id)->value('content').')</a>';
                                    }
                                  
                                }
                                if($item->doc_type =='si')
                                {
                                    if($item->operation == -1)
                                    { 
                                        echo '<a class="font-medium" href ="'.route('suptransaction.show',$item->doc_id)
                                        .'"> phiếu chi tiền khách hàng/nhà cung cấp: '.$item->doc_id.'</a>';

                                    }
                                    else
                                    {
                                        echo '<a class="font-medium" href ="'.route('suptransaction.show',$item->doc_id)
                                        .'"> phiếu thu tiền khách hàng/nhà cung cấp: '.$item->doc_id.'</a>';
                                    }
                                   
                                }
                                if($item->doc_type =='fo')
                                {
                                    echo 'đơn hàng đã hủy';
                                }
                            ?>
                        </td>
                         
                        <td class="table-report__action w-56">
                              <?php echo e($item->created_at); ?>

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
                <?php echo e($banktrans->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
        <?php if($bank_id != 0): ?>
                <div><h3> Số dư cuối đầu kỳ : <?php echo e(number_format($end_balance,0,",",".")); ?> </h3> </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
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
</script>
<script>
     

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"<?php echo e(route('bankaccount.status')); ?>",
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
<!-- <script>

// Example usage of the Product class
const product1 = new Product("Laptop", 1000, 2);
product1.displayInfo(); // Output the product information

// Update price and quantity
product1.updatePrice(1200);
product1.updateQuantity(3);
product1.displayInfo(); 
</script> -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/bankaccounts/transview.blade.php ENDPATH**/ ?>