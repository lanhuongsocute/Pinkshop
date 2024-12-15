@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
            <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                    <button id="btnprint" class="btn btn-primary shadow-md mr-2">Print</button>
                                
                </div>
                </div>         
                <?php
                    if($freetrans->operation == 1)
                        $title = "PHIẾU THU";
                    else
                        $title = "PHIẾU CHI";
                ?>
                <div id="divprint" class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <div class="text-primary font-semibold text-3xl">{{$title}}</div>
                            <div class="mt-2"> Mã: <span class="font-medium">{{$freetrans->id}}</span> </div>
                            <div class="mt-1">Ngày lập: {{$freetrans->created_at}}</div>
                            <div class="mt-1">
                                {{$freetrans->created_at!=$freetrans->updated_at?"Điều chỉnh: ".$freetrans->updated_at:""}}
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row px-5 sm:px-20 pt-10 pb-5 sm:py-5">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 50%" class="text-left">
                                    <div >
                                        <div class="text-base text-slate-500">Nội dung</div>
                                            <div class="text-lg font-medium text-primary mt-2">
                                                {{$freetrans->content}}
                                            </div>
                                            <div class="mt-1"> </div>
                                            <div class="mt-1"> </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                    <div  >
                                        <div class="text-base text-slate-500"> Người thực hiện</div>
                                        <div class="text-lg font-medium text-primary mt-2">
                                            <?php
                                                echo \App\Models\User::where('id',$freetrans->user_id)->value('full_name');
                                            ?>
                                        </div>
                                        <div class="mt-1">
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                            </table>
                           
                            
                        </div>
                    </div>
                    <div class="px-5 sm:px-16 py-5 sm:py-5">
                        <div class="overflow-x-auto">
                            <table class="table">
                                
                                <tbody>
                                    <?php $i = 1;?>
                                  
                                    <tr>
                                        <td>
                                            Số tiền:
                                        </td>
                                        <td class='text-right'> 
                                            
                                        {{$freetrans->total<0?number_format((-$freetrans->total ), 0, '.', ','):number_format(($freetrans->total ), 0, '.', ',')}}
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
<script>
   
</script>
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
        + '<body onload="window.print()"><div class="content">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},20);
    });
</script>
@endsection
