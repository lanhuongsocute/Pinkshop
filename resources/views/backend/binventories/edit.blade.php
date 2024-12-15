@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh tồn kho đầu kỳ
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('binventory.update',$binventory->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div>
                         
                        <label for="regular-form-1" class="form-label">Sản phẩm</label>
                        <input id="product_search" value="{{\App\Models\Product::where('id',$binventory->product_id)->value('title')}}"  type="text" class="form-control" placeholder="tên">
                        <input type="hidden" value="{{$binventory->product_id}}" id= "product_id" name="product_id"/>
                    </div>
                    <div class="mt-3">
                        <div id="div_parent_id" class="  flex flex-col sm:flex-row items-center">
                            <label style="min-width:50px  " class="form-select-label" for="status">Kho</label>
                            <select id="warehouse_id" name="wh_id" class="form-select mt-2 sm:mr-2" aria-label="Default select example"   >
                                @foreach ($warehouses as $wh)
                                    <option value="{{$wh->id}}" {{$binventory->wh_id==$wh->id?'selected':''}}>{{$wh->title}}</option>
                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số lượng</label>
                        <input   class="form-control" type="text" id= "quantity" value="{{$binventory->quantity}}" name="quantity"  />
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Đơn giá</label>
                        <input   class="form-control" type="text" id= "price" name="price" value='{{$binventory->price}}'/>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Series</label>
                        <input id="seri" onchange="updateQuantityS()" class="form-control" type="text" id= "series" 
                        name="series" value="{{$series}}"/>
                        <div class="form-help"> * Các số series cách nhau dấu ,</div>
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
@endsection

@section ('scripts')

 

<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet">  
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});
$(document).ready(function(){ //Your code here 

   
    var warehouse_id = $('#warehouse_id');
    warehouse_id.change(function(e){
        e.preventDefault();
        var whid = $(this).val();
        var data = $('#product_id').val();
        if(whid && data)
        {
            $.ajax({
                type: 'GET',
                url: '{{route('product.stock_quantity')}}',
                data: {
                    product_id: data,
                    warehouse_id: whid,
                },
                success: function(response) {
                    console.log(response);
                    if(response.status == false)
                    {
                        $('#quantity').val(0);
                    }
                    else
                    {
                        $('#quantity').val(response.msg);
                    }
                    
                }
            });
        }
        
    });
       
   
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
            var warehouse_id = $('#warehouse_id').val();
            
            // var idnhom = $('#selectgroupid').val();
            // console.log('warehouseid' + warehouse_id);
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearch')}}',
                data: {
                    data: request.term,
                    warehouse_id: warehouse_id,
                
                },
                success: function(data) {
                    console.log(data);
                    response( jQuery.map( data.msg, function( item ) {
                        var imageurls = item.photo.split(",");
                    
                        return {
                        id: item.id,
                        value: item.title,
                        imgurl: imageurls[0],
                        qty: item.quantity,
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            if(ui.item.qty == null)
                $('#quantity').val(0);
            else
                $('#quantity').val(ui.item.qty);
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