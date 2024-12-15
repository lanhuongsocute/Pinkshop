@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách tạo thành phẩm
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('combocreation.create')}}" class="btn btn-primary shadow-md mr-2">Thêm phiếu tạo combo</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$combocreations->currentPage()}} trong {{$combocreations->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                 
            </div>
        </div>

       
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
            
            
        </div>
        
   

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">ẢNH</th>
                        <th class="">TÊN</th>
                        <th class="whitespace-nowrap">SỐ LƯỢNG</th>
                        <th class="whitespace-nowrap">NGƯỜI TẠO</th>
                        <th class="text-center whitespace-nowrap">NGÀY TẠO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($combocreations as $item)
                    <tr class="intro-x">
                        <td class=" ">
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
                        <td>
                            <a href="{{route('combocreation.show',$item->id)}}" class="font-medium  ">{{$item->title}}</a> 
                        </td>
                        <td class="text-center"> 
                            {{$item->quantity}}  
                        </td>
                        <td> 
                           {{\App\Models\User::find($item->user_id)->full_name}} 
                          
                        </td>
                        
                        
                        
                        <td class="text-center"> 
                             {{$item->created_at}}
                        </td>
                        
                        <td class="table-report__action ">
                            <div class="flex justify-center items-center">
                                <div class="dropdown py-3 px-1 ">  
                                    <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                        hoạt động
                                    </a>
                                    <div class="dropdown-menu w-40"> 
                                        <ul class="dropdown-content">
          
                                        <li><a class="dropdown-item" href="{{route('combocreation.edit',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <li>
                                            <form action="{{route('combocreation.destroy',$item->id)}}" method = "post">
                                                @csrf
                                                @method('delete')
                                                <a class=" dropdown-item flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                            </form>
                                        </li>
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
                {{$combocreations->links('vendor.pagination.tailwind')}}
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