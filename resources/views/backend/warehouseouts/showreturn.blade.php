@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
   
                <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                    <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                                
                </div>
                </div>         
                <div id="divprint" class="intro-y box overflow-hidden  px-10">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-1 py-5 sm:px-1 sm:py-2">
                            <style>
                                .divclass_p {
                                    display: flex;
                                    justify-content: center;
                                }
                                .header_p {
                                display: flex;
                                align-items: center; /* Vertically aligns the content */
                                justify-content: space-between; /* Adds space between the logo and text */
                                }

                                .logo_p {
                                max-width: 100%; /* Ensures logo doesn't exceed its container */
                                max-height: 80px; /* Set a reasonable max height for the logo */
                                height: auto; /* Maintain aspect ratio */
                                }

                                .company-info_p {
                                margin-left: 10px; /* Adds some space between the logo and text */
                                flex: 1; /* Allows the text to take the remaining available space */
                                text-align: right; /* Aligns the text to the right */
                                }

                                .company-name_p  {
                                display: block; /* Ensures text is displayed in block */
                                font-size: 24pt; /* Adjust font size as needed */
                               
                                font-weight:600;
                                }
                                .telephone-number_p {
                                display: block; /* Ensures text is displayed in block */
                                font-size: 18pt; /* Adjust font size as needed */
                                
                                }
                                .customer_p {
                                    display: block; /* Ensures text is displayed in block */
                                    font-size: 24pt; /* Adjust font size as needed */
                                
                                }
                                .customer_tel_p {
                                    display: block; /* Ensures text is displayed in block */
                                    font-size: 28pt; /* Adjust font size as needed */
                                }
                                @media print {
                                    .company-name_p {
                                        font-weight:600;
                                        font-size: clamp(18pt, 2vw, 24pt); /* Min 12pt, scales with viewport, max 18pt */
                                    }

                                    .telephone-number_p {
                                        font-size: clamp(16pt, 1.5vw, 24pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                    .customer_p {
                                        font-size: clamp(24pt, 1.5vw, 28pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                    .customer_tel_p {
                                        font-size: clamp(28pt, 1.5vw, 32pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                }
                            </style>
                             <?php $detail = \App\Models\SettingDetail::find(1); ?>
                            
                            <?php
                                $date_object = new DateTime($warehouseout->created_at);
                                $formatted_date = strftime('ngày %d tháng %m năm %Y', $date_object->getTimestamp());

                            ?>
                            <div class="header_p">
                                <img src="{{$detail->logo}}"  class="logo_p">
                                <div class="company-info">
                                    <span class="company-name_p">{{$detail->company_name}}</span>
                                    <span class=" mt-4 telephone-number_p">{{$detail->phone}}</span>
                                    <span class=" mt-4 telephone-number_p">{{$detail->address}}</span>
                                </div>
                            </div>
                            
                            <div style="clear:both">&nbsp;<br/></div>
                             
                            <div style="text-align:center" class="text-primary font-semibold text-2xl">PHIẾU TRẢ HÀNG</div>
                            <div style="text-align:center" class="mt-1 text-xl"> {{ $formatted_date}}</div>
                            <div style="clear:both">&nbsp;<br/></div>
                            <div style="text-align:center; " class=" mt-4 customer_p">
                                 Khách hàng: {{\App\Models\User::where('id',$warehouseout->customer_id)->value('full_name')}}
                            </div>
                            <div style="text-align:center " class=" mt-6  customer_tel_p">SĐT: {{\App\Models\User::where('id',$warehouseout->customer_id)->value('phone')}}</div>
                            <div style="text-align:center " class=" mt-4 telephone-number_p">Địa chỉ: {{\App\Models\User::where('id',$warehouseout->customer_id)->value('address')}}</div>
                            
                             
                           
                            
                        </div>
                    </div>
                    <div class="px-1 py-2 sm:px-1 sm:py-2">
                        <div class="overflow-x-auto">
                            <table class="table" style="margin-bottom:10px">
                                <thead>
                                    <tr>
                                        <th  style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> STT </th>
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
                                    @if ($wi->series != '')
                                        <tr><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " ></td><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="4">số seri:{{$wi->series}}</td></tr> 
                                    @endif
                                    @endforeach
                                   
                                   
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  colspan="2">
                                            <span class="font-medium "> 
                                                Giảm giá: - {{number_format($warehouseout->discount_amount, 0, '.', ',')}}
                                            </span> 
                                            <br/>
                                            <span class="font-medium "> 
                                                Phí vận chuyển: + {{$warehouseout->shiptrans_id? number_format(\App\Models\Freetransaction::find($warehouseout->shiptrans_id)->total,  0, '.', ','):'0'}}
                                            </span> 
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  colspan="2" class="text-right font-medium ">
                                            Tổng tiền hàng:
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right font-medium">
                                            {{number_format($warehouseout->final_amount, 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                             Nợ cũ khách hàng:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            {{number_format(-1*($amount_before_trans), 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Phải thanh toán:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            {{number_format(-1*($amount_before_paid ), 0, '.', ',')}}
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Cửa hàng thanh toán:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            {{number_format($warehouseout->paid_amount, 0, '.', ',')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Nợ hiện tại:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right " colspan="2">
                                            {{number_format(-1*($amount_after_trans ), 0, '.', ',')}}
                                            <br/>
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
        + '<body onload="window.print()"><div style="min-height:50px !important; margin-left: 0px !important;  " class="content2">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},20);
    });
</script>

@endsection
