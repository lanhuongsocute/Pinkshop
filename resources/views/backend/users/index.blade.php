@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách người dùng
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('user.create')}}" class="btn btn-primary shadow-md mr-2">Thêm người dùng</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$users->currentPage()}} trong {{$users->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('user.search')}}" method = "get">
                     
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>

       
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form action="{{route('user.sort')}}" method = "get" class="xl:flex sm:mr-auto" >
              
                <div class="sm:flex items-center sm:mr-4">
                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Sắp xếp cột: </label>
                    <select name="field_name" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="id">Thời gian</option>    
                        <option value="full_name">Tên</option>
                        <option value="role">Vai trò</option>
                        <option value="ugroup_id">Nhóm</option>
                        <option value="status">Trạng thái</option>
                    </select>
                </div>
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Loại</label>
                    <select name="type_sort" id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto" >
                        <option value="ASC" selected>tăng</option>
                        <option value="DESC" selected>giảm</option>
                    </select>
                </div>
               
                <div class="mt-2 xl:mt-0">
                    <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Go</button>
                </div>
            </form>
            <div class="flex mt-5 sm:mt-0">
                <button id="tabulator-print" class="btn btn-outline-secondary w-1/2 sm:w-auto mr-2"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </button>
                <div class="dropdown w-1/2 sm:w-auto">
                    <button class="dropdown-toggle btn btn-outline-secondary w-full sm:w-auto" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export <i data-lucide="chevron-down" class="w-4 h-4 ml-auto sm:ml-2"></i> </button>
                    <div class="dropdown-menu w-40">
                        <ul class="dropdown-content">
                            <li>
                                <a id="tabulator-export-csv" href="javascript:;" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export CSV </a>
                            </li>
                            <li>
                                <a id="tabulator-export-json" href="javascript:;" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export JSON </a>
                            </li>
                            <li>
                                <a id="tabulator-export-xlsx" href="javascript:;" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export XLSX </a>
                            </li>
                            <li>
                                <a id="tabulator-export-html" href="javascript:;" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Export HTML </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
   

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">TÊN</th>
                        <th class="text-center whitespace-nowrap">PHOTO</th>
                        <th class="text-center whitespace-nowrap">EMAIL</th>
                        <th class="whitespace-nowrap">LOẠI</th>
                        <th class="whitespace-nowrap">NHÓM</th>
                        <th class="text-center whitespace-nowrap">PHONE</th>
                        <th class="whitespace-nowrap">ĐỊA CHỈ</th>
                        
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $item)
                    <tr class="intro-x">
                        <td>
                            <a href="javascript:void(0)" data-id="{{$item->id}}" class="font-medium whitespace-nowrap showdata">{{$item->full_name}}</a> 
                        </td>
                       
                        <td class="w-20">
                            <div class="flex">
                                
                                    <?php
                                        $photos = explode( ',', $item->photo);
                                        foreach($photos as $photo)
                                        {
                                            echo '<div class="w-10 h-10 image-fit zoom-in">
                                                <img class="tooltip rounded-full"  src="'.$photo.'"/>
                                            </div>';
                                        }
                                    ?>
                                     
                                
                            </div>
                        </td>
                        <td class="text-left"><?php echo $item->email; ?></td>
                        <td class="text-center">{{$item->title}} </td>
                        <td class="text-center">{{$item->ugroup_id!= null?\App\Models\UGroup::where('id',$item->ugroup_id)->value('title'):''}} </td>
                        <td class="text-center">{{$item->phone}} </td>
                        <td class="text-left">{{$item->address}} </td>
                        
                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$item->status=="active"?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$item->id}}"
                            data-style="ios">
                        </td>
                        
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{route('user.edit',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                <form action="{{route('user.destroy',$item->id)}}" method = "post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                </form>
                               
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
                {{$users->links('vendor.pagination.tailwind')}}
            </nav>
           
        </div>
        <!-- END: Pagination -->
</div>
<!-- end content -->
  <!-- BEGIN: Modal   -->
<div  id="myModal" class="modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="file"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thông tin người dùng </h2>    
                <div id="div_img" class="w-10 h-10 image-fit zoom-in">
                 
                </div>
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-center"> 
                
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6 text-left">
                        <span class="form-label font-medium"> Tên:  </span> <br/> <span id='md_full_name'>name</span> <br/><br/>
                        <span class="form-label font-medium"> Email:  </span> <br/><span id='md_email'>email</span> <br/><br/>
                        <span class="form-label font-medium"> Vai trò:  </span> <br/><span id='md_role'>email</span> <br/><br/>
                        
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-6  text-left">
                        <span class="form-label font-medium"> Điện thoại:  </span> <br/><span id='md_phone'>phone</span> <br/><br/>
                        <span class="form-label font-medium"> Địa chỉ:  </span> <br/><span id='md_address'>dc</span> <br/><br/>
                        <span class="form-label font-medium"> Tình trạng:  </span><br/> <span class="py-1 px-2 rounded-full text-xs font-medium text-xs text-white"  id='md_status'>dc</span> <br/><br/>
                    </div>
                </div>
                <div class="text-left p-1">
                    <h3 class="form-label font-medium"> Thông tin thêm: </h3>
                    <p id='md_description'> mô tả </p>
                </div>
              
            </div>
         </div>
 </div> 
   <!-- END: Modal   -->
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
   const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModal"));
   $( ".showdata" ).on( "click", function() {
        var dataID = $(this).data('id');
        $.ajax({
            url:"{{route('user.detail')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                id:dataID,
            },
            success:function(response){
                var cell = response.msg;
                $('#md_full_name').text(cell.full_name);
                $('#md_email').text(cell.email);
                $('#md_role').text(cell.role);
                $('#md_status').text(cell.status);
                console.log(cell.status.localeCompare("inactive"));
                if(cell.status.localeCompare("inactive")==0)
                {
                    $('#md_status').removeClass("bg-success");
                    $('#md_status').addClass("bg-danger");
                    
                }    
                else
                {
                    $('#md_status').addClass("bg-success");
                    $('#md_status').removeClass("bg-danger");
                }    

                $('#md_phone').text(cell.phone);
                $('#md_address').text(cell.address);
                $('#md_description').html(cell.description);
                if(cell.photo)
                {
                    var img = '<img src="' + cell.photo +'" class = "rounded-full"/>';
                    $('#div_img').html(img);
                }
                
                myModal.show();
                console.log(response.msg);
            }
            
        });
    } );
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

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"{{route('user.status')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                mode:mode,
                id:id,
            },
            success:function(response){
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.msg,
                showConfirmButton: false,
                timer: 1000
                });
                console.log(response.msg);
            }
            
        });
  
});  
    
</script>
@endsection