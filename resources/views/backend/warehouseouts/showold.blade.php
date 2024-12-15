@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
   
                <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                    <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                                
                </div>
                </div>         
                <div id="divprint" class="intro-y box overflow-hidden mt-5 px-10">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-1 py-2 sm:px-1 sm:py-2">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%; vertical-align:top" class="text-left">
                                    <div class="text-primary font-semibold text-2xl">PHIẾU BÁN HÀNG (đã xóa)</div>
                                    <div class="mt-2"> Mã: <span class="font-medium">{{$warehouseout->version}} - {{$warehouseout->code}}</span> </div>
                                    <div class="mt-1">Ngày lập: {{$warehouseout->created_at}}</div>
                                    <div class="form-help">
                                        {{$warehouseout->created_at!=$warehouseout->updated_at?"Điều chỉnh: ".$warehouseout->updated_at:""}}
                                        <br/>
                                       <span class="font-medium text-danger"> 
                                        {{$warehouseout->status=='returned'?'ĐÃ TRẢ HÀNG':''}}
                                        </span>
                                       
                                    </div>
                                    </td>
                             
                                    <?php $detail = \App\Models\SettingDetail::find(1); ?>
                                    <td style="width: 50%; vertical-align:top" class="text-center">
                                    <div class="text-primary font-semibold text-2xl">{{$detail->company_name}} </div>
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
                                        <div class="text-base text-slate-500">Khách hàng</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                <a  href="{{route('user.showsup',$warehouseout->customer_id)}}" >
                                                    {{\App\Models\User::where('id',$warehouseout->customer_id)->value('full_name')}}
                                                </a>
                                            </div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$warehouseout->customer_id)->value('phone')}}</div>
                                            <div class="mt-1">{{\App\Models\User::where('id',$warehouseout->customer_id)->value('address')}}</div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                    <div  >
                                        <div class="text-base text-slate-500">Kho xuất hàng</div>
                                        <div class="text-lg font-medium text-primary mt-2">
                                        {{\App\Models\Warehouse::where('id',$warehouseout->wh_id)->value('title')}}
                                        </div>
                                        <div class="mt-1">{{\App\Models\User::where('id',$warehouseout->vendor_id)->value('full_name')}}

                                        </div>
                                    </div>
                                    </td>
                                </tr>
                            </table>
                           
                            
                        </div>
                    </div>
                    <div class="px-1 py-2 sm:px-1 sm:py-2">
                        <div class="overflow-x-auto">
                            <table class="table" style="margin-bottom:10px">
                                <thead>
                                    <tr>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> STT </th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400  ">Hàng hóa</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right ">Số lượng</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right  ">Đơn giá</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right  ">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;?>
                                    @foreach ($wo_details as $wi )
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> 
                                            <?php echo $i; $i ++; ?>
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b dark:border-darkmode-400">
                                            <?php
                                                $product= \App\Models\Product::find( $wi->product_id);
                                            ?>
                                            <div class="  ">
                                                <a  href="{{route('inventory.viewproduct',$product->id)}}" > 
                                                    {{  $product-> title    }}
                                                </a>
                                            </div>
                                            <div class="form-help">
                                            {{ $product->expired != null ? 'bảo hành: '. $product->expired.' tháng':''   }} 
                                            </div>
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                            {{$wi->quantity}}
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                            {{number_format($wi->price, 0, '.', ',')}}
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400   ">
                                        {{number_format(($wi->quantity*$wi->price), 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    @endforeach
                                   
                                   
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="2">
                                            <span class="font-medium "> 
                                                Giảm giá: - {{number_format($warehouseout->discount_amount, 0, '.', ',')}}
                                            </span> 
                                            <br/>
                                            <span class="font-medium "> 
                                                Phí vận chuyển: + {{$warehouseout->shiptrans_id? number_format(\App\Models\Freetransaction::find($warehouseout->shiptrans_id)->total,  0, '.', ','):'0'}}
                                            </span> 
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="2" class="text-right font-medium ">
                                            Tổng:
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right font-medium">
                                            {{number_format($warehouseout->final_amount, 0, '.', ',')}}
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                          

                        </div>
                    </div>
                    <div class="px-1 py-2 sm:px-1 sm:py-2">
                        <table style="width:100%">
                            <tr>
                                <td style="width:50%">
                                    <div class="text-center sm:text-left mt-1 sm:mt-0">
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
                                        <div class="text-base text-slate-500"> Đơn vị vận chuyển </div>
                                            <div class="text-xl text-primary font-medium mt-1">
                                               {{$warehouseout->delivery_id? \App\Models\User::find($warehouseout->delivery_id)->full_name:''}}

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
        + 'type="text/css"><style type="text/css"> .content2 { padding: 0px 0px;  position: relative;   min-height: 100vh; min-width: 0px;flex: 1 1 0%;--tw-bg-opacity: 1;background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity)); padding-top: 0rem;padding-bottom: 0rem;}'
        + ' @media print {.modal-dialog { max-width: 2000px;} }</style> '
        + '<body onload="window.print()"><div style="min-height:50px !important; margin-left: 0px !important; " class="content2">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},20);
    });
</script>

@endsection
