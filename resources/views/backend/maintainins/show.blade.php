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
                                    <div class="text-primary font-semibold text-2xl">PHIẾU NHẬN BẢO HÀNH</div>
                                    <div class="mt-2"> Mã: <span class="font-medium">{{$maintainin->id}}</span> </div>
                                    <div class="mt-1">Ngày lập: {{$maintainin->created_at}}</div>
                                    <div class="form-help">
                                        {{$maintainin->created_at!=$maintainin->updated_at?"Điều chỉnh: ".$maintainin->updated_at:""}}
                                       <br/>
                                    </div>
                                    @if ($maintainin->status =='finished')
                                        <div class="mt-1">Trạng thái: Đã hoàn trả cho khách</div>
                                    @endif
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
                                        <br/>
                            <table style="width: 100%" >
                                <tr>
                                    <td style="width: 50%" class="text-left">
                                        <div >
                                            <div class="text-base text-slate-500">Khách hàng</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                {{\App\Models\User::where('id',$maintainin->customer_id)->value('full_name')}}
                                            </div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$maintainin->customer_id)->value('phone')}}</div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$maintainin->customer_id)->value('address')}}</div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div >
                                            <div class="text-base text-slate-500">Nhân viên nhận</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                {{\App\Models\User::where('id',$maintainin->vendor_id)->value('full_name')}}
                                            </div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$maintainin->vendor_id)->value('phone')}}</div>
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
                                       
                                        <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Hàng hóa</th>
                                        <th class="border-b-2 dark:border-darkmode-400 text-right whitespace-nowrap">Số lượng</th>
                                        <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">Tình trạng</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;?>
                                   
                                    <tr>
                                        
                                        <td class="border-b dark:border-darkmode-400  w-32">
                                            <div class="font-medium whitespace-nowrap">
                                            {{ \App\Models\Product::where('id', $maintainin->product_id)->value('title')   }}
                                            </div>
                                        </td>
                                        <td class="text-right border-b dark:border-darkmode-400 w-32">
                                            {{$maintainin->quantity}}
                                        </td>
                                        <td class="text-left border-b dark:border-darkmode-400  ">
                                            {{   $maintainin->description}}
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            <label class="font-medium">Series nhận:</label>
                                            {{$maintainin->seriesin}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            <label class="font-medium">Mô tả:</label>
                                            {{$maintainin->description}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            <label class="font-medium">Phản hồi:</label>
                                            {{$maintainin->comment}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'>
                                            <label class="font-medium">Series trả:</label>
                                            {{$maintainin->seriesout}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            <label class="font-medium">Kết quả:</label>
                                            {{$maintainin->result}}
                                        </td>
                                   
                                        <td colspan='1'>
                                            <label class="font-medium">Số tiền phải trả:</label>
                                            {{Number_format($maintainin->final_amount,0,'.',',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">
                                            Nợ cũ:
                                        </td>
                                        
                                        <td class="text-right font-medium">
                                            {{number_format(-1*($amount_before_trans), 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">
                                           Phải thanh toán:
                                        </td>
                                        
                                        <td class="text-right font-medium">
                                            {{number_format(-1*($amount_before_paid ), 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">
                                           Đã thanh toán:
                                        </td>
                                        
                                        <td class="text-right font-medium">
                                            {{number_format($maintainin->paid_amount, 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="2">
                                           Nợ hiện tại:
                                        </td>
                                        
                                        <td class="text-right font-medium">
                                            {{number_format(-1*($amount_after_trans ), 0, '.', ',')}}
                                        </td>
                                    </tr>
                                   
                                </tbody>
                                 
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
