@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<style>
     #thumbnail{
                                pointer-events: none;
                            }
                            #holder img{
                                border-radius: 0.375rem;
                                margin:0.2rem;
                            }
</style>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
@endsection
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm hàng hóa
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('product.store')}}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Photo</label>

                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="button-image" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                                </a>
                            </span>
                            <input id="image_label" class="form-control" type="text" name="photo">
                        </div>
                        <div id="holder" style="margin-top:15px;max-height:100px;">
                        </div>
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
 <script>
    var select = new TomSelect('#select-junk',{
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd:function(){
                this.setTextboxValue('');
                this.refreshOptions();
            },
        create: true
        
    });
    select.clear();
</script>
<!-- <script src="{{asset('backend/assets/dist/js/ckeditor-classic.js')}}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('image');
</script> -->
<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("upload.ckeditor")."?_token=".csrf_token()}}'
                },
                mediaEmbed: {previewsInData: true}
            

        })

        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })

</script>
<script>
        document.addEventListener("DOMContentLoaded", function() {

        document.getElementById('button-image').addEventListener('click', (event) => {
        event.preventDefault();

        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
        });
        });

        // set file link
        function fmSetLink($url) {
        document.getElementById('image_label').value = $url;
        document.getElementById('holder').innerHTML = '<img src = "'+ $url +'" width ="100"/>';
        }
</script>
@endsection