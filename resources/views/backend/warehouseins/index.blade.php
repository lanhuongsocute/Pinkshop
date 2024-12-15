@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Nhập kho
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('warehousein.create')}}" class="btn btn-primary shadow-md mr-2">Thêm nhập kho</a>
               
                    <a href="javascript:;"  class="btn btn-primary btn_einvoice shadow-md mr-2" >Thêm đơn nhập điện tử</a>
                
             <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$warehouseins->currentPage()}} trong {{$warehouseins->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                 
            </div>
        </div>
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
                    <form action="{{route('warehousein.index')}}" method = "get" class="xl:flex sm:mr-auto" >
                        <!-- @csrf -->
                        <div class="sm:flex items-center sm:mr-4">
                            <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Lọc: </label>
                            <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                <input name="date1" value = "{{$date1}}" type="text" class="datepicker form-control w-56 block mx-auto" data-single-mode="true"> 
                                -  
                                <input name="date2" value ="{{$date2}}" type="text" class="datepicker form-control w-56 block mx-auto" data-single-mode="true"> 
                                <input type="text" id='supplier_search' 
                                    class="form-control    " placeholder="Tên hoặc số điện thoại" value ="{{isset($supplier_id)&&$supplier_id>0 && \App\Models\User::find($supplier_id)?\App\Models\User::find($supplier_id)->full_name:''}}">
                                <input type="hidden" name="supplier_id" id="supplier_id" name="supplier_id" value="{{isset($supplier_id)?$supplier_id:0}}" />
                            </div>
                            <button id="btn_tim" type="submit" class="btn btn-primary w-full sm:w-16" >Chọn</button>
                        </div>
                    </form>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">CODE</th>
                        <th class="whitespace-nowrap">NHÀ CUNG CẤP</th>
                        <th class="whitespace-nowrap">KHO</th>
                        <th class="text-center whitespace-nowrap">SỐ TIỀN</th>
                        <th class="text-center whitespace-nowrap">ĐÃ THANH TOÁN</th>
                        <th class="text-center whitespace-nowrap">NGÀY LẬP</th>
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouseins as $item)
                    <?php
                                if($item->final_amount != 0)
                                    $temp = (int)(($item->paid_amount/$item->final_amount)*100);
                                else
                                    $temp = 0;
                                if($temp==0)
                                    $temp = 1;
                                $class_p = "";
                                if($temp < 50)
                                {
                                    $class_p = "bg-danger";
                                }
                                else
                                {
                                    if($temp < 100)
                                    {
                                        $class_p ="bg-warning";
                                    }
                                }
                    ?>
                    <tr class="intro-x ">
                        <td> <a href="{{route('warehousein.show',$item->id)}}" title="Xem chi tiết" class=" tooltip" href="javascript:;">
                            {{$item->code}} 
                            </a>
                        </td>
                        <td>
                            
                           <a class="tooltip "  title="Xem công nợ"  href="{{route('user.showsup',$item->supplier_id)}}"> {{\App\Models\User::where('id',$item->supplier_id)->value('full_name')}}
                            </a>
                            
                        </td>
                        <td>
                             
                            {{\App\Models\Warehouse::where('id',$item->wh_id)->value('title')}}
                            
                        </td>
                        <td class="text-right">
                            
                            {{ number_format($item->final_amount, 0, '.', ',');}}
                            
                        </td>
                        <td class="text-right">
                           
                            <div class="progress h-6 mt-3">
                                <div class="progress-bar {{$class_p}} " role="progressbar" style="  width:{{$temp}}%"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                 {{ number_format($item->paid_amount, 0, '.', ',')}} 
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$item->created_at}}
                        </td>
                        <td>
                            <span class="{{$item->status =='returned'||$item->status =='deleted'?'text-danger':''}}">
                             {{$item->status}}
                            </span>
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    hoạt động
                                </a>
                                <div class="dropdown-menu w-40"> 
                                    <ul class="dropdown-content">
                                        <li><a href="{{route('warehousein.show',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                       
                                        <?php
                                        if($item->is_paid == false && $item->status == 'active')
                                        {
                                            echo '<li> <a href=" '. route('warehousein.paid',$item->id).'" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i> Trả tiền </a></li>';
                                        }
                                        ?>
                                        
                                        @if($item->status == 'active')
                                            <li><a href="{{route('warehousein.edit',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                            <li> 
                                                <form action="{{route('warehousein.destroy',$item->id)}}" method = "post">
                                                @csrf
                                                @method('delete')
                                                <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                                </form>
                                            </li>
                                            <li> 
                                                <form action="{{route('warehousein.return')}}" method = "post">
                                                @csrf
                                                <input type="hidden" name="id" value = "{{$item->id}}"/>
                                                <a class="dropdown-item flex items-center text-danger dltReturn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="git-branch" class="w-4 h-4 mr-1"></i> Return </a>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div> 
                            </div> 
                            </div>
                        </td>
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
    </div>
    <!-- END: HTML Table Data -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{$warehouseins->links('vendor.pagination.tailwind')}}
            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>

<div  id="addeinvoice" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Điền mã điện tử của hóa đơn </h2>    
                
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <form action="{{route('warehousein.add_einvoice')}}" method = "post">
                    @csrf
                    <input id="uiid" name="uiid"  type="text" class="form-control" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx-xxxx-xxxx-xxxx">
                    <br/>
                    <button type="submit" id="btn_newuser" class="btn btn-primary w-24">Lưu</button>
                </form>
            </div>
         </div>
</div>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
 
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet"> 
<script src="{{asset('backend/assets/js/product_v3.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    
    const myModalsup = tailwind.Modal.getOrCreateInstance(document.querySelector("#addeinvoice"));
    $('.btn_einvoice').click(function(e)
    {
        myModalsup.show();
    });
    $('.dltReturn').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn trả lại hàng cho nhà cung cấp?',
            text: "Bạn không thể thay đổi dữ liệu sau khi thực hiện",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn trả!'
            }).then((result) => {
            if (result.isConfirmed) {
                // alert(form);
                form.submit();
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
            }
        });
    });
    $('.dltBtn').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa không?',
            text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
            if (result.isConfirmed) {
                // alert(form);
                form.submit();
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
            }
        });
    });
</script>
<script>
    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
           
            // Do something
            var data=$(this).val();
            var form=$(this).closest('form');
            if(data.length > 0)
            {
                form.submit();
            }
            else
            {
                  Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });

    var product_search = $('#supplier_search');

    product_search.autocomplete({
        source: function(request, response) {
            $('#btn_tim').prop('disabled', true);
            $.ajax({
                type: 'GET',
                url: '{{route('supplier.jsearch')}}',
                data: {
                    data: request.term,
                },
                success: function(data) {
                    console.log(data);
                    response( jQuery.map( data.msg, function( item ) {
                        return {
                        id: item.id,
                        value: item.title,
                       
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {

           $('#supplier_id').val(ui.item.id);
           $('#btn_tim').prop('disabled', false);
           
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td>'
            +'<span   style="line-height:220%">'+ item.value +'</span></td></tr></table>')
            .appendTo(ul);
        };;
  
</script>
@endsection