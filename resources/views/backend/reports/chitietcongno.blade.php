@extends('backend.layouts.master')
 
@section ('scriptop')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
  <!-- Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css">

@endsection
@section('content')
<div class="content">
    @include('backend.layouts.notification')
 
    <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
        <form action="{{route('report.chitietcongno',$user->id)}}" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- @csrf -->
            <div class="sm:flex items-center sm:mr-4">
                <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5"> Chọn ngày bắt đầu </label>
                 
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
            <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Chọn</button>
        
            <div class="mt-2 xl:mt-0">
                
            </div>
            <div class="mt-2 xl:mt-0">
                  
            </div>
        </form>
        <div class="flex mt-5 sm:mt-0">
            <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
        </div>
    </div>   
            
                 
            
                <div id="divprint" class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <div class="text-primary font-semibold text-3xl">DANH SÁCH CHI TIẾT GIAO DỊCH CỦA {{$user->full_name}}</div>
                            
                            <div class="mt-1">Ngày lập: {{ date('Y-m-d H:i:s');}}</div>
                             
                            @if ($user->budget > 0)
                                <h2 class="font-medium"> Tổng công nợ cần trả cho đối tác: {{number_format($user->budget,0,",",".")}} </h2>
                            @else
                                <h2 class="font-medium"> Tổng công nợ cần thu từ đối tác: {{number_format((-1)*$user->budget,0,",",".")}} </h2>
                          
                            @endif
                             
                        </div>
                        <?php   $i = 1; $tongthu = 0; $tongchi = 0; $tong = 0;?>
                        <div class="col-span-12 lg:col-span-12">
                            <table id="myTable" class="display table" style="width:100%">
                                <thead class="table-dark">
                                    <tr> 
                                        <td> STT </td>
                                        <td> NGÀY</td> 
                                        
                                        <td> XUẤT </td>
                                        <td> NHẬP </td>
                                        <td> CÔNG NỢ<br/>THỜI ĐIỂM </td>
                                        <td> CHI TIẾT </td> 
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($sups as $sup )
                                    <?php
                                    if($sup->operation == -1)
                                        $tongthu += $sup->amount;
                                    else
                                        $tongchi += $sup->amount;

                                    $tong += $sup->operation*$sup->amount;
                                    ?>
                                    <tr>
                                        <td> {{$i}} </td>
                                        <td>{{substr($sup->created_at,0,10)}} </td>
                                        <td> {{ $sup->operation==-1?number_format($sup->amount,0,'.',','):''}} </td>
                                        <td>  {{ $sup->operation==1?number_format($sup->amount,0,'.',','):''}}  </td>
                                        <td>
                                            <span class ="{{$sup->operation > 0? 'text-danger':''}}">  
                                                {{ number_format($sup->total1,0,'.',',')}} 
                                               
                                            <span>
                                        </td>
                                        <td class='text-center'> 
                                            
                                            @if ($sup->doc_type=='wi')
                                            <?php
                                                $wi = \App\Models\WarehouseIn::find($sup->doc_id);
                                               
                                                if($wi)
                                                {
                                                    $details = \DB::select( 'select a.* ,b.title from (select * from warehouse_in_details where doc_id ='.
                                                        $wi->id.' and doc_type="wi" ) as a left join products as b on a.product_id = b.id ');
                                                }
                                            ?>
                                                <h3 class='mt-2 mb-2'> 
                                                                <a href="{{route('warehousein.show',$wi->id)}}">                                                 
                                                                   PHIẾU NHẬP HÀNG: {{$wi->code}}
                                                                </a>
                                                </h3>
                                                <table class='table'>
                                                    <thead class="table-light">
                                                        <tr> 
                                                            <td> TÊN </td>
                                                            <td> SỐ LƯỢNG</td> 
                                                            
                                                            <td> GIÁ </td>
                                                             
                                                        </tr>
                                                    </thead>
                                                    @foreach ($details as $detail )
                                                    <tr>
                                                        <td>  {{$detail->title }} </td>
                                                        <td> {{$detail->quantity}} </td>
                                                        <td> {{number_format($detail->price,0,'.',',')}} </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                            @if ($sup->doc_type=='wo')
                                                 <?php
                                                    $wo = \App\Models\Warehouseout::find($sup->doc_id);
                                                
                                                    if($wo)
                                                    {
                                                        $details = \DB::select( 'select a.* ,b.title from (select * from warehouseout_details where wo_id ='.
                                                            $wo->id.' and doc_type="wo" ) as a left join products as b on a.product_id = b.id ');
                                                    }
                                                ?>
                                                    <h3 class='mt-2 mb-2'> 
                                                                    <a href="{{route('warehouseout.show',$wo->id)}}">                                                 
                                                                    PHIẾU XUẤT HÀNG: {{$wo->code}}
                                                                    </a>
                                                    </h3>
                                                    <table class='table'>
                                                        <thead class="table-light">
                                                            <tr> 
                                                                <td> TÊN </td>
                                                                <td> SỐ LƯỢNG</td> 
                                                                
                                                                <td> GIÁ </td>
                                                                
                                                            </tr>
                                                        </thead>
                                                        @foreach ($details as $detail )
                                                        <tr>
                                                            <td>  {{$detail->title }} </td>
                                                            <td> {{$detail->quantity}} </td>
                                                            <td> {{number_format($detail->price,0,'.',',')}} </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                            @endif
                                            @if ($sup->doc_type=='mi')
                                            <?php
                                                    $mi = \App\Models\MaintainBack::find($sup->doc_id);
                                                
                                                    if($mi)
                                                    {
                                                        $details = \DB::select( 'select a.* ,b.title from (select * from maintain_back_details where mb_id ='.
                                                            $mi->id.'   ) as a left join products as b on a.product_id = b.id ');
                                                    }
                                                ?>
                                                    <h3 class='mt-2 mb-2'> 
                                                                    <a href="{{route('maintainback.show',$mi->id)}}">                                                 
                                                                    PHIẾU TRẢ BẢO HÀNH: {{$mi->id}}
                                                                    </a>
                                                    </h3>
                                                    <table class='table'>
                                                        <thead class="table-light">
                                                            <tr> 
                                                                <td> TÊN </td>
                                                                <td> SỐ LƯỢNG</td> 
                                                                
                                                                <td> GIÁ </td>
                                                                
                                                            </tr>
                                                        </thead>
                                                        @foreach ($details as $detail )
                                                        <tr>
                                                            <td>  {{$detail->title }} </td>
                                                            <td> {{$detail->quantity}} </td>
                                                            <td> {{number_format($detail->price,0,'.',',')}} </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                            @endif
                                            @if ($sup->doc_type=='mo')
                                            <?php
                                                    $mos = \DB::select( 'select a.* ,b.title from (select * from maintenance_ins where id ='.
                                                    $sup->doc_id.'   ) as a left join products as b on a.product_id = b.id ');
                                                    
                                                    $mo = $mos[0];
                                                ?>
                                                    <h3 class='mt-2 mb-2'> 
                                                                    <a href="{{route('maintainin.show',$mo->id)}}">                                                 
                                                                    PHIẾU NHẬN BẢO HÀNH: {{$mo->id}}
                                                                    </a>
                                                    </h3>
                                                    <table class='table'>
                                                        <thead class="table-light">
                                                            <tr> 
                                                                <td> TÊN </td>
                                                                <td> SỐ LƯỢNG</td> 
                                                            </tr>
                                                        </thead>
                                                       
                                                        <tr>
                                                            <td>  {{$mo->title }} </td>
                                                            <td> {{$mo->quantity}} </td>
                                                        </tr>
                                                       
                                                    </table>
                                            @endif
                                            @if ($sup->doc_type=='fi')
                                            <?php
                                                $banktrans = \DB::select("select a.*, b.title from (select * from bank_transactions where id =".$sup->doc_id." )as a left join bankaccounts as b on a.bank_id = b.id");
                                                $banktran = $banktrans[0];
                                            ?>
                                                <h3 class='mt-2 mb-2'> 
                                                    <a href="{{route('banktrans.show',$banktran->id)}}">                                                 
                                                    PHIẾU GIAO DỊCH TIỀN BẰNG TÀI KHOẢN: {{$banktran->title}}
                                                    </a>
                                                </h3>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i ++; ?>
                                    @endforeach
                                </tbody>
                                <tfooter>
                                    
                                    <tr>
                                        <td colspan ='2'>
                                        <td>
                                            {{ number_format($tongthu,0,'.',',')}}
                                        </td>
                                        <td>
                                            {{ number_format($tongchi,0,'.',',')}}
                                        </td>
                                        <td colspan = '2'>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan = '2'>

                                        </td>
                                        <td  colspan = '2'>
                                            Tổng công nợ:  {{ number_format($tong,0,'.',',')}}
                                        </td>
                                        <td colspan = '2'>

                                        </td>
                                    </tr>
                                </tfooter>
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
                                        {{\App\Models\User::where('id',auth()->user()->id)->value('full_name')}}
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

@endsection
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
@endsection
