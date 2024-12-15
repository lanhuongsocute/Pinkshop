@extends('backend.layouts.master')
 
@section ('scriptop')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
  <!-- Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css">

@endsection
@section('content')
<div class="content">
    @include('backend.layouts.notification')


                 
            
                <div id="divprint" class="intro-y box overflow-hidden mt-5">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-5 py-10 sm:px-20 sm:py-10">
                            <div class="text-primary font-semibold text-3xl">KIỂM TRA TỒN KHO</div>
                            
                            <div class="mt-1">Ngày lập: {{ date('Y-m-d H:i:s');}}</div>
                             
                            
                             
                        </div>
                        <?php   $i = 1; $tongthu = 0; $tongchi = 0; $tong = 0;?>
                        <div class="col-span-12 lg:col-span-12">
                            <table id="myTable" class="display table" style="width:100%">
                                <thead class="table-dark">
                                    <tr> 
                                        <td> STT </td>
                                        <td> ID USER</td> 
                                        
                                        <td> CÔNG NỢ HIỆN TẠI </td>
                                        <td> CÔNG NỢ TÍNH LẠI TỪ PHIẾU </td>
                                        <td> CHÊNH LỆCH </td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user )
                                     <?php 
                                        $chenhlech = $user->tong -$user->budget;
                                        $text_c = "";
                                        if($chenhlech != 0)
                                            $text_c = "text-danger";
                                    ?>
                                     
                                    <tr>
                                        <td> {{$user->id}} </td>
                                        <td><a href="{{route('report.chitietcongno',$user->id)}}">{{$user->full_name }}</td>
                                        <td>   {{ number_format($user->budget,0,'.',',')}} </td>
                                        <td>    {{ number_format($user->tong,0,'.',',')}}  </td>
                                        <td> 
                                        
                                            <span class ="{{$chenhlech !=  0? 'text-danger':''}}">  
                                                {{ number_format($chenhlech,0,'.',',')}}  
                                            <span>
                                        </td>
                                    </tr>
                                @endforeach
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
