@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
<style>
     #thumbnail{
                                pointer-events: none;
                            }
                            #holder img{
                                border-radius: 0.375rem;
                                margin:0.2rem;
                            }
</style>
<style>
        /* Basic styling for the loader */
        #loader {
            display: none; /* Initially hidden */
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            z-index: 9999;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">

@endsection
@section('content')

<div class = 'content'>
<div id="loader"></div>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm hàng hóa
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <div class="mt-3">
                          <label> Tìm kiếm trên itcctv: </label>  <input type='text' id='product_search'/>
                            
                        <!-- <form action = "{{route('product.itcctv_jsearch')}}" method="post">
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
                            @csrf
                            <input name="searchdata" type = "text" />
                            <button type="submit" class="btn" id="btn_itcctv_search" > Tìm sản phẩm trên itcctv </button> <br/>
                        </form> -->
                    </div>
            <form method="post" action="{{route('product.store')}}">
                @csrf
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
                <div class="intro-y box p-5">
                   
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Photo</label>
                        <div class="   ">
                            <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                                <div   class="dropzone grid grid-cols-10 gap-5 pl-4 pr-5 py-5  "    url="{{route('upload.product')}}" >
                                    <div class="fallback"> <input name="file" type="file" /> </div>
                                    <div class="dz-message" data-dz-message>
                                        <div class=" font-medium">Kéo thả hoặc chọn nhiều ảnh.</div>
                                            
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                         <div id="div_anh"> </div>
                        <input type="hidden" id="photo" name="photo"/>
                    </div>
                    
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả ngắn</label>
                       
                        <textarea  class="form-control"  name="summary" id="editor1"  >{{old('summary')}}</textarea>
                    </div>
                   
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor" name="description" id="editor2"  >
                            {{old('description')}}
                        </textarea>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá bán</label>
                        <input id="price_out" name="price" type="number" class="form-control" value="0">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Kích thước</label>
                        <input id="size" name="size" type="text" class="form-control" placeholder=" ">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Cân nặng</label>
                        <input id="weight" name="weight" type="text" class="form-control" placeholder=" ">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Bảo hành</label>
                        <input id="expired" name="expired" type="number" class="form-control" placeholder=" ">
                        <div class="form-help mt-3">
                            * Tính theo tháng
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Danh mục</label><br/>
                            <select name="cat_id"  class="form-select mt-2 sm:mr-2"   >
                                @foreach($categories as $cat)
                                    <option value ="{{$cat->id}}"> {{ $cat->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:100px  " class="form-select-label" for="status">Nhà sản xuất</label><br/>
                            <select name="brand_id"  class="form-select mt-2 sm:mr-2"   >
                                <option value =""> --chọn nhà sản xuất-- </option>
                                @foreach($brands as $brand)
                                    <option value ="{{$brand->id}}"> {{ $brand->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Loại</label>
                          
                            <select name="type" class="form-select mt-2 sm:mr-2"   >
                                <option value ="normal" {{old('type')=='normal'?'selected':''}}>Normal</option>
                                <!-- <option value = "inactive" {{old('type')=='digital'?'selected':''}}>Digital</option> -->
                                <option value = "service" {{old('type')=='service'?'selected':''}}>Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                     
                     <label for="post-form-4" class="form-label">Tags</label>
                     <select id="select-junk" name="tag_ids[]" multiple placeholder="Start Typing..." autocomplete="off">
                        @foreach ($tags as $tag )
                            <option value="{{$tag->id}}" >{{$tag->title}}</option>
                        @endforeach
                     </select>
                    
             </div>     
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                                <option value ="active" {{old('status')=='active'?'selected':''}}>Active</option>
                                <option value = "inactive" {{old('status')=='inactive'?'selected':''}}>Inactive</option>
                            </select>
                        </div>
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
<script src="{{asset('backend/assets/js/product_v3.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
    
$(document).ready(function(){ //Your code here 
   

    var select = new TomSelect('#select-junk',{
        maxItems: null,
        allowEmptyOption: true,
        create: true, // Tạo mới các tag nếu không tồn tại
        plugins: ['remove_button'],
       
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd:function(){
                this.setTextboxValue('');
                this.refreshOptions();
            },
        
    });
    select.clear();
    // select.addItem('hha');
});
</script>
<script>
 
                // previewsContainer: ".dropzone-previews",
    Dropzone.instances[0].options.multiple = true;
    Dropzone.instances[0].options.autoQueue= true;
    Dropzone.instances[0].options.maxFilesize =  1; // MB
    Dropzone.instances[0].options.maxFiles =5;
    Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
    Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
                                           +' </div>';
    // Dropzone.instances[0].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
    Dropzone.instances[0].options.addRemoveLinks =  true;
    Dropzone.instances[0].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
 
    Dropzone.instances[0].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
    });
    Dropzone.instances[0].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        {
            var value_link = $('#photo').val();
            if(value_link != "")
            {
                value_link += ",";
            }
            value_link += response.link;
            $('#photo').val(value_link);
        }
           
        // console.log('File success successfully!' +$('#photo').val());
    });
    Dropzone.instances[0].on("removedfile", function (file ) {
            $('#photo').val('');
        console.log('File removed successfully!'  );
    });
    Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);
       
        console.log('error !' +message);
    });
     console.log(Dropzone.instances[0].options   );
 
    // console.log(Dropzone.optionsForElement);
 
