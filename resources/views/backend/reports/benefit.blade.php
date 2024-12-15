@extends('backend.layouts.master')
@section ('scriptop')
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
    <!-- Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css">
@endsection
@section('content')

<div class="content">
@include('backend.layouts.notification')
<div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-12">
                        <div class="grid grid-cols-12 gap-6 mt-8">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 row">
                                <div class="intro-y flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                         Báo cáo doanh thu
                                    </h2>
                                </div>
                                <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                                    <form action="{{route('report.money')}}" method = "get" class="xl:flex sm:mr-auto" >
                                        <!-- @csrf -->
                                        <div class="sm:flex items-center sm:mr-4">
                                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5"> Chọn nhanh </label>
                                            <select name="time" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                                <option value="today" {{$time=="today"?"selected":""}}>hôm nay</option>
                                                <option value="week" {{$time=="week"?"selected":""}}>7 ngày</option>
                                                <option value="30ngay" {{$time=="30ngay"?"selected":""}}>30 ngày</option>
                                                <option value="hangthang" {{$time=="hangthang"?"selected":""}}>cả năm</option>
                                                 
                                            </select>
                                        </div>
                                        <?php $curYear = date('Y'); ?>
                                        <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                            <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">hoặc chọn thời gian</label>
                                            <select name="select_year" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                                                <option value="0" selected>-chọn năm-</option>
                                                <option value="{{$curYear}}" {{$curYear==$select_year?'selected':''}}>{{$curYear}}</option>
                                            </select>
                                            <select name="select_month" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                                                <option value="0" selected>-chọn tháng-</option>
                                                @for ($i = 1; $i < 13; $i++)
                                                    <option value ="{{$i}}" {{$i==$select_month?'selected':''}} > tháng {{$i}} </option>
                                                @endfor
                                            </select>
                                            <select name="select_day" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                                                <option value="0" selected>-chọn ngày-</option>
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <option value ="{{$i}}" {{$i==$select_day?'selected':''}}> ngày {{$i}} </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mt-2 xl:mt-0">
                                            <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Go</button>
                                        </div>
                                    </form>
                                    
                                    <div class="flex mt-5 sm:mt-0">
                                        <a href="{{route('product.print')}}" id="tabulator-print" class="btn btn-outline-secondary w-1/2 sm:w-auto mr-2"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                                        
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-6 mt-5">
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-success tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($tongloinhuan,0,'.',',')}}</div>
                                                <div class="text-base text-slate-500 mt-1">Lợi nhuận ròng</div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="credit-card" class="report-box__icon text-pending"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="2% Lower than last month"> 2% <i data-lucide="chevron-down" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($tongdoanhthu,0,'.',',')}}  </div>
                                                <div class="text-base text-slate-500 mt-1">Tổng doanh thu</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="credit-card" class="report-box__icon text-pending"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="2% Lower than last month"> 2% <i data-lucide="chevron-down" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($sodon,0,'.',',')}}  </div>
                                                <div class="text-base text-slate-500 mt-1">Số đơn hàng</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="credit-card" class="report-box__icon text-pending"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="2% Lower than last month"> 2% <i data-lucide="chevron-down" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($thuchi,0,'.',',')}}  </div>
                                                <div class="text-base text-slate-500 mt-1">Thu - chi</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <div class="report-box zoom-in">
                                            <div class="box p-5">
                                                <div class="flex">
                                                    <i data-lucide="credit-card" class="report-box__icon text-pending"></i> 
                                                    <!-- <div class="ml-auto">
                                                        <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="2% Lower than last month"> 2% <i data-lucide="chevron-down" class="w-4 h-4 ml-0.5"></i> </div>
                                                    </div> -->
                                                </div>
                                                <div class="text-3xl font-medium leading-8 mt-6">{{Number_format($tongloinhuan+ $thuchi,0,'.',',')}}  </div>
                                                <div class="text-base text-slate-500 mt-1">Lợi nhuận</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: General Report -->
                                <?php $i = 1;?>
                                <div style="width:100%" class="row col-span-12 " style="padding:10px" >
                                    <div style="background-color:white">
                                        <table id="myTable" class="display table" style="width:100%; ">
                                            <thead class="table-dark">
                                                <tr> <td> STT </td><td> Tên </td><td> Số lượng xuất </td><td> Lợi nhuận </td> 
                                                </tr>
                                            </thead>
                                            @foreach ($products as $item )

                                            @if ($item->product_id != null)
                                                <tr>
                                                    <td> {{$i}} </td>
                                                    <td> <a   > {{$item->title}} </a></td>
                                                    <td><span  > {{number_format($item->quantity,0,".",",")}} </span> </td>
                                                    <td><span  > {{number_format($item->tongloinhuan,0,".",",")}} </span> </td>
                                                    
                                                </tr>
                                            @endif
                                            <?php $i ++; ?>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
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
                                    <h3> Doanh thu </h3>
                                    <div class="report-chart1">
                                        <div class=" " id="container_div1">
                                          </div>
                                          <div id="columnchart_material1" ></div>
                                    </div>
                                    <h3> Số lượng đơn hàng </h3>
                                    <div class="report-chart2">
                                        <div class=" " id="container_div2">
                                          </div>
                                          <div id="columnchart_material2" ></div>
                                    </div>
                                    <h3> thu chi </h3>
                                    <div class="report-chart3">
                                        <div class=" " id="container_div3">
                                          </div>
                                          <div id="columnchart_material3" ></div>
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

@section('scripts')
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
        pageLength: 50,
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        }
        
    });
   
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // const myDropdown = tailwind.Dropdown.getOrCreateInstance(document.querySelector("#mydropdown"));
    google.charts.load('current', {'packages':['bar'],'language': 'vi'});
    google.charts.load('current', {'packages':['corechart'],'language': 'vi'});
    google.charts.setOnLoadCallback(drawChart);

    
function drawChart( ) {
        var f_data1 = new Array();
        var sx = new Array();
        sx.push("Giờ");
        sx.push("Doanh thu");
        sx.push("Lợi nhuận");
        f_data1.push(sx);
     
        var i = 0;
        
            @foreach ( $reportdetails as $adetail)
                 
                var sx = new Array();
                sx.push("" + '{{$adetail->ngay}}');
                sx.push({{$adetail->tongbansp}}*1);
                sx.push({{$adetail->loinhuan}}*1);
                f_data1.push(sx);
                 
            @endforeach
           
         
        var f_data2 = new Array();
        sx = new Array();
        sx.push("Giờ");
        sx.push("Số đơn");
        f_data2.push(sx);
      
        i = 0;
        
            @foreach ( $reportsodons as $adetail)
                 
                sx = new Array();
                sx.push("" + '{{$adetail->ngay}}');
                sx.push({{$adetail->sodon}}*1);
                f_data2.push(sx);
                 
            @endforeach
        var f_data3 = new Array();
        sx = new Array();
        sx.push("Giờ");
        sx.push("Thu chi");
        f_data3.push(sx);
      
        i = 0;
        
            @foreach ( $reportthuchis as $adetail)
                 
                sx = new Array();
                sx.push("" + '{{$adetail->ngay}}');
                sx.push({{$adetail->thuchi}}*1);
                f_data3.push(sx);
                 
            @endforeach   
         
        console.log(f_data1);
        console.log(f_data2);
        console.log(f_data3);
        var data1 = google.visualization.arrayToDataTable(f_data1);
        var data2 = google.visualization.arrayToDataTable(f_data2);
        var data3 = google.visualization.arrayToDataTable(f_data3);
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
    }
   
   
    
</script>

 
 
@endsection