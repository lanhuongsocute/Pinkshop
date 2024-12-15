@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm bảo hành
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('maintainin.store')}}">
                @csrf
                <div class="intro-y box p-5">
                    <div class=" mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn khách hàng
                                </label>
                                <div class="flex">
                                    <input type="text" id='customer_search' 
                                        class="form-control py-3   " placeholder="Tên hoặc số điện thoại">
                                    <a id ="btn_shownew" class=" btn btn-primary w-32 shadow-md ml-auto" style="width:50px">
                                        <i data-lucide="user-plus"   ></i>
                                    </a>
                                </div>
                                <input type="hidden" name="customer_id" id="customer_id" value="0" />
                                 
                    </div>
                    <div class=" mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn hàng hóa
                                </label>
                                <div class="flex">
                                    <input type="text" id='product_search' 
                                        class="form-control py-3   " placeholder="Tên">
                                    <a id ="btn_shownewpro" class=" btn btn-primary w-32 shadow-md ml-auto" style="width:50px">
                                        <i data-lucide="plus"   ></i>
                                    </a>
                                </div>
                                <input type="hidden" id="product_id" name="product_id" value="0" />
                              
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số lượng</label>
                        <input onchange="check_quantity()" id="quantity" name="quantity" value="1"  type="number" class="form-control" placeholder=" ">
                    </div>
                    <div class="mt-3">
                        <label  for="regular-form-1" class="form-label">Số series</label>
                        <input id='seri' onchange="updateQuantityS()"  class="form-control" type="text" id= "series" name="series" value='{{old("series")}}'/>
                        <div class="form-help"> cách nhau bằng dấu , </div>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea onchange="checklength()" class="form-control" name="description" id= "description"   >
                            {{old('description')}}
                        </textarea>
                        <div class="form-help">
                            * Không quá 200 chữ.
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tiền vận chuyển </label>
                        <input id="shipcost" name="shipcost"   type="number" class="form-control" placeholder=" ">
                        <div class="form-help">
                            * đây là chi phí cửa hàng bảo hành qua đơn vị vận chuyển. Chi phí này được ghi nợ cho khách hàng.
                        </div>

                    </div>
                    <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn tài khoản trả tiền vận chuyển
                                </label>
                                <select id="bank_id" name="bank_id" class="form-select mt-2 sm:mr-2"    >
                                    @foreach ($bankaccounts as $bank)
                                        <option value="{{$bank->id}}" {{old('bank_id')==$bank->id?'selected':''}}>{{$bank->title}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                    <div class="mt-3">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>    {{$error}} </li>
                                    @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end content -->
  <!-- BEGIN: Modal   -->
<div  id="myModal" class="modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="user"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thêm khách hàng </h2>    
                
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tên</label>
                    <input   id="full_name" type="text" class="form-control" placeholder="tên">
                </div>
                    
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Điện thoại</label>
                    <input id="phone"  type="text" class="form-control" placeholder="điện thoại">
                    <div class="form-help">Kiểm tra lại số điện thoại, thông tin nãy sẽ không được chỉnh sửa sau khi hoàn thành việc thêm mới.</div>
                </div>
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Địa chỉ</label>
                    <input id="address"   type="text" class="form-control" placeholder="địa chỉ">
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_newuser" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
         </div>
 </div> 
  <!-- BEGIN: Modal   -->
  <div  id="myModalpro" class="modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog  ">
         <div class="modal-content">
             <!-- BEGIN: Modal Header -->
             <div class="modal-header">
                <i data-lucide="box"  ></i> <h2 class="font-medium text-base mr-auto"> &nbsp; Thêm hàng hóa</h2>    
                
             </div> <!-- END: Modal Header -->
            <div class="modal-body p-5 text-left"> 
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Tên</label>
                    <input   id="title" type="text" class="form-control" placeholder="tên">
                </div>
                    
                <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Bảo hành</label>
                    <input id="expired"  type="text" class="form-control" placeholder=" ">
                    <div class="form-help"> * Tính theo tháng</div>
                </div>
                <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="cat_id ">Danh mục</label>
                            <br/>
                            <select id="cat_id"  class="form-select mt-2 sm:mr-2"   >
                                @foreach($categories as $cat)
                                    <option value ="{{$cat->id}}"> {{ $cat->title}} </option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="text-right mt-5">
                        <button type="button" id="btn_newproduct" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
         </div>
 </div> 
 <!-- end modal -->   
@endsection

@section ('scripts')


 
 
    
 
     
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
  
</script>
<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});
function checklength()
{
    var description = document.getElementById('description') ;
    var temp = description.value;
    let result = temp.substr(0, 200);
    description.value = result;
}
function check_quantity()
{
    var quantity = document.getElementById('quantity') ;
    var temp = quantity.value;
    if(temp <= 0|| temp == null)
        quantity.value = 1;
     
}
$(document).ready(function(){ //Your code here 

    ///////////

    const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModal"));
    const myModalpro = tailwind.Modal.getOrCreateInstance(document.querySelector("#myModalpro"));
   
    $( "#btn_shownewpro" ).on( "click", function() {
            myModalpro.show();
    });
    $( "#btn_newproduct" ).on( "click", function() {
        // alert('click');
        myModalpro.hide();
        var title = $('#title').val();
        var expired = $('#expired').val();
        var cat_id = $('#cat_id').val();
        if(title == null || title=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin tên khách hàng!',
                    'error'
                ); 
            return;
        }
         
        if(cat_id == null || cat_id=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa chọn danh mục!',
                    'error'
                ); 
            return;
        }
        $.ajax({
            url:"{{route('product.addm')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                title:title,
                expired:expired,
                cat_id:cat_id,
            },
            success:function(response){
                myModal.hide();
                if(response.status == true)
                {
                    Swal.fire(
                        'Thành công',
                        "Đã thêm hàng hóa",
                        'success'
                     ); 
                    }
                else
                {
                    Swal.fire(
                        'Lỗi xãy ra',
                        response.msg,
                        'error'
                    ); 
                }
                console.log(response.msg);
            }
            
        });
    } );
   
    $( "#btn_shownew" ).on( "click", function() {
        myModal.show();
        
   });
   $( "#btn_newuser" ).on( "click", function() {
        // alert('click');
        var full_name = $('#full_name').val();
        var phone = $('#phone').val();
        var address = $('#address').val();
        if(full_name == null || full_name=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin tên khách hàng!',
                    'error'
                ); 
            return;
        }
        if(phone == null || phone=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin điện thoại!',
                    'error'
                ); 
            return;
        }
        if(address == null || address=='')
        {
            Swal.fire(
                    'Lỗi xãy ra',
                    'Chưa có thông tin địa chỉ!',
                    'error'
                ); 
            return;
        }
        $.ajax({
            url:"{{route('customer.add')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                full_name:full_name,
                phone:phone,
                address:address,
            },
            success:function(response){
                myModal.hide();
                if(response.status == true)
                {
                    var cell = response.msg;
                     $('#customer_search').val(cell.full_name);
                    $('#customer_id').val(cell.id);
                }
                else
                {
                    Swal.fire(
                    'Lỗi xãy ra',
                    response.msg,
                    'error'
                ); 
                }
                
                console.log(response.msg);
            }
            
        });
    } );

    //////////////////

    var description = document.getElementById('description') ;
    description.value = "";
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
            var warehouse_id = $('#warehouse_id').val();
        
            $.ajax({
                type: 'GET',
                url: '{{route('product.msearch')}}',
                data: {
                    data: request.term,
                },
                success: function(data) {
                    console.log(data);
                   
                    response( jQuery.map( data.msg, function( item ) {
                        var imageurls = item.photo.split(",");
                    
                        return {
                        id: item.id,
                        value: item.title,
                        imgurl: imageurls[0],
                        
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
          
            $('#product_id').val(ui.item.id);
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500">No:' + (item.qty==null?0:item.qty) 
            +"</span></td></tr></table>")
            .appendTo(ul);
        };;
    // /////////customer search//////////////////////
    ///////////////////////////////////////////////
    var customer_search = $('#customer_search');
    customer_search.autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: '{{route('customer.jsearch')}}',
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
           
           $('#customer_id').val(ui.item.id);
           
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
    //////////end cusstomer search //////   

});
    

</script>
<script>
  function cleanArray(arr) {
    // Remove empty values
    let noSpacesArray = arr.map(item => {
        if (typeof item === 'string') {
            return item.replace(/\s+/g, '');
        }
        return item;
    });
    let cleanedArray = noSpacesArray.filter(item => item !== null && item !== undefined && item !== '');
    // Remove duplicate values from the array
    let uniqueArray = [...new Set(cleanedArray)];
    return uniqueArray;
}
    function arrayToString(arr) {
        return arr.join(', ');
    }
    function updateQuantityS()
    {
        ip = document.getElementById('seri');
        var num = 0;
        const myArray = ip.value.split(",");
        let cleanedArray = cleanArray(myArray);
        let result = arrayToString(cleanedArray);
        ip.value = result;
        ipq = document.getElementById('quantity');
        ipq.value = cleanedArray.length;
    // alert(ip.value);
    
        if(ipq.value > stock)
        {
            Swal.fire(
                'Không hợp lệ!',
                'Số lượng lớn hơn số lượng tồn kho!',
                'error'
            );
            ipq.value = 0;
            ip.value = '';
        }
    }

</script>
@endsection