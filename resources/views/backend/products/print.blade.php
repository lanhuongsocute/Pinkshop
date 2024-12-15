@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
                <div  class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                                        
                                    
                    </div>
                </div>         
                
                <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form action="{{route('product.print')}}" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- @csrf -->
                        <div class="sm:flex items-center sm:mr-4">
                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                            <select name="cat_id" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                                <option value="0">-chọn loại-</option>
                                @foreach ($cats as $cat )
                                    <option value="{{$cat->id}}" {{$cat_id==$cat->id?'selected':''}}>{{$cat->title}}</option>
                                @endforeach
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
                            <div class="text-primary font-semibold text-3xl">DANH SÁCH SẢN PHẨM</div>
                            
                            <div class="mt-1">Ngày lập: {{ date('Y-m-d H:i:s');}}</div>
                            
                        </div>
                        <?php   $i = 1;?>
                        <div class="flex flex-col lg:flex-row px-5 sm:px-20 pt-10 pb-5 sm:py-5">
                            <table class="table" style="width: 100%">
                                <thead class="table-dark"> 
                                    <td style="width:20px"> STT </td>
                                    <td class="whitespace-nowrap">Hình  </td>
                                    <td class="whitespace-nowrap">Tên </td>
                                   
                                    <td class="whitespace-nowrap"> Danh mục </td>
                                </thead>
                                @foreach ($products as $item)
                                    <tr>
                                        <td> {{$i}} </td>
                                        <td>  
                                            <div class="flex">
                                            <?php
                                                $photos = explode( ',', $item->photo);
                                                if($photos[0])
                                                {
                                                    echo '<div class="w-10 h-10 image-fit zoom-in">
                                                    <img class="tooltip rounded-full"  src="'.$photos[0].'"/>
                                                </div>';
                                                }
                                            ?>
                                            </div>
                                        </td>
                                        <td style="width: 50%"> {{$item->title}} </td>
                                        <td> {{$item->cattitle}} </td>
                                    </tr>
                                    <?php   $i += 1;?>
                                @endforeach
                                <thead class="table-dark"> 
                                <tr> <td colspan='2'> Tổng </td> <td> {{number_format($i,0,",",".")}} </td> <td> </td></tr>
                                <thead>
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
        + 'type="text/css"><style type="text/css"> .content2 { padding: 0px 0px;  position: relative;   min-height: 100vh; min-width: 0px;flex: 1 1 0%;--tw-bg-opacity: 1;background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity)); padding-top: 0rem;padding-bottom: 0rem;}'
        + ' @media print {.modal-dialog { max-width: 2000px;} }</style> '
        + '<body onload="window.print()"><div style="min-height:50px !important; margin-left: 0px !important; margin-left:-100px !important;margin-right:-100px !important;" class="content2">'+divToPrint.innerHTML+'</div></body>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},60);
    });
</script>
@endsection
