@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
   
                <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                    <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                                
                </div>
                </div>         
                <div id="divprint" class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%; vertical-align:top" class="text-left">
                                    <div class="text-primary font-semibold text-3xl">PHIẾU GỬI BẢO HÀNH</div>
                                    <div class="mt-2"> Mã: <span class="font-medium">{{$ms->id}}</span> </div>
                                    <div class="mt-1">Ngày lập: {{$ms->created_at}}</div>
                                    <div class="form-help">
                                        {{$ms->created_at!=$ms->updated_at?"Điều chỉnh: ".$ms->updated_at:""}}
                                       <br/>
                                       
                                       
                                    </div>
                                    </td>
                             
                                    <?php $detail = \App\Models\SettingDetail::find(1); ?>
                                    <td style="width: 50%; vertical-align:top" class="text-center">
                                    <div class="text-primary font-semibold text-3xl">{{$detail->company_name}} </div>
                                    <div class="mt-2">    {{$detail->phone}} - {{$detail->address}}</span> </div>
                                    
                                    <style>
                                        .divclass {
                                        display: flex;
                                        justify-content: center;
                                        
                                        }
                                    </style>
                                    <div class="mt-1 justify-center divclass" style=" margin: auto;" >
                                            <img src="{{$detail->logo}}" style="width:50px;"> 
                                    </div>
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%" class="text-left">
                                        <div >
                                            <div class="text-base text-slate-500">đơn vị bảo hành</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                {{\App\Models\User::where('id',$ms->supplier_id)->value('full_name')}}
                                            </div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$ms->supplier_id)->value('phone')}}</div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$ms->supplier_id)->value('address')}}</div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div >
                                            <div class="text-base text-slate-500">Nhân viên phụ trách</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                {{\App\Models\User::where('id',$ms->vendor_id)->value('full_name')}}
                                            </div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$ms->vendor_id)->value('phone')}}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                           
                            
                        </div>
                    </div>
                    <div class="px-5 sm:px-16 py-5 sm:py-5">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width:20px"> STT </th>
                                        <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Hàng hóa</th>
                                        <th class="border-b-2 dark:border-darkmode-400 text-right whitespace-nowrap">Số lượng</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; $count = 0;?>
                                    @foreach ($ms_details as $wi )
                                    <tr>
                                        <td> 
                                            <?php echo $i; $i ++;  $count += $wi->quantity;?>
                                        </td>
                                        <td class="border-b dark:border-darkmode-400">
                                            <div class="font-medium whitespace-nowrap">
                                            {{ \App\Models\Product::where('id', $wi->product_id)->value('title')   }}
                                            </div>
                                        </td>
                                        <td class="text-right border-b dark:border-darkmode-400 w-32">
                                            {{$wi->quantity}}
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                   
                                   
                                </tbody>
                                <tfooter>
                                    <tr>
                                        
                                             
                                        <td colspan="2" class="text-right font-medium ">
                                            Số lượng:
                                        </td>
                                        <td class="text-right font-medium">
                                            {{number_format($count, 0, '.', ',')}}
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
                                        <div class="text-base text-slate-500"> </div>
                                            <div class="text-xl text-primary font-medium mt-2">
                                    
                                            </div>
                                        
                                        </div>
                                
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
    
</div>

@endsection
@section('scripts')
<script>
    $("#btnprint").on("click", function(){
        var divToPrint=document.getElementById('divprint');
        // alert(divToPrint.innerHTML);
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<link rel="stylesheet" '
        + 'href="<?php echo asset('backend/assets/dist/css/app.css') ?>" '
        + 'type="text/css"><style type="text/css">'
        + ' @media print {.modal-dialog { max-width: 1000px;} }</style> '
        + '<body onload="window.print()"><div style="min-height:50px !important" class="content">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},20);
    });
</script>

@endsection
