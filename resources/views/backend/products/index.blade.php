@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách hàng hóa
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('product.create')}}" class="btn btn-primary shadow-md mr-2">Thêm hàng hóa</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$products->currentPage()}} trong {{$products->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('product.search')}}" method = "get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>

       
        <div   class=" intro-y col-span-12 flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form action="{{route('product.sort')}}" method = "get" class="xl:flex sm:mr-auto" >
                <!-- @csrf -->
                <div class="sm:flex items-center sm:mr-4">
                    <label style="min-width:80px" class="w-12 flex-none xl:w-auto xl:flex-initial mr-5">Sắp xếp cột: </label>
                    <select name="field_name" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="title">Tên</option>
                        <option value="cat_id">Danh mục</option>
                        <option value="stock">Tồn kho</option>
                         
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
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                    <select name="cat_id" id="tabulator-html-filter-field" class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="0">-chọn loại-</option>
                        @foreach ($cats as $cat )
                            <option value="{{$cat->id}}" {{$cat_id==$cat->id?'selected':''}}>{{$cat->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2 xl:mt-0">
                    <button id="tabulator-html-filter-go" type="submit" class="btn btn-primary w-full sm:w-16" >Go</button>
                </div>
            </form>
             
            <div class="flex mt-5 sm:mt-0">
                <a href="{{route('product.print')}}" id="tabulator-print" class="btn btn-outline-secondary w-1/2 sm:w-auto mr-2"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print </a>
                
            </div>
        </div>
        
   

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="">TÊN</th>
                        <th class="text-center whitespace-nowrap">PHOTO</th>
                        <th class="whitespace-nowrap">TỒN KHO</th>
                        <th class="whitespace-nowrap">GIÁ </th>
                         
                      
                        <th class="whitespace-nowrap">DANH MỤC</th>
                       
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                       
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $item)
                    <tr class="intro-x">
                        <td>
                            <a href="{{route('product.show',$item->id)}}" class="font-medium  ">{{$item->title}}</a> 
                           
                        </td>
                        
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
                        <td class="text-center"> 
                            {{$item->stock}} <br/>
                            <span class="form-help"> đã bán:  {{$item->sold}}</span> 
                        </td>
                        <td  > 
                           <span  >giá nhập:</span> <span>{{number_format($item->price_in,0,".",",")}}</span> <br/>
                           <span  >giá vốn &nbsp;:</span> <span> {{number_format($item->price_avg,0,".",",")}} </span><br/>
                           <span >giá bán  &nbsp;:</span>  <span>{{number_format($item->price_out,0,".",",")}} </span><br/>
                           <span  >giá web  &nbsp;:</span>  <span>{{number_format($item->price,0,".",",")}} </span><br/>
                        </td>
                        
                        
                        <td>
                         {{$item->cat_id!=null ? \App\Models\Category::where('id',$item->cat_id)->value('title'):''}}   
                     
                           
                        </td>
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
                        
                        <td class="table-report__action ">
                            <div class="flex justify-center items-center">
                                <div class="dropdown py-3 px-1 ">  
                                    <a class="btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown"> 
                                        hoạt động
                                    </a>
                                    <div class="dropdown-menu w-40"> 
                                        <ul class="dropdown-content">
                                        <li><a class="dropdown-item" href="{{route('product.priceview',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="credit-card" class="w-4 h-4 mr-1"></i> Thiết lập giá</a></li>
                                        <li><a class="dropdown-item" href="{{route('product.edit',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a></li>
                                        <li><a class="dropdown-item" href="{{route('inventory.viewproduct',$item->id)}}" class="flex items-center mr-3" > <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>nhập xuất kho</a></li>
                                        
                                                           
                                        <li>
                                            <form action="{{route('product.destroy',$item->id)}}" method = "post">
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
                {{$products->links('vendor.pagination.tailwind')}}
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

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"{{route('product.status')}}",
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