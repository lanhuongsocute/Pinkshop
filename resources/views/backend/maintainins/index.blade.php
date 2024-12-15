@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách nhận bảo hành
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('maintainin.create')}}" class="btn btn-primary shadow-md mr-2">Thêm nhận bảo hành</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$maintainins->currentPage()}} trong {{$maintainins->lastPage()}} trang</div>
            
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">MÃ</th>
                        <th class="whitespace-nowrap">KHÁCH HÀNG</th>
                        <th class="whitespace-nowrap">SẢN PHẨM</th>
                        <th class="whitespace-nowrap">SL</th>
                        <th class="text-center whitespace-nowrap">CHI PHÍ</th>
                        <th class="text-center whitespace-nowrap">THANH TOÁN</th>
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                        <th class="text-center whitespace-nowrap">KẾT QUẢ</th>
                        <th class="text-center whitespace-nowrap">NGÀY LẬP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintainins as $item)
                        <?php
                            $classname = "text-danger";
                            if($item->status == 'sent')
                                $classname = "text-warning";
                            if($item->status == 'back')
                                $classname = "text-warning";
                            if($item->status == 'returned')
                                $classname = "text-warning";
                                if($item->status == 'finished')
                                $classname = "text-success";
                        ?>
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
                        <td>
                             {{ $item->id }}
                        </td>
                        <td>
                            <a  class="tooltip "  title="Xem công nợ"  href="{{route('customer.show',$item->customer_id)}}">  
                                    {{\App\Models\User::where('id',$item->customer_id)->value('full_name')}}
                            </a>
                        </td>
                        <td>
                            {{\App\Models\Product::where('id',$item->product_id)->value('title')}}
                        </td>
                        <td>
                            {{ $item->quantity }}
                        </td>
                        <td>
                            {{ $item->final_amount }}
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
                            <span class="{{$classname}}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            {{ $item->result }}
                        </td>
                        
                        <td>
                            {{$item->created_at}}
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                            <div class="dropdown py-3 px-1 ">  
                                <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                    hoạt động
                                </a>
                                <div class="dropdown-menu w-40"> 
                                    <ul class="dropdown-content">
                                        
                                         
                                        <li><a href="{{route('maintainin.show',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                       @if($item->status != 'finished')
                                            <li><a onclick="save_newreturn({{$item->id}})"  class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Kết quả </a></li>
                                        @endif
                                        @if($item->status == 'returned')
                                        <li> 
                                            <a   class="dropdown-item flex items-center mr-3" href="{{route('maintainin.viewfinish',$item->id)}}"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Hoàn khách </a>
                                      
                                        </li>
                                        @endif
                                        @if($item->paid_amount < $item->final_amount && $item->status == 'finished')
                                            <li> <a  onclick="on_paid({{$item->id}})" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i> Trả tiền </a></li> 
                                        @endif   
                                        @if($item->status == 'received')
                                            <li><a href="{{route('maintainin.edit',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        @endif
                                        @if($item->status == 'returned' || $item->status == 'finished')
                                            <li><a href="{{route('maintainin.edit_paid',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        @endif
                                        @if($item->status != 'returned' && $item->status != 'finished')
                                         
                                        <li> 
                                            <form action="{{route('maintainin.destroy',$item->id)}}" method = "post">
                                            @csrf
                                            @method('delete')
                                            <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
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
                {{$maintainins->links('vendor.pagination.tailwind')}}
            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
    <!-- BEGIN: Modal   -->
<div  id="myModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thêm kết quả phiếu <span id='spid'></span></h2>    
                <input id="return_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Nhận xét</label>
                    <textarea   id="comment"   class="form-control" value ="">
                    </textarea>
                </div>
                    
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Chi phí</label>
                    <input id="maincost"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Kết quả</label>
                    <select id="result" class = "form-control">
                        <option value="pending">chưa xác định</option>
                        <option value="damaged">hư hỏng</option>
                        <option value="ok">tốt</option>
                    </select>
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_newreturn" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div>  
    <!-- BEGIN: Modal   -->
<div  id="myModalreturn" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Trả sản phẩm bảo hành phiếu <span id='spid0'></span></h2>    
                <input id="finish_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
            <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền phải trả:</label>
                    <br/>
                    <span id="sp_final_amount"> </span> 
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền nhận</label>
                    <input id="paid_amount"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tài khoản</label>
                    <select id="bank_id" name="bank" class="form-select mt-2 sm:mr-2"    >
                        @foreach ($bankaccounts as $bank)
                            <option value="{{$bank->id}}" {{old('bank_id')==$bank->id?'selected':''}}>{{$bank->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-help">
                    * kiểm tra kỹ lại thông tin, sau khi bạn bấm Lưu, hàng hóa sẽ xuất kho bảo hành, hoàn trả cho khách nên không thể thay đổi!
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_finish" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div>  
     <!-- BEGIN: Modal   -->
<div  id="myModalpaid" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Trả tiền phiếu bảo hành <span id='spid1'></span></h2>    
                <input id="paid_id" value ="0" type="hidden"/>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
            <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền phải trả:</label>
                    <br/>
                    <span id="sp_final_amount1"> </span> 
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Số tiền nhận</label>
                    <input id="paid_amount1"  type="number" class="form-control" placeholder="">
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tài khoản</label>
                    <select id="bank_id1" name="bank" class="form-select mt-2 sm:mr-2"    >
                        @foreach ($bankaccounts as $bank)
                            <option value="{{$bank->id}}" {{old('bank_id')==$bank->id?'selected':''}}>{{$bank->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_finish1" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </div>
    </div>
 </div> 
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
<script>
  $('#comment').val('');
    const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModal"));
    const myModalreturn = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModalreturn"));
    const myModalpaid = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModalpaid"));
    function save_finish(id)
    { 
        var finish_id = document.getElementById('finish_id');
        finish_id.value = id;
       
        var spid = document.getElementById('spid0');
        spid.innerText = id;
        $.ajax({
            url:"{{route('maintainin.getitem')}}",
            type:"get",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#sp_final_amount').text(Intl.NumberFormat().format( item.final_amount));
                     $('#paid_amount').val(item.paid_amount);
                    myModalreturn.show();
                }
            }
            
        });
    }
    
    function on_paid(id)
    { 
        var paid_id = document.getElementById('paid_id');
        paid_id.value = id;
       
        var spid = document.getElementById('spid1');
        spid.innerText = id;
        $.ajax({
            url:"{{route('maintainin.getitem')}}",
            type:"get",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#sp_final_amount1').text(Intl.NumberFormat().format( item.final_amount - item.paid_amount));
                     $('#paid_amount1').val(item.final_amount - item.paid_amount);
                    myModalpaid.show();
                }
            }
            
        });
    }
    
    function save_newreturn(id)
    {
       
        var return_id = document.getElementById('return_id');
        return_id.value = id;
       
        var spid = document.getElementById('spid');
        spid.innerText = id;
        $.ajax({
            url:"{{route('maintainin.getitem')}}",
            type:"get",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                     var item = response.msg;
                     $('#comment').val(item.comment);
                     $('#maincost').val(item.maincost);
                    $('#result').val(item.result);
                    
                     myModal.show();
                }
            }
            
        });
    }
    $( "#btn_newreturn" ).on( "click", function() {
        myModal.hide();
        var id = $('#return_id').val();
        var comment = $('#comment').val();
        var maincost = $('#maincost').val();
        var result = $('#result').val();
        if(maincost <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"{{route('maintainin.savereturn')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                comment:comment,
                maincost:maincost,
                result:result,
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );

    $( "#btn_finish1" ).on( "click", function() {
        myModalreturn.hide();
        var id = $('#paid_id').val();
        var paid_amount = $('#paid_amount1').val();
        var bank_id = $('#bank_id1').val();
        if(paid_amount <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"{{route('maintainin.storepaid')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                paid_amount:paid_amount,
                bank_id:bank_id,
                 
            },
            success:function(response){
                console.log(response);
                myModalpaid.hide();
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );

    $( "#btn_finish" ).on( "click", function() {
        myModalreturn.hide();
        var id = $('#finish_id').val();
        var paid_amount = $('#paid_amount').val();
        var bank_id = $('#bank_id').val();
        if(paid_amount <  0 )
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Số tiền không thể âm!',
                    'error'
                ); 
            return;
        }
        if(id == null || id==0)
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'mã phiếu không hợp lệ!',
                    'error'
                ); 
            return;
        }
        
        $.ajax({
            url:"{{route('maintainin.savefinish')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                id:id,
                paid_amount:paid_amount,
                bank_id:bank_id,
                 
            },
            success:function(response){
                console.log(response);
                 if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        response.msg,
                        'success'
                    ); 
                    window.location.reload(true);
                }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
            }
            
        });
    } );
</script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
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
 
@endsection