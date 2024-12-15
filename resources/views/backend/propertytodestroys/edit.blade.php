@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh chuyển kho hủy
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('propertytodestroy.update',$ptd->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                <div class="mt-3">
                         
                         <label for="regular-form-1" class="form-label">Sản phẩm</label>
                         <input id="product_search" value="{{\App\Models\Product::where('id',$ptd->product_id)->value('title')}}"  type="text" class="form-control" placeholder="tên">
                         <input type="hidden" id= "product_id" name="product_id" value = "{{$ptd->product_id}}"/>
                     </div>
                
                   
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số lượng</label>
                        <input  onchange="updateQuantity()"  class="form-control" type="text" id= "quantity" name="quantity" value='{{$ptd->quantity}}'/>
                        <div class="form-help">
                            (Tồn kho hiện tại: <span id="spstock">{{\App\Models\InventoryProperties::where('product_id',$ptd->product_id)->value('quantity') + $ptd->quantity}} </span> )
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Đơn giá</label>
                        <input   class="form-control" type="text" id= "price" name="price" value='{{$ptd->price}}'/>
                        <div class="form-help"> * nên để giá trị mặc định nếu không chắc về giá trị sản phẩm </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số series</label>
                        <input  id='seri' onchange="updateQuantityS()"   class="form-control" type="text" id= "series" name="series" value='{{$series}}'/>
                        <div class="form-help"> cách nhau bằng dấu , </div>
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
            <!-- end form layout -->
        </div>
    </div>
</div>
@endsection

@section ('scripts')

<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet">  
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});
var product_id = {{$ptd->product_id}};
var quantity = {{$ptd->quantity}};
var stock = {{\App\Models\InventoryProperties::where('product_id',$ptd->product_id)->value('quantity') + $ptd->quantity}} ;
function updateQuantity( )
{
    ip = document.getElementById('quantity');
    // alert(ip.value);
    
    if(ip.value > stock)
    {
        Swal.fire(
            'Không hợp lệ!',
            'Số lượng lớn hơn số lượng tồn kho!',
            'error'
        );
        ip.value = stock;
    }
    if(ip.value < 0)
    {
        Swal.fire(
            'Không hợp lệ!',
            'Số lượng phải lớn hơn 0!',
            'error'
        );
        ip.value = stock;
    }
}

$(document).ready(function(){ //Your code here 
   
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            var warehouse_id = $('#warehouse_id').val();
            
            $.ajax({
                type: 'GET',
                url: '{{route('product.jsearchptw')}}',
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
                        price:item.price,
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            if(ui.item.qty == null)
             {
                $('#quantity').val(0);
                stock = 0;
             }  
            else
            {
                stock = ui.item.qty;
                $('#quantity').val(1);
            }    
            $('#product_id').val(ui.item.id);
            $('#price').val(ui.item.price);
            $('#spstock').html(stock);
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