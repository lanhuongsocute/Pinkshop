@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
   
                <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                    <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                                
                </div>
                </div>         
                <div id="divprint"  class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%; vertical-align:top" class="text-left">
                                    <div class="text-primary font-semibold text-3xl">PHIẾU GỬI HÀNG</div>
                                    <div class="mt-1"> Mã: <span class="font-medium">{{$warehousetrans->id}}</span> </div>
                                    <div class="mt-1">Ngày lập: {{$warehousetrans->created_at}}</div>
                                    <div class="mt-1">
                                        {{$warehousetrans->created_at!=$warehousetrans->updated_at?"Điều chỉnh: ".$warehousetrans->updated_at:""}}
                                    </div>
                                    </td>
 
                                    <?php $detail = \App\Models\SettingDetail::find(1); ?>
                                    <td style="width: 50%; vertical-align:top" class="text-center">
                                    <div class="text-primary font-semibold text-3xl">{{$detail->company_name}} </div>
                                    <div class="mt-1  ">    {{$detail->phone}} - {{$detail->address}}</span> </div>
                                    
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
                           
                        </div>
                    </div>
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <table style="width: 100%">
                            
                                <tr>
                                    <td style="width: 30%; vertical-align:top" class="text-left">
                                        <div class="text-primary font-semibold ">Người nhận</div>
                                       
                                    </td>
                                    <td>
                                        <div class="text-primary font-semibold text-3xl">{{\App\Models\User::find($warehousetrans->vendor_id2)->full_name}}</div>
                                        <div class="text-primary font-semibold text-2xl">Kho: {{\App\Models\Warehouse::find($warehousetrans->wh_id2)->title}}</div>
                                       
                                        <div class="text-primary font-semibold text-2xl">{{\App\Models\Warehouse::find($warehousetrans->wh_id2)->address}}</div>
                                        <div class="text-primary font-semibold text-2xl">{{\App\Models\User::find($warehousetrans->vendor_id2)->phone}}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <?php
                                        if($warehousetrans->delivery_id != null)
                                        {
                                            $de = \App\Models\User::find($warehousetrans->delivery_id);
                                            echo ' <div class="form-help">Đơn vị vận chuyển:</div>';
                                            echo ' <div class="form-help">'.$de->full_name.'</div>';
                                            echo ' <div class="form-help">ĐT: '.$de->phone.  '</div>';
                                            echo ' <div class="form-help">Địa chỉ:'.$de->phone.'-'.$de->address. '</div>';
                                        }
                                        ?>
                                    </td>
                                    <td style="width:20%" class="text-right">
                                        <span> Phiếu số: ...... /......
                                    </td>
                                </tr>
                            </table>
                        </div>
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
