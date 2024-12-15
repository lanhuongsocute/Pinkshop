
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách nhận bảo hành
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="<?php echo e(route('maintainin.create')); ?>" class="btn btn-primary shadow-md mr-2">Thêm nhận bảo hành</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang <?php echo e($maintainins->currentPage()); ?> trong <?php echo e($maintainins->lastPage()); ?> trang</div>
            
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">MÃ</th>
                        <th class="whitespace-nowrap">KHÁCH HÀNG</th>
                        <th class="whitespace-nowrap">SẢN PHẨM</th>
                        <th class="whitespace-nowrap">SL</th>
                        <th class="text-center whitespace-nowrap">CHI PHÍ</th>
                        <th class="text-center whitespace-nowrap">THANH TOÁN</th>
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                        <th class="text-center whitespace-nowrap">KẾT QUẢ</th>
                        <th class="text-center whitespace-nowrap">NGÀY LẬP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $maintainins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $classname = "text-danger";
                            if($item->status == 'sent')
                                $classname = "text-warning";
                            if($item->status == 'back')
                                $classname = "text-warning";
                            if($item->status == 'returned')
                                $classname = "text-warning";
                                if($item->status == 'finished')
                                $classname = "text-success";
                        ?>
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
                             <?php echo e($item->id); ?>

                        </td>
                        <td>
                            <a  class="tooltip "  title="Xem công nợ"  href="<?php echo e(route('customer.show',$item->customer_id)); ?>">  
                                    <?php echo e(\App\Models\User::where('id',$item->customer_id)->value('full_name')); ?>

                            </a>
                        </td>
                        <td>
                            <?php echo e(\App\Models\Product::where('id',$item->product_id)->value('title')); ?>

                        </td>
                        <td>
                            <?php echo e($item->quantity); ?>

                        </td>
                        <td>
                            <?php echo e($item->final_amount); ?>

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
                            <span class="<?php echo e($classname); ?>">
                                <?php echo e($item->status); ?>

                            </span>
                        </td>
                        <td>
                            <?php echo e($item->result); ?>

                        </td>
                        
                        <td>
                            <?php echo e($item->created_at); ?>

                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    hoạt động
                                </a>
                                <div class="dropdown-menu w-40"> 
                                    <ul class="dropdown-content">
                                        
                                         
                                        <li><a href="<?php echo e(route('maintainin.show',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                       <?php if($item->status != 'finished'): ?>
                                            <li><a onclick="save_newreturn(<?php echo e($item->id); ?>)"  class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Kết quả </a></li>
                                        <?php endif; ?>
                                        <?php if($item->status == 'returned'): ?>
                                        <li> 
                                            <a   class="dropdown-item flex items-center mr-3" href="<?php echo e(route('maintainin.viewfinish',$item->id)); ?>"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Hoàn khách </a>
                                      
                                        </li>
                                        <?php endif; ?>
                                        <?php if($item->paid_amount < $item->final_amount && $item->status == 'finished'): ?>
                                            <li> <a  onclick="on_paid(<?php echo e($item->id); ?>)" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i> Trả tiền </a></li> 
                                        <?php endif; ?>   
                                        <?php if($item->status == 'received'): ?>
                                            <li><a href="<?php echo e(route('maintainin.edit',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <?php endif; ?>
                                        <?php if($item->status == 'returned' || $item->status == 'finished'): ?>
                                            <li><a href="<?php echo e(route('maintainin.edit_paid',$item->id)); ?>" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <?php endif; ?>
                                        <?php if($item->status != 'returned' && $item->status != 'finished'): ?>
                                         
                                        <li> 
                                            <form action="<?php echo e(route('maintainin.destroy',$item->id)); ?>" method = "post">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                            <a class="dropdown-item flex items-center text-danger dltBtn" data-id="<?php echo e($item->id); ?>" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
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
                <?php echo e($maintainins->links('vendor.pagination.tailwind')); ?>

            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
    <!-- BEGIN: Modal   -->
<div  id="myModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thêm kết quả phiếu <span id='spid'></span></h2>    
                <input id="return_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Nhận xét</label>
                    <textarea   id="comment"   class="form-control" value ="">
                    </textarea>
                </div>
                    
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Chi phí</label>
                    <input id="maincost"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Kết quả</label>
                    <select id="result" class = "form-control">
                        <option value="pending">chưa xác định</option>
                        <option value="damaged">hư hỏng</option>
                        <option value="ok">tốt</option>
                    </select>
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_newreturn" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div>  
    <!-- BEGIN: Modal   -->
<div  id="myModalreturn" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Trả sản phẩm bảo hành phiếu <span id='spid0'></span></h2>    
                <input id="finish_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
            <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền phải trả:</label>
                    <br/>
                    <span id="sp_final_amount"> </span> 
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền nhận</label>
                    <input id="paid_amount"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tài khoản</label>
                    <select id="bank_id" name="bank" class="form-select mt-2 sm:mr-2"    >
                        <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bank->id); ?>" <?php echo e(old('bank_id')==$bank->id?'selected':''); ?>><?php echo e($bank->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-help">
                    * kiểm tra kỹ lại thông tin, sau khi bạn bấm Lưu, hàng hóa sẽ xuất kho bảo hành, hoàn trả cho khách nên không thể thay đổi!
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_finish" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div>  
     <!-- BEGIN: Modal   -->
<div  id="myModalpaid" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Trả tiền phiếu bảo hành <span id='spid1'></span></h2>    
                <input id="paid_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
            <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền phải trả:</label>
                    <br/>
                    <span id="sp_final_amount1"> </span> 
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền nhận</label>
                    <input id="paid_amount1"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tài khoản</label>
                    <select id="bank_id1" name="bank" class="form-select mt-2 sm:mr-2"    >
                        <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bank->id); ?>" <?php echo e(old('bank_id')==$bank->id?'selected':''); ?>><?php echo e($bank->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_finish1" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div> 
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')); ?>"></script>
<script>
  $('#comment').val('');
    const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModal"));
    const myModalreturn = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModalreturn"));
    const myModalpaid = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModalpaid"));
    function save_finish(id)
    { 
        var finish_id = document.getElementById('finish_id');
        finish_id.value = id;
       
        var spid = document.getElementById('spid0');
        spid.innerText = id;
        $.ajax({
            url:"<?php echo e(route('maintainin.getitem')); ?>",
            type:"get",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#sp_final_amount').text(Intl.NumberFormat().format( item.final_amount));
                     $('#paid_amount').val(item.paid_amount);
                    myModalreturn.show();
                }
            }
            
        });
    }
    
    function on_paid(id)
    { 
        var paid_id = document.getElementById('paid_id');
        paid_id.value = id;
       
        var spid = document.getElementById('spid1');
        spid.innerText = id;
        $.ajax({
            url:"<?php echo e(route('maintainin.getitem')); ?>",
            type:"get",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#sp_final_amount1').text(Intl.NumberFormat().format( item.final_amount - item.paid_amount));
                     $('#paid_amount1').val(item.final_amount - item.paid_amount);
                    myModalpaid.show();
                }
            }
            
        });
    }
    
    function save_newreturn(id)
    {
       
        var return_id = document.getElementById('return_id');
        return_id.value = id;
       
        var spid = document.getElementById('spid');
        spid.innerText = id;
        $.ajax({
            url:"<?php echo e(route('maintainin.getitem')); ?>",
            type:"get",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#comment').val(item.comment);
                     $('#maincost').val(item.maincost);
                    $('#result').val(item.result);
                    
                     myModal.show();
                }
            }
            
        });
    }
    $( "#btn_newreturn" ).on( "click", function() {
        myModal.hide();
        var id = $('#return_id').val();
        var comment = $('#comment').val();
        var maincost = $('#maincost').val();
        var result = $('#result').val();
        if(maincost <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"<?php echo e(route('maintainin.savereturn')); ?>",
            type:"post",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                comment:comment,
                maincost:maincost,
                result:result,
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );

    $( "#btn_finish1" ).on( "click", function() {
        myModalreturn.hide();
        var id = $('#paid_id').val();
        var paid_amount = $('#paid_amount1').val();
        var bank_id = $('#bank_id1').val();
        if(paid_amount <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"<?php echo e(route('maintainin.storepaid')); ?>",
            type:"post",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                paid_amount:paid_amount,
                bank_id:bank_id,
                 
            },
            success:function(response){
                console.log(response);
                myModalpaid.hide();
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );

    $( "#btn_finish" ).on( "click", function() {
        myModalreturn.hide();
        var id = $('#finish_id').val();
        var paid_amount = $('#paid_amount').val();
        var bank_id = $('#bank_id').val();
        if(paid_amount <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"<?php echo e(route('maintainin.savefinish')); ?>",
            type:"post",
            data:{
                _token:'<?php echo e(csrf_token()); ?>',
                id:id,
                paid_amount:paid_amount,
                bank_id:bank_id,
                 
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );
</script>
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
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/maintainins/index.blade.php ENDPATH**/ ?>