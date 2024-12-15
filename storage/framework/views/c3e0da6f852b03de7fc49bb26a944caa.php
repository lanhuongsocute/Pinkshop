
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-12">
                        <div class="grid grid-cols-12 gap-6 mt-8">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 ">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                         Báo cáo thu chi
                                    </h2>
                                </div>
                                <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                                    <form action="<?php echo e(route('report.thuchi')); ?>" method = "get" class="xl:flex sm:mr-auto" >
                                        <!-- <?php echo csrf_field(); ?> -->
                                        <div class="sm:flex items-center sm:mr-4">
                                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5"> Chọn nhanh </label>
                                            <select name="time" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                                <option value="today" <?php echo e($time=="today"?"selected":""); ?>>hôm nay</option>
                                                <option value="week" <?php echo e($time=="week"?"selected":""); ?>>7 ngày</option>
                                                <option value="30ngay" <?php echo e($time=="30ngay"?"selected":""); ?>>30 ngày</option>
                                                <option value="hangthang" <?php echo e($time=="hangthang"?"selected":""); ?>>cả năm</option>
                                                 
                                            </select>
                                        </div>
                                        <?php $curYear = date('Y'); ?>
                                        <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                            <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">hoặc chọn thời gian</label>
                                            <select name="select_year" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                                                <option value="0" selected>-chọn năm-</option>
                                                <option value="<?php echo e($curYear); ?>" <?php echo e($curYear==$select_year?'selected':''); ?>><?php echo e($curYear); ?></option>
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
                                            <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Go</button>
                                        </div>
                                    </form>
                                    
                                    <div class="flex mt-5 sm:mt-0">
                                        <a href="<?php echo e(route('product.print')); ?>" id="tabulator-print" class="btn btn-outline-secondary w-1/2 sm:w-auto mr-2"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                                        
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-5">
                                    <?php
                                        $tongbanhang = 0;
                                        $tongnhaphang = 0;
                                        
                                        foreach($thungays as $item)
                                        {
                                            $tongbanhang += $item->tongthu;
                                        }
                                        foreach($chingays as $item)
                                        {
                                            $tongnhaphang += $item->tongchi;
                                        }
                                    ?>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($tongthuchi,0,'.',',')); ?></div>
                                                <div class="text-base text-slate-500 mt-1">Tổng thu chi ngoài hàng hóa</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($tongbanhang,0,'.',',')); ?></div>
                                                <div class="text-base text-slate-500 mt-1">Tổng bán hàng</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($tongnhaphang,0,'.',',')); ?></div>
                                                <div class="text-base text-slate-500 mt-1">Tổng nhập hàng</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($tongbanhang-$tongnhaphang+$tongthuchi,0,'.',',')); ?></div>
                                                <div class="text-base text-slate-500 mt-1">bán - nhập + thu chi</div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <!-- END: General Report -->
                            <!-- BEGIN: Sales Report -->
                            <div class="col-span-12 lg:col-span-12  ">
                                <div class="intro-y block sm:flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Báo cáo bán hàng
                                    </h2>
                                    <!-- <div class="sm:ml-auto mt-3 sm:mt-0 relative text-slate-500">
                                        <i data-lucide="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i> 
                                        <input type="text" class="datepicker form-control sm:w-56 box pl-10">
                                    </div> -->
                                </div>
                                <div class="intro-y box p-5 mt-12 sm:mt-5">
                                  
                                    <h3> Thu chi </h3>
                                    <div class="report-chart3">
                                        <div class=" " id="container_div3">
                                          </div>
                                          <div id="columnchart_material3" ></div>
                                    </div>
                                    <h3> Thu theo loại </h3>
                                    <div class="report-chart1">
                                        <div class=" " id="container_div1">
                                          </div>
                                          <div id="columnchart_material1" ></div>
                                    </div>
                                    <h3> Chi theo loại </h3>
                                    <div class="report-chart2">
                                        <div class=" " id="container_div2">
                                          </div>
                                          <div id="columnchart_material2" ></div>
                                    </div>
                                    <h3> Bán hàng  </h3>
                                    <div class="report-chart1">
                                        <div class=" " id="container_div4">
                                          </div>
                                          <div id="columnchart_material4" ></div>
                                    </div>
                                    <h3> Nhập hàng  </h3>
                                    <div class="report-chart2">
                                        <div class=" " id="container_div5">
                                          </div>
                                          <div id="columnchart_material5" ></div>
                                    </div>
                                </div>
                            </div>
                           
                           
                            <!-- END: Weekly Top Products -->
                        </div>
                    </div>
                     
                    
                </div>