</script>
<!-- <script src="{{asset('backend/assets/dist/js/ckeditor-classic.js')}}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('image');
</script> -->
<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
        function showLoader() {
            document.getElementById('loader').style.display = 'block';
        }

        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }


        var editor2;
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("upload.ckeditor")."?_token=".csrf_token()}}'
                },
                mediaEmbed: {previewsInData: true}
        })
        .then(editor => {
            editor2 = editor;
            console.log('editor2');
            console.log(editor2);
        })
        .catch( error => {
            console.error( error );
        })

</script>
<script>
        $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});
     ////////////////////////////////////////////////
    // /////////product search//////////////////////
    ///////////////////////////////////////////////
    var product_search = $('#product_search');
    product_search.autocomplete({
        source: function(request, response) {
            // console.log("toi biet ma");
            showLoader();
            $.ajax({
                type: 'GET',
                url: '{{route('product.itcctv_jsearch_get')}}',
                data: {
                    searchdata: request.term,
                },
                success: function(data) {
                    console.log(data);
                    hideLoader();
                    var p_products = JSON.parse(data.products);
                    response( jQuery.map( p_products, function( item ) {
                        var imageurls = item.photo.split(",");
                    
                        return {
                        id: item.id,
                        value: item.title,
                        price: item.price,
                        imgurl: imageurls[0],
                        qty: 0,
                        expired:0,
                        }
                    }));
                }
            });
        },
        response: function(event, ui) {
        
        },
        select: function(event, ui) {
            showLoader();
            $.ajax({
                type: 'GET',
                url: '{{route('product.itcctv_productdetail')}}',
                data: {
                    id: ui.item.id,
                },
                success: function(data) {
                    console.log(data);
                    hideLoader();
                    var product  = JSON.parse(data.product);
                    $('#title').val(product.title);
                    var textarea = document.getElementById('editor1');
                    textarea.value = product.summary;
                    product.description += "<p> Xem thêm chi tiết tại: <a href='https://itcctv.vn/product/view/"+product.slug+"'>"+ product.title+"</a></p>";
                    editor2.data.set(product.description);
                    $('#photo').val(product.photo);
                    var imageurls = product.photo.split(",");
                    $('#div_anh').html('<img src="'+imageurls[0]+'" style="max-width:100px"/>');
                    $('#price_out').val(product.price);
                    var tags = product.tag;
                    // Thêm từng tag vào TomSelect
                    // tags.forEach(tag => {
                    //     select.addItem(tag); // Thêm tag
                    // });
                    
                    // const tomSelectInstance = new TomSelect('#select-junk', {
                    //     create: true, // Cho phép tạo tag mới nếu chưa tồn tại
                    // });

                    // // Mảng các tag cần thêm
                 

                    // // Thêm từng tag vào TomSelect
                    // tags.forEach(tag => {
                    //     // Kiểm tra nếu tag chưa tồn tại trong options, tạo mới tag
                    //     if (!tomSelectInstance.options[tag]) {
                    //         tomSelectInstance.addOption({value: tag, text: tag}); // Tạo option mới
                    //     }
                    //     tomSelectInstance.addItem(tag); // Thêm tag vào danh sách
                    // });
                    if (!document.querySelector('#select-junk').tomselect) {
      // Khởi tạo TomSelect nếu chưa có
                const tomSelectInstance = new TomSelect('#select-junk', {
                        create: true, // Cho phép tạo tag mới nếu chưa tồn tại
                    });
                } else {
                    // Lấy instance hiện tại của TomSelect
                    var tomSelectInstance = document.querySelector('#select-junk').tomselect;
                }

                // Mảng các tag cần thêm
                // const tags = ["JavaScript", "HTML", "CSS", "React"];

                // Thêm từng tag vào TomSelect
                tags.forEach(tag => {
                    if (!tomSelectInstance.options[tag]) {
                        tomSelectInstance.addOption({value: tag.title, text: tag.title}); // Tạo option mới
                    }
                    tomSelectInstance.addItem(tag.title); // Thêm tag vào danh sách
                });
                }
            });
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item){
        $( ul ).addClass('dropdown-content overflow-y-auto h-52 ');
        return $("<li class='mt-10 dropdown-item  '></li>")
            .data("item.autocomplete", item )
            // .append('<div  style="clear:both"><div style="  pointer-events: none; width:50; float:left; "><img width="50" height="50" src="'+item.imgurl+'"/></div> <div style="float:left"> <span style=" pointer-events: none;">'+item.value+' </span> <br/> <span>số lượng: '+ item.qty +'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span> giá: '+  Intl.NumberFormat('en-US').format(item.price)+'</div></div>' )
            .append('<table style=" border:none; background:none" > <tr><td><img class="rounded-full" width="50" height="50" src="'+item.imgurl
            +'"/></td><td style=" text-align: left;"><span class="font-medium">'+ item.value 
            +'</span><br/> <span class=" text-slate-500"> No:' + (item.qty==null?0:item.qty) 
            +'</span>  <span class=" text-slate-500"> giá:' + (item.qty==null?0:item.qty)
            +'</span> <span class=" text-slate-500"> bảo hành:' + (item.expired==null?'':item.expired)+'</span>'
            +'</td></tr></table>')
            .appendTo(ul);
        };;
</script>
@endsection