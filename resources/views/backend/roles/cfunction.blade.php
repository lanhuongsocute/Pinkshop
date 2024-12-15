@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg  mt-10">
        Điều chỉnh chức năng cho role: <span class="font-medium"> {{$role->title}} </span>
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
         
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Alias</th>
                        <th class="whitespace-nowrap">Tên</th>
                        
                        <th class="text-center whitespace-nowrap">
                            <a class="btn" href="{{route('role.selectall',$role->id)}}" 
                            class="flex items-center mr-3" href="javascript:;"> 
                              Chọn hết</a>

                        </th>
                         
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($role_functions as $item)
                    
                    <tr class="intro-x">
                        <td>
                             {{$item->alias}} 
                        </td>
                        <td>
                        {{$item->title}} 
                        </td>
                         
                        <td class="text-center"> 
                            <input type="checkbox" 
                            data-toggle="switchbutton" 
                            data-onlabel="active"
                            data-offlabel="inactive"
                            {{$item->value==1?"checked":""}}
                            data-size="sm"
                            name="toogle"
                            value="{{$item->id}}"
                            data-style="ios">
                        </td>
                       
                         
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
    </div>
    <!-- END: HTML Table Data -->
        <!-- BEGIN: Pagination -->
         
        <!-- END: Pagination -->
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
 
<script>
 

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"{{route('role.functionstatus')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                mode:mode,
                id:id,
                role_id:{{$role->id}},
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