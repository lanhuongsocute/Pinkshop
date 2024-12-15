@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
<div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-12">
                        <div class="grid grid-cols-12 gap-6 mt-8">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 ">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                         Báo cáo quỹ
                                    </h2>
                                </div>
                                
                                <div class="grid grid-cols-12 gap-6 mt-5">
                                     <?php $btong = 0; ?>
                                    @foreach ($accounts as $account )
                                    <?php $btong += $account->total; ?>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($account->total,0,'.',',')}}</div>
                                                <div class="text-base text-slate-500 mt-1">{{$account->title}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($btong,0,'.',',')}}</div>
                                                <div class="text-base text-slate-500 mt-1">Tổng</div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                   
                                </div>
                            </div>
                            <!-- END: General Report -->
                            <!-- BEGIN: Sales Report -->
                            <div class="col-span-12 lg:col-span-12  ">
                                <div class="intro-y block sm:flex items-center h-10">
                                     
                                    <!-- <div class="sm:ml-auto mt-3 sm:mt-0 relative text-slate-500">
                                        <i data-lucide="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i> 
                                        <input type="text" class="datepicker form-control sm:w-56 box pl-10">
                                    </div> -->
                                </div>
                                <div class="intro-y box p-5 mt-12 sm:mt-5">
                                  
                                    <h3> Quỹ theo tháng</h3>
                                    <div class="report-chart1">
                                        <div class=" " id="container_div1">
                                          </div>
                                          <div id="columnchart_material1" ></div>
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

@endsection

@section('scripts')


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // const myDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#mydropdown"));
    google.charts.load('current', {'packages':['bar'],'language': 'vi'});
    google.charts.load('current', {'packages':['corechart'],'language': 'vi'});
    google.charts.setOnLoadCallback(drawChart);

    
function drawChart( ) {
        var f_data1 = new Array();
        
        sx = new Array();
        sx.push("Giờ");
        sx.push("tổng quỹ");
        f_data1.push(sx);
        
        @foreach ( $quys as $adetail)
            sx = new Array();
            sx.push("" + '{{$adetail->ngay}}');
            sx.push({{  $adetail->tong }}*1);
            f_data1.push(sx);
        @endforeach
        var data1 = google.visualization.arrayToDataTable(f_data1);
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
        var chart1 = new google.visualization.ComboChart(document.getElementById('columnchart_material1'));
        chart1.draw(data1,  options1);
        
    }
   
   
    
</script>

 
 
@endsection