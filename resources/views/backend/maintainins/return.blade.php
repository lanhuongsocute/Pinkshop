@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Hoàn trả phiếu bảo hành
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('maintainin.savefinish',$maintainin->id)}}">
                @csrf
              
                <div class="intro-y box p-5">
                    <div class=" mt-3 ">
                        <input type="hidden" name="mi_id" value="{{$maintainin->id}}"/>
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Khách hàng
                                </label>
                                <div class="flex">
                                    <span>  {{\App\Models\User::find($maintainin->customer_id)->full_name}} </span>
                                </div>
                                <input type="hidden" name="customer_id" id="customer_id" value="{{$maintainin->customer_id}}" />
                                 
                    </div>
                    <div class=" mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn hàng hóa
                                </label>
                                <div class="flex">
                                    <input type="text" id='product_search' value="{{\App\Models\Product::find($maintainin->product_id)->title}}"
                                        class="form-control py-3   " placeholder="Tên">
                                    <a id ="btn_shownewpro" class=" btn btn-primary w-32 shadow-md ml-auto" style="width:50px">
                                        <i data-lucide="plus"   ></i>
                                    </a>
                                </div>
                                <input type="hidden" id="product_id" name="product_id" value="{{$maintainin->product_id}}" />
                              
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số lượng</label>
                        <input   id="quantity" name="quantity" 
                                value="{{$maintainin->quantity}}"  type="number" class="form-control" placeholder=" ">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Số series</label>
                        <input  id='seri' onchange="updateQuantityS()"   class="form-control" type="text" id= "series" name="series" value='{{$series}}'/>
                        <div class="form-help"> cách nhau bằng dấu , </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tiền vận chuyển </label>
                        <input id="shipcost" name="shipback" onchange="updateCost()" value="{{$ship_amount}}"
                             type="number" class="form-control" placeholder=" ">
                        <div class="form-help">
                            * đây là chi phí cửa hàng bảo hành qua đơn vị vận chuyển. Chi phí này được ghi nợ cho khách hàng.
                        </div>

                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label"> Chi phí bảo hành </label>
                        <input onchange="updateCost()" id="maincost" name="maincost"  value="{{$maintainin->maincost}}"
                             type="number" class="form-control" placeholder=" ">
                        

                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label"> Tổng phải trả </label>
                        <input type="hidden" id='totalcost' name="final_amount" value="0" 
                                class="form-control py-3 mt-2 " placeholder="Phí vận chuyển">
                                <span id = "sptotalcost"></span>

                    </div>
                    <div class="mt-3 ">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                    Đã thanh toán
                                </label>
                                <input type="number" id='paid_amount' name="paid_amount" value="0"
                                class="form-control py-3 mt-2 " placeholder="Phí vận chuyển">
                                
                            </div>
                    <div class="mt-3">
                                <label style="min-width:50px  " class="form-select-label" for="">
                                Chọn tài khoản nhận tiền
                                </label>
                                <select id="bank_id" name="bank_id" class="form-select mt-2 sm:mr-2"    >
                                    @foreach ($bankaccounts as $bank)
                                        <option value="{{$bank->id}}" {{$bank_id==$bank->id?'selected':''}}>{{$bank->title}}</option>
                                        
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
  
 <!-- end modal -->   
@endsection

@section ('scripts')


 
 
    
 
     
<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="Stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>


<script>
  function updateCost()
  {
    var shipcostip = document.getElementById('shipcost') ;
    shipcostip.value = parseInt(shipcostip.value );
    var totalcostip = document.getElementById('totalcost') ;
    var maincostip = document.getElementById('maincost') ;
    maincostip.value =parseInt(maincostip.value );
    var totalcost = parseInt(shipcostip.value ) + parseInt(maincostip.value );
    totalcostip.value = totalcost;
    var sptotalcost = document.getElementById('sptotalcost');
    sptotalcost.innerHTML = Intl.NumberFormat().format( totalcostip.value);
  }
 
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
    var temp = description.value;
    if(temp <= 0|| temp == null)
        quantity.value = 1;
     
}
$(document).ready(function(){ //Your code here 

    ///////////

     
    updateCost();
    //////////////////

    var description = document.getElementById('description') ;
    description.value = '{{$maintainin->description}}';
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