</div>
<link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
<link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // const myDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#mydropdown"));
    google.charts.load('current', {'packages':['bar'],'language': 'vi'});
    google.charts.load('current', {'packages':['corechart'],'language': 'vi'});
    google.charts.setOnLoadCallback(drawChart);

    
function drawChart( ) {
        var f_data1 = new Array();
        var f_data2 = new Array();
        var f_data3 = new Array();
        var f_data4 = new Array();
        var f_data5 = new Array();
        var sx = new Array();
        sx.push("Giờ");
        sx.push("Thu");
        f_data1.push(sx);
     
        var i = 0;
        <?php $__currentLoopData = $thuloais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            var sx = new Array();
            sx.push("" + '<?php echo e($adetail->typetitle); ?>');
            sx.push(<?php echo e($adetail->thu); ?>*1);
            f_data1.push(sx);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           
         
        sx = new Array();
        sx.push("Giờ");
        sx.push("Số đơn");
        f_data2.push(sx);
        i = 0;
        <?php $__currentLoopData = $chiloais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            sx = new Array();
            sx.push("" + '<?php echo e($adetail->typetitle); ?>');
            sx.push(<?php echo e($adetail->chi); ?>*1);
            f_data2.push(sx);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        sx = new Array();
        sx.push("Giờ");
        sx.push("Thu chi");
        f_data3.push(sx);
        i = 0;
        <?php $__currentLoopData = $thuchingay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            sx = new Array();
            sx.push("" + '<?php echo e($adetail->ngay); ?>');
            sx.push(<?php echo e($adetail->thuchi); ?>*1);
            f_data3.push(sx);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
         
        sx = new Array();
        sx.push("Giờ");
        sx.push("bán hàng");
        f_data4.push(sx);
        i = 0;
        <?php $__currentLoopData = $thungays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            sx = new Array();
            sx.push("" + '<?php echo e($adetail->ngay); ?>');
            sx.push(<?php echo e($adetail->tongthu); ?>*1);
            f_data4.push(sx);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

        sx = new Array();
        sx.push("Giờ");
        sx.push("nhập hàng");
        f_data5.push(sx);
        i = 0;
        <?php $__currentLoopData = $chingays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            sx = new Array();
            sx.push("" + '<?php echo e($adetail->ngay); ?>');
            sx.push(<?php echo e($adetail->tongchi); ?>*1);
            f_data5.push(sx);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

         
        var data1 = google.visualization.arrayToDataTable(f_data1);
        var data2 = google.visualization.arrayToDataTable(f_data2);
        var data3 = google.visualization.arrayToDataTable(f_data3);
        var data4 = google.visualization.arrayToDataTable(f_data4);
        var data5 = google.visualization.arrayToDataTable(f_data5);
        var options1 = {
            chart: {
            title: 'Báo cáo doanh thu',
            },
            seriesType: 'bars',
            series: {1: {type: 'line'}},
            vAxis: {format: 'short'},
            height: 400,
            colors: ['#1b9e77' ,'#d95f02' ]
        };
        var options2 = {
            chart: {
            title: 'Báo cáo doanh thu',
            },
            seriesType: 'bars',
            series: {1: {type: 'line'}},
            vAxis: {format: 'short'},
            height: 400,
            colors: ['#d95f02'  ]
        };
        
        var chart1 = new google.visualization.ComboChart(document.getElementById('columnchart_material1'));
        chart1.draw(data1,  options1);
        var chart2 = new google.visualization.ComboChart(document.getElementById('columnchart_material2'));
        chart2.draw(data2,  options2);
        var chart3 = new google.visualization.ComboChart(document.getElementById('columnchart_material3'));
        chart3.draw(data3,  options1);
        var chart4 = new google.visualization.ComboChart(document.getElementById('columnchart_material4'));
        chart4.draw(data4,  options1);
        var chart5 = new google.visualization.ComboChart(document.getElementById('columnchart_material5'));
        chart5.draw(data5,  options1);
    }
   
   
    
</script>

 
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/reports/thuchi.blade.php ENDPATH**/ ?>