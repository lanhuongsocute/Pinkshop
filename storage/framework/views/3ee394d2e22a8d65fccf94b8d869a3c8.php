
<?php $__env->startSection('content'); ?>

<div class="content">
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-9">
                        <div class="grid grid-cols-12 gap-6 mt-8">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 ">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                         Báo cáo chung
                                    </h2>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-5">
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <a href="<?php echo e(route('bankaccount.index')); ?>" title="Xem chi tiết">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                        <div class="ml-auto">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($cash,0,'.',',')); ?></div>
                                                    <div class="text-base text-slate-500 mt-1">Tổng quỹ</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <a href="<?php echo e(route('warehouseout.today')); ?>" title="Xem chi tiết">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="credit-card" class="report-box__icon text-pending"></i> 
                                                        <div class="ml-auto">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($total_order,0,'.',',')); ?> / <?php echo e(Number_format($number_order,0,'.',',')); ?></div>
                                                    <div class="text-base text-slate-500 mt-1">Tổng doanh thu / Số đơn hàng</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                        <a href="<?php echo e(route('inventory.index')); ?>" title="Xem chi tiết tồn kho">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="monitor" class="report-box__icon text-warning"></i> 
                                                        <div class="ml-auto">
                                                            <!-- <div class="report-box__indicator bg-success tooltip cursor-pointer" title="12% Higher than last month"> 12% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($stock_value,0,'.',',')); ?></div>
                                                    <div class="text-base text-slate-500 mt-1">Giá trị tồn kho</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <a href="<?php echo e(route('customer.index')); ?>" title="danh sách khách hàng">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="user" class="report-box__icon text-success"></i> 
                                                        <div class="ml-auto">
                                                            <!-- <div class="report-box__indicator bg-success tooltip cursor-pointer" title="22% Higher than last month"> 22% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div> -->
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6"><?php echo e(Number_format($usernumber,0,'.',',')); ?></div>
                                                    <div class="text-base text-slate-500 mt-1">Số khách hàng</div>
                                                </div>
                                            </a>
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
                                    <div class="flex flex-col md:flex-row md:items-center">
                                        <div class="flex">
                                            <div>
                                                <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium"><?php echo e(Number_format($sum1,0,'.',',')); ?></div>
                                                <div class="mt-0.5 text-slate-500">Tháng này</div>
                                            </div>
                                            <div class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5"></div>
                                            <div>
                                                <div class="text-slate-500 text-lg xl:text-xl font-medium"><?php echo e(Number_format($sum2,0,'.',',')); ?></div>
                                                <div class="mt-0.5 text-slate-500">Tháng trước</div>
                                            </div>
                                        </div>
                                        <div id= "mydropdown" class="dropdown md:ml-auto mt-5 md:mt-0">
                                            <button class="dropdown-toggle btn btn-outline-secondary font-normal" aria-expanded="false" data-tw-toggle="dropdown"> chọn thời gian <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i> </button>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content overflow-y-auto h-32">
                                                    <li><a href="javascript:void(0)" onclick="load_data_today()" class="dropdown-item">hôm nay</a></li>
                                                    <li><a href="javascript:void(0)" onclick="load_data_month()" class="dropdown-item">tháng này</a></li>
                                                    <li><a href="javascript:void(0)" onclick="load_data_year()" class="dropdown-item">năm nay</a></li>
                                                    <li><a href="javascript:void(0)" onclick="load_data_all()" class="dropdown-item">giữa các năm</a></li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-chart1">
                                        <div class=" " id="container_div">
                                          </div>
                                          <div id="columnchart_material" ></div>
                                    </div>
                                </div>
                            </div>
                            <!-- ds sản phẩm bán chạy trong 2 tháng -->
                            <div class="col-span-12 lg:col-span-12">
                                <h2 style="padding-top:10px;padding-bottom:10px" class="text-success text-lg font-medium truncate mr-5"> 
                                    Danh sách sản phẩm bán chạy trong hai tháng gần đây
                                </h2>
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr> <td> Tên </td><td> Số lượng nhập </td> <td> Số lượng bán </td> </tr>
                                    </thead>
                                    <?php $__currentLoopData = $hotproducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td> 
                                            <a href="<?php echo e(route('inventory.viewproduct',$pro->id)); ?>">
                                                <?php echo e($pro->title); ?>

                                            </a> 
                                        </td>
                                        <td> <?php echo e($pro->tongnhap); ?> </td>
                                        <td> <?php echo e($pro->tongban); ?> </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>

                            </div>
                            <!-- end ds sản phẩm bán chạy trong 2 tháng -->
                            <!-- ds sản phẩm bán chạy cần nhập -->
                            <div class="col-span-12 lg:col-span-12">
                                <h2 style="padding-top:10px;padding-bottom:10px" class="text-danger text-lg font-medium truncate mr-5"> 
                                    Danh sách sản phẩm bán chạy cần nhập 
                                </h2>
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr> <td> Tên </td><td> Số lượng bán </td> <td> tồn kho </td> </tr>
                                    </thead>
                                    <?php $__currentLoopData = $outproducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td> <a href="<?php echo e(route('inventory.viewproduct',$pro->id)); ?>"> <?php echo e($pro->title); ?> </a></td>
                                        <td> <?php echo e($pro->tongban); ?> </td>
                                        <td> <?php echo e($pro->stock); ?> </td>
                                        
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                            <!-- end ds sp bán chạy cần nhập  -->
                            <!-- ds khách hàng nợ -->
                            <div class="col-span-12 lg:col-span-12">
                                <h2 style="padding-top:10px;padding-bottom:10px" class="text-danger text-lg font-medium truncate mr-5"> Danh sách 10 khách hàng nợ </h2>
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr> <td> Tên </td><td> Điện thoại </td><td> Công nợ </td> <td> số ngày nợ </td>   </tr>
                                    </thead>
                                    <?php $__currentLoopData = $debtcus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td> <a  href="<?php echo e(route('user.showsup',$debt->id)); ?>" > <?php echo e($debt->full_name); ?> </a></td>
                                        <td> <?php echo e($debt->phone); ?> </td>
                                        <td> <?php echo e(number_format($debt->budget,0,",",".")); ?> </td>
                                        <td> 
                                            <?php
                                                $wlongs = \DB::select("select DATEDIFF(now(),created_at) as day from warehouseouts where status = 'active' and customer_id = ". $debt->id." and is_paid = false order by id asc limit 1");
                                                if (count($wlongs) > 0)
                                                {
                                                    $wlong = $wlongs[0];
                                                    echo $wlong->day;
                                                }

                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                            <!-- END: Weekly Top Products -->
                        </div>
                    </div>
                    <!-- hoạt động gần đây -->
                    <div class="col-span-12 2xl:col-span-3">
                                <div class="intro-y block sm:flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Hoạt động gần đây
                                    </h2>
                                    <!-- <div class="sm:ml-auto mt-3 sm:mt-0 relative text-slate-500">
                                        <i data-lucide="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i> 
                                        <input type="text" class="datepicker form-control sm:w-56 box pl-10">
                                    </div> -->
                                </div>
                                <div class="mt-5 relative before:block before:absolute before:w-px before:h-[85%] before:bg-slate-200 before:dark:bg-darkmode-400 before:ml-5 before:mt-5">
                                     <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>    
                                     <?php
                                                    $url = "";
                                                    $total = 0;
                                                    $classname="text-danger";
                                                    // echo strcmp($log->doc_type,  "wi");
                                                    if(strcmp($log->doc_type,  "wi") == 0)
                                                    {
                                                        $url = route("warehousein.show",$log->doc_id);
                                                        $wi = \App\Models\WarehouseIn::find($log->doc_id);
                                                        if($wi)
                                                            $total = $wi->final_amount;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "fi") == 0)
                                                    {
                                                        $url = route("freetransaction.show",$log->doc_id);
                                                        $fi = \App\Models\FreeTransaction::find($log->doc_id);
                                                        if($fi)
                                                        {
                                                            $total = $fi->total;
                                                            if($fi->operation == 1)
                                                                $classname="text-success";
                                                        }   
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "wo") == 0)
                                                    {
                                                        $classname="text-success";
                                                        $url = route("warehouseout.show",$log->doc_id);
                                                        $wo = \App\Models\Warehouseout::find($log->doc_id);
                                                        if($wo)
                                                            $total = $wo->final_amount;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "rewo") == 0)
                                                    {
                                                        $classname="text-warning";
                                                        $url = route("warehouseout.show",$log->doc_id);
                                                        $wo = \App\Models\Warehouseout::find($log->doc_id);
                                                        if($wo)
                                                            $total = $wo->final_amount;
                                                        else
                                                            $total = 0;
                                                        
                                                    }
                                                    if(strcmp($log->doc_type,  "btrans") == 0)
                                                    {
                                                        $classname="text-warning";
                                                        $url =  "";
                                                        $item = \App\Models\FreeTransaction::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "bcreate") == 0)
                                                    {
                                                        $classname="text-success";
                                                        $url =  "";
                                                        $item = \App\Models\Bankaccount::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "supwo") == 0 ||strcmp($log->doc_type,  "inpay") == 0)
                                                    {
                                                        $classname="text-warning";
                                                        $url =  route('suptransaction.show',$log->doc_id);
                                                        $item = \App\Models\SupTransaction::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->amount;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "supwi") == 0 ||strcmp($log->doc_type,  "inpay") == 0)
                                                    {
                                                        $classname="text-warning";
                                                        $url =  route('suptransaction.show',$log->doc_id);
                                                        $item = \App\Models\SupTransaction::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->amount;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "ptw") == 0  )
                                                    {
                                                        // $classname="text-warning";
                                                        $url =  route('propertytowarehouse.index' );
                                                        $item = \App\Models\PropertytoWarehouse::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "wtp") == 0  )
                                                    {
                                                        // $classname="text-warning";
                                                        $url =  route('warehousetoproperty.index' );
                                                        $item = \App\Models\WarehouseToProperty::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "mtw") == 0  )
                                                    {
                                                        // $classname="text-warning";
                                                        $url =  route('maintaintowarehouse.index' );
                                                        $item = \App\Models\MaintainToWarehouse::find($log->doc_id) ;
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    if(strcmp($log->doc_type,  "wtm") == 0  )
                                                    {
                                                        // $classname="text-warning";
                                                        $url =  route('warehousetomaintain.index' );
                                                        $item = \App\Models\WarehouseToMaintain::find($log->doc_id) ;
                                                        
                                                        if($item)
                                                            $total = $item->total;
                                                        else
                                                            $total = 0;
                                                    }
                                                    
                                                    
                                                    $invoice =   $log->content.': '. Number_format($total,0,'.',',') ;
                                                    if(strcmp($log->doc_type,  "binven") == 0  )
                                                    {
                                                        $classname="text-warning";
                                                        $url =  route('binventory.index' );
                                                        $binventory = \App\Models\BInventory::find($log->doc_id) ;
                                                        $product = \App\Models\Product::find($binventory->product_id);
                                                        $invoice = $log->content.': '.(strlen($product->title)>20?substr( $product->title,0,20).'...':$product->title) ." số lượng ".$binventory->quantity;
                                                    }
                                                    if(strcmp($log->doc_type,  "mb") == 0  )
                                                    {
                                                        $classname="text-success";
                                                        $url =  route('maintainback.show',$log->doc_id );
                                                        $mb = \App\Models\MaintainBack::find($log->doc_id) ;
                                                        $user = \App\Models\User::find($mb->supplier_id);
                                                        $invoice = $log->content.': '.(strlen($user->full_name)>20?substr($user->full_name,0,20).'...':$user->full_name) .":". number_format($mb->final_amount,0,'.',',')  ;
                                                    }
                                                    if(strcmp($log->doc_type,  "ms") == 0  )
                                                    {
                                                        $classname="text-success";
                                                        $url =  route('maintainsent.show',$log->doc_id );
                                                        $mb = \App\Models\MaintainSent::find($log->doc_id) ;
                                                        $user = \App\Models\User::find($mb->supplier_id);
                                                        $invoice = $log->content.': '.(strlen($user->full_name)>20?substr($user->full_name,0,20).'...':$user->full_name) ." số lượng ". number_format($mb->quantity,0,'.',',')  ;
                                                    }
                                                    if(strcmp($log->doc_type,  "mi") == 0  )
                                                    {
                                                        $classname="text-success";
                                                        $url =  route('maintainin.show',$log->doc_id );
                                                        $mb = \App\Models\MaintenanceIn::find($log->doc_id) ;
                                                        $user = \App\Models\User::find($mb->customer_id);
                                                        $invoice = $log->content.': '.(strlen($user->full_name)>20?substr($user->full_name,0,20).'...':$user->full_name) ." số lượng ". number_format($mb->quantity,0,'.',',')  ;
                                                    }
                                                    if(strcmp($log->doc_type,  "mir") == 0  )
                                                    {
                                                        $classname="text-success";
                                                        $url =  route('maintainin.show',$log->doc_id );
                                                        $mb = \App\Models\MaintenanceIn::find($log->doc_id) ;
                                                        $user = \App\Models\User::find($mb->customer_id);
                                                        $invoice = $log->content.': '.(strlen($user->full_name)>20?substr($user->full_name,0,20).'...':$user->full_name) ." kết quả: ". $mb->result  ;
                                                    }
                                                    if(strcmp($log->doc_type,  "mip") == 0  )
                                                    {
                                                        $classname="text-success";
                                                        $url =  route('maintainin.show',$log->doc_id );
                                                        $mb = \App\Models\MaintenanceIn::find($log->doc_id) ;
                                                        $user = \App\Models\User::find($mb->customer_id);
                                                        $invoice = $log->content.': '.(strlen($user->full_name)>20?substr($user->full_name,0,20).'...':$user->full_name) ." ". number_format($mb->paid_amount,0,'.',',')  ;
                                                    }
                                                    // echo $invoice;
                                            ?>
                                        <div class="intro-x relative flex items-center mb-3"  >
                                            <div class="before:block before:absolute before:w-20 before:h-px before:bg-slate-200 before:dark:bg-darkmode-400 before:mt-5 before:ml-5">
                                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                                </div>
                                            </div>
                                            <a style="width:100%" href="<?php echo e($url); ?>">
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in" >
                                                    <div class="flex items-center">
                                                        <div class="font-medium"><?php echo e(\App\Models\User::find($log->user_id)->full_name); ?></div>
                                                        <div class="text-xs text-slate-500 ml-auto"><?php echo e($log->created_at); ?></div>
                                                    </div>
                                                    <div class="text-slate-500 mt-1 <?php echo e($classname); ?>">
                                                        <?php echo e($invoice); ?>

                                                
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                    </div>
                     <!-- end hoạt động gần đây -->
                </div>
</div>
<link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
<link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    const myDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#mydropdown"));
      google.charts.load('current', {'packages':['bar'],'language': 'vi'});
      google.charts.load('current', {'packages':['corechart'],'language': 'vi'});
      google.charts.setOnLoadCallback(drawChart);
   
    
    function drawChart(data_list) {
        var data = google.visualization.arrayToDataTable(data_list);

        var options = {
            chart: {
            title: 'Báo cáo doanh thu',
            },
            seriesType: 'bars',
            series: {1: {type: 'line'}},
            vAxis: {format: 'short'},
            height: 400,
            colors: ['#1b9e77' ,'#d95f02']
        };
        var chart = new google.visualization.ComboChart(document.getElementById('columnchart_material'));
        chart.draw(data,  options);
    }
    function load_data_time(url)
    {
            myDropdown.hide();
            var f_data = new Array();
            var sx = new Array();
            sx.push("Giờ");
            sx.push("Doanh thu");
            sx.push("Nhập hàng");
            f_data.push(sx);
            $.ajax({
            type: 'GET',
            url: url,
            success: function(data) {
                    console.log(data);
                    if(data.status == true)
                    {
                        messages = data.msg;
                        messages.forEach((message) => {
                            var sx = new Array();
                            sx.push("" + message.time);
                            sx.push( message.b_out*1);
                            sx.push( message.a_in*1);
                            f_data.push(sx);
                        });
                        console.log(f_data);
                        if(f_data.length > 1)
                            drawChart(f_data);
                    }
                }
            }); 
    }
    function load_data_today()
    {
        var url = "<?php echo e(route("admin.getoutday")); ?>";
        load_data_time(url);
    }
    function load_data_month()
    {
        var url = "<?php echo e(route("admin.getoutmonth")); ?>";
        load_data_time(url);   
    }
    function load_data_year()
    {
        var url = "<?php echo e(route("admin.getoutyear")); ?>";
        load_data_time(url);   
    }
    function load_data_all()
    {
        var url = "<?php echo e(route("admin.getoutall")); ?>";
        load_data_time(url);   
    }
    function drawsimpleChart(data_list) {
    var data = google.visualization.arrayToDataTable(data_list);

    var options = {
        chart: {
        title: 'Báo cáo doanh thu',
        // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
        },
        bars: 'vertical',
        vAxis: {format: 'short'},
        height: 400,
        colors: ['#1b9e77' ]
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
    </script>

 

 <script>
    var f_mess = new Array();
    
      // create data set on our data
      var sx = new Array();
      sx.push("Tháng");
      sx.push("Doanh thu");
      sx.push("Nhập hàng");
      f_mess.push(sx);
      $.ajax({
        type: 'GET',
        url: '<?php echo e(route("admin.getoutmonth")); ?>',
        success: function(data) {
                console.log(data);
                if(data.status == true)
                {
                    messages = data.msg;
                    messages.forEach((message) => {
                        var sx = new Array();
                        sx.push( ''+message.time);
                        sx.push( message.b_out*1);
                        sx.push( message.a_in*1);
                        f_mess.push(sx);
                    });
                    console.log(f_mess);
                    drawChart(f_mess);
                }
            }
        }); 
      
       
       
     
    
 </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/index.blade.php ENDPATH**/ ?>