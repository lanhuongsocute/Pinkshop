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
            Điều chỉnh product
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('product.update',$product->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" value="{{$product->title}}" type="text" class="form-control" placeholder="title">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Photo</label>

                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="button-image" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                                </a>
                            </span>
                            <input id="image_label" value="{{$product->photo}}" class="form-control" type="text" name="photo">
                        </div>
                        <div id="holder" class="flex" style="margin-top:15px;max-height:150px;">
                                    <?php
                                        $photos = explode( ',', $product->photo);
                                        foreach($photos as $photo)
                                        {
                                            echo '<div class="w-10 h-10 image-fit zoom-in">
                                                <img class="tooltip rounded-full"  src="'.$photo.'"/>
                                            </div>';
                                        }
                                    ?>
                        </div>
                    </div>
                     
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả ngắn</label>
                       
                        <textarea class="form-control" id="editor1" name="summary" ><?php echo $product->summary; ?></textarea>
                    </div>
                   
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor" name="description" id="editor2"  >
                            <?php echo $product->description; ?>
                        </textarea>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá bán</label>
                        <input id="price_out" name="price" type="number" class="form-control" value="{{$product->price}}">
                    </div>
                   
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Danh mục</label><br/>
                            <select name="cat_id"  class="form-select mt-2 sm:mr-2"   >
                                @foreach($categories as $cat)
                                    <option value ="{{$cat->id}}" {{$cat->id == $product->cat_id?'selected':''}}> {{ $cat->title}} </option>
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
                                    <option value ="{{$brand->id}}" {{$brand->id == $product->brand_id?'selected':''}}> {{ $brand->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Kích thước</label>
                        <input id="size" name="size" value="{{$product->size}}" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Cân nặng</label>
                        <input id="weight" name="weight" value="{{$product->weight}}" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Bảo hành</label>
                        <input id="expired" name="expired" value="{{$product->expired}}"
                            type="number" class="form-control" placeholder=" ">
                        <div class="form-help mt-3">
                            * Tính theo tháng
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Loại</label>
                           
                            <select name="type" class="form-select mt-2 sm:mr-2"   >
                                <option value ="normal" {{$product->type=='normal'?'selected':''}}>Normal</option>
                                <option value = "service" {{$product->type=='service'?'selected':''}}>Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="post-form-4" class="form-label">Tags</label>
                    
                        <select id="select-junk" name="tag_ids[]" multiple placeholder=" ..." autocomplete="off">
                    
                        <!-- <select name="tag_ids[]" data-placeholder="tag .."   class="tom-select w-full" id="post-form-4" multiple> -->
                            @foreach ($tags as $tag )
                                <option value="{{$tag->id}}" 
                                    <?php 
                                        foreach($tag_ids as $item)
                                        {
                                                if($item->tag_id == $tag->id)
                                                    echo 'selected';
                                        } 
                                    ?>
                                >{{$tag->title}}</option>
                            @endforeach
                        </select>
                    </div>     
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                                <option value ="active" {{$product->status=='active'?'selected':''}}>Active</option>
                                <option value = "inactive" {{$product->status =='inactive'?'selected':''}}>Inactive</option>
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
        maxItems: 100,
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
 
</script>
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