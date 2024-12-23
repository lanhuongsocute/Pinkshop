@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách trả bảo hành từ nhà cung cấp
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('maintainback.create')}}" class="btn btn-primary shadow-md mr-2">Thêm trả bảo hành</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$maintainbacks->currentPage()}} trong {{$maintainbacks->lastPage()}} trang</div>
            
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">ĐỐI TÁC</th>
                        <th class="text-center whitespace-nowrap">CHI PHÍ</th>
                        <th class="text-center whitespace-nowrap">ĐÃ THANH TOÁN</th>
                        <th class="text-center whitespace-nowrap">NGƯỜI LẬP</th>
                       
                        <th class="text-center whitespace-nowrap">NGÀY LẬP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintainbacks as $item)
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
                            <a  class="tooltip "  title="Xem công nợ"  href="{{route('user.showsup',$item->supplier_id)}}">       
                                        {{\App\Models\User::where('id',$item->supplier_id)->value('full_name')}}
                            </a>
                        </td>
                        <td>
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
                            {{\App\Models\User::where('id',$item->vendor_id)->value('full_name')}}
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
                                        
                                        @if($item->paid_amount < $item->final_amount)
                                            <li> <a href=" {{ route('maintainback.paid',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i> Trả tiền </a></li> 
                                        @endif    
                                        <li><a href="{{route('maintainback.show',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                        <li><a href="{{route('maintainback.edit',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                         
                                        <li> 
                                            <form action="{{route('maintainback.destroy',$item->id)}}" method = "post">
                                            @csrf
                                            @method('delete')
                                            <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                            </form>
                                        </li>
                                         
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
                {{$maintainbacks->links('vendor.pagination.tailwind')}}
            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
  
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
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