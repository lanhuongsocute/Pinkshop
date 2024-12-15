@extends('backend.layouts.master')
 
@section ('scriptop')
<link href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="content">
    @include('backend.layouts.notification')


                <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form action="{{route('report.congnokhach')}}" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- @csrf -->
                        <div class="sm:flex items-center sm:mr-4">
                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Sắp xếp: </label>
                            <select name="order_name" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                <option value="budget" {{$order_name == "budget"?"selected":""}}>Công nợ</option>
                                <option value="tongdoanhthu" {{$order_name == "tongdoanhthu"?"selected":""}}>Doanh thu</option>
                                <option value="tongloinhuan" {{$order_name == "tongloinhuan"?"selected":""}}>Lợi nhuận</option>
                                <option value="full_name" {{$order_name == "full_name"?"selected":""}}>Tên</option>
                            </select>
                            <select name="order_type" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                <option value="asc" {{$order_name == "asc"?"selected":""}}>Tăng</option>
                                <option value="desc" {{$order_name == "desc"?"selected":""}}>Giảm</option>
                                 
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
                            <div class="text-primary font-semibold text-3xl">DANH SÁCH KHÁCH HÀNG NỢ</div>
                            
                            <div class="mt-1">Ngày lập: {{ date('Y-m-d H:i:s');}}</div>
                            <?php
                                $tong = 0;
                                foreach ($debtcus as $debt )
                                {
                                    if( $debt->budget < 0)
                                        $tong += $debt->budget;
                                }

                            ?>
                            <h2 class="font-medium"> Tổng công nợ cần thu: {{number_format($tong,0,",",".")}} </h2>
                        </div>
                        <?php   $i = 1;?>
                        <div class="col-span-12 lg:col-span-12">

                        
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr> <td> STT </td><td> Tên </td><td> Công nợ </td><td> doanh thu </td>
                                        <td> lợi nhuận </td> 
                                        <td> số ngày nợ </td> 
                                        <td> Điện thoại </td><td> Địa chỉ </td></tr>
                                    </thead>
                                    @foreach ($debtcus as $debt )
                                    <tr>
                                        <td> {{$i}} </td>
                                        <td> <a  href="{{route('customer.show',$debt->id)}}"> {{$debt->full_name}} </a></td>
                                        <td><span class ="{{$debt->budget < 0? 'text-danger':''}}"> {{number_format($debt->budget,0,",",".")}} </span> </td>
                                       
                                        <td> {{number_format($debt->tongdoanhthu,0,",",".")}} </td>
                                        <td> {{number_format($debt->tongloinhuan,0,",",".")}} </td>
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
                                        <td> {{$debt->phone}} </td>
                                        <td> {{$debt->address}} </td>
                                    </tr>
                                    <?php $i ++; ?>
                                    @endforeach
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
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
<script>
  let table = new DataTable('#myTable', {
        pageLength: 1000
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
        + '<body onload="window.print()"><div style="min-height:50px !important; margin-left: 0px !important; margin-left:-100px !important;margin-right:-100px !important;" class="content2">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},60);
    });
</script>
@endsection
