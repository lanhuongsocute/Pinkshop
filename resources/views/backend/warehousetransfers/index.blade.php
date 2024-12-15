@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sachs phiếu chuyển kho
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('warehousetransfer.create')}}" class="btn btn-primary shadow-md mr-2">Thêm chuyển kho</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$warehousetrans->currentPage()}} trong {{$warehousetrans->lastPage()}} trang</div>
            
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">KHO NGUỒN</th>
                        <th class="whitespace-nowrap">KHO ĐÍCH</th>
                        <th class="text-center whitespace-nowrap">NGƯỞI GỬI</th>
                        <th class="text-center whitespace-nowrap">NGƯỜI NHẬN</th>
                        <th class="text-center whitespace-nowrap">TỔNG</th>
                        
                        <th class="text-center whitespace-nowrap">NGÀY LẬP </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehousetrans as $item)
                    
                    <tr class="intro-x ">
                        <td>
                            {{\App\Models\Warehouse::where('id',$item->wh_id1)->value('title')}}
                        </td>
                        <td>
                            {{\App\Models\Warehouse::where('id',$item->wh_id2)->value('title')}}
                        </td>
                        <td>
                            {{\App\Models\User::where('id',$item->vendor_id1)->value('full_name')}}
                        </td>
                        <td>
                            {{\App\Models\User::where('id',$item->vendor_id2)->value('full_name')}}
                        </td>
                        <td>
                            {{Number_format($item->total,0,'.,',',')}}
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
                                        <li><a href="{{route('warehousetransfer.show',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem </a></li>
                                        <!-- <li><a href="{{route('warehousetransfer.deprint',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="printer" class="w-4 h-4 mr-1"></i> phiếu gửi hàng </a></li>
                                        <li><a href="{{route('warehousetransfer.edit',$item->id)}}" class="dropdown-item flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <li> 
                                            <form action="{{route('warehousetransfer.destroy',$item->id)}}" method = "post">
                                            @csrf
                                            @method('delete')
                                            <a class="dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                            </form>
                                        </li> -->
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
                {{$warehousetrans->links('vendor.pagination.tailwind')}}
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

  
</script>
@endsection