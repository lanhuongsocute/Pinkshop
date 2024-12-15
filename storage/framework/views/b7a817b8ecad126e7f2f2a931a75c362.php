
<?php $__env->startSection('scriptop'); ?>
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
  <!-- Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form action="<?php echo e(route('report.sanpham')); ?>" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- <?php echo csrf_field(); ?> -->
                        <div class="sm:flex items-center sm:mr-4">
                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Sắp xếp: </label>
                            <select name="order_name" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                <option value="title" <?php echo e($order_name == "title"?"selected":""); ?>>&nbsp;&nbsp;Tên &nbsp;&nbsp;</option>
                                <option value="tiennhap" <?php echo e($order_name == "tiennhap"?"selected":""); ?>>&nbsp;&nbsp; Tiền nhập &nbsp;&nbsp;</option>
                                <option value="tienxuat" <?php echo e($order_name == "tienxuat"?"selected":""); ?>>&nbsp;&nbsp; Tiền xuất &nbsp;&nbsp;</option>
                                <option value="soluongnhap" <?php echo e($order_name == "soluongnhap"?"selected":""); ?>> Số lượng nhập </option>
                                <option value="soluongban" <?php echo e($order_name == "soluongban"?"selected":""); ?>> Số lượng bán </option>
                                <option value="tonkho" <?php echo e($order_name == "tonkho"?"selected":""); ?>> Tồn kho </option>
                                <option value="loinhuan" <?php echo e($order_name == "loinhuan"?"selected":""); ?>> Lợi nhuận </option>
                                <option value="id" <?php echo e($order_name == "id"?"selected":""); ?>> Thời gian </option>
                            </select>
                            <select name="order_type" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                <option value="asc" <?php echo e($order_type == "asc"?"selected":""); ?>>Tăng</option>
                                <option value="desc" <?php echo e($order_type == "desc"?"selected":""); ?>>Giảm</option>
                                 
                            </select>
                            <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Chọn</button>
                        </div>
                        <div class="mt-2 xl:mt-0">
                         
                        </div>
                        <div class="mt-2 xl:mt-0">
                                <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                        </div>
                    </form>
                    <div class="flex mt-5 sm:mt-0">
                        
                    </div>
                </div>   
            
                <div id="divprint" class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <div class="text-primary font-semibold text-3xl">DANH SÁCH SẢN PHẨM</div>
                            
                            <div class="mt-1">Ngày lập: <?php echo e(date('Y-m-d H:i:s')); ?></div>
                            
                        </div>
                        <?php   $i = 1;?>
                        <div class="col-span-12 lg:col-span-12">
                                 
                                <table id="myTable" class="display table" style="width:100%">
                                    <thead class="table-dark">
                                        <tr> <td> STT </td><td> Tên </td><td> tổng nhập </td><td> Tổng xuất </td><td> Tổng <br/>tồn kho </td>
                                        <td> SL nhập</td><td> SL xuất </td><td> tồn kho </td><td> lợi nhuận </td><td> % </td>
                                        </tr>
                                    </thead>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($item->id != null): ?>
                                        <tr>
                                            <td> <?php echo e($i); ?> </td>
                                            <td> <a   > <?php echo e($item->title); ?> </a></td>
                                            <td><span  > <?php echo e(number_format($item->tiennhap,0,".",",")); ?> </span> </td>
                                            <td><span  > <?php echo e(number_format($item->tienxuat,0,".",",")); ?> </span> </td>
                                            <td><span  > <?php echo e(number_format((( $item->tiennhap/$item->soluongnhap)*$item->tonkho  ),0,".",",")); ?> </span> </td>
                                            <td><span  > <?php echo e(number_format($item->soluongnhap,0,".",",")); ?> </span> </td>
                                            <td><span  > <?php echo e(number_format($item->soluongxuat,0,".",",")); ?> </span> </td>
                                            <td><span  class="<?php echo e($item->tonkho > 5?'text-danger':''); ?>"  > <?php echo e(number_format($item->tonkho,0,".",",")); ?> </span> </td>
                                            <td><span class="<?php echo e($item->loinhuan <0?'text-danger':''); ?>" > <?php echo e(number_format($item->loinhuan,0,".",",")); ?> </span> </td>
                                            <td><span  > <?php echo e($item->tiennhap?round($item->loinhuan*100/$item->tiennhap ):0); ?> </span> </td>
                                            
                                        </tr>
                                    <?php endif; ?>
                                    <?php $i ++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                    </div>
                   
                    <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
                        <table style="width:100%">
                            <tr>
                                <td style="width:50%">
                                    <div class="text-center sm:text-left mt-10 sm:mt-0">
                                        <div class="text-base text-slate-500">Người lập</div>
                                        <div class="mt-1">
                                            <br/>
                                            <br/>
                                            <br/>
                                        <?php echo e(\App\Models\User::where('id',auth()->user()->id)->value('full_name')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center sm:text-right sm:ml-auto" >
                                    </div>
                                
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <!-- JSZip (required for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- Buttons HTML5 export JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <!-- Buttons Print JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <!-- Buttons ColVis JS (optional, for column visibility control) -->
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
  let table = new DataTable('#myTable', {
        pageLength: 1000,
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        }
        
    });
   
</script>
<script>
$("#btnprint").on("click", function(){
        var divToPrint=document.getElementById('divprint');
        // alert(divToPrint.innerHTML);
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<link rel="stylesheet" '
        + 'href="<?php echo asset('backend/assets/dist/css/app.css') ?>" '
        + 'type="text/css"><style type="text/css"> .content2 { padding: 0px 0px;  position: relative;   min-height: 100vh; min-width: 0px;flex: 1 1 0%;--tw-bg-opacity: 1;background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity)); padding-top: 0rem;padding-bottom: 0rem;}'
        + ' @media print {.modal-dialog { max-width: 2000px;} }</style> '
        + '<body onload="window.print()"><div style="min-height:50px !important; margin-left: 0px !important; " class="content2">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},60);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/reports/sanpham.blade.php ENDPATH**/ ?